<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Initially developped for :
 * Université de Cergy-Pontoise
 * 33, boulevard du Port
 * 95011 Cergy-Pontoise cedex
 * FRANCE
 *
 * @package   block_coursesshowcase
 * @copyright 2018 Brice Errandonea <brice.errandonea@u-cergy.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * File : checkcohorts.php
 * Import cohorts and fill them with existing students.
 */

namespace block_coursesshowcase\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .'/course/lib.php');
require_once($CFG->libdir .'/filelib.php');
require_once($CFG->libdir .'/accesslib.php');

class checkcohorts extends \core\task\scheduled_task {

    public function get_name() {
        
        return get_string('checkcohorts', 'block_coursesshowcase');
    }

    public function execute() {

        $xmldoc = new \DOMDocument();
        $xmldoc->load('/home/referentiel/DOKEOS_Etudiants_Inscriptions.xml');
        $xpathvar = new \Domxpath($xmldoc);
        $liststudents = $xpathvar->query('//Student');
        foreach ($liststudents as $student) {
           $this->studentline($student);
        }
    }

    private function studentline($student) {

        global $CFG, $DB;

        $studentuid = $student->getAttribute('StudentUID');
        echo "$studentuid<br>";
        $email = $student->getAttribute('StudentEmail');
        $universityyears = $student->childNodes;

        //~ echo "studentuid = $studentuid, email= $email\n";

        if ($studentuid && $email && $universityyears) {

            $user = $DB->get_record('user', array('username' => $studentuid, 'email' => $email));
            if ($user) {

                print_object($universityyears);
                foreach ($universityyears as $universityyear) {

                    if ($universityyear->nodeType !== 1 ) {

                        //~ echo "nodetype<br>";
                        continue;
                    }

                    // Si l'utilisateur est inscrit à l'université pendant l'année en cours, on traite son cas.
                    $year = $universityyear->getAttribute('AnneeUniv');
                    $longufrcode = $universityyear->getAttribute('CodeComposante');
                    echo "$longufrcode<br>";
                    echo "$year<br>";

                    if ($year == $CFG->thisyear) {

                        $xmlenrolments = $universityyear->childNodes;
                        $userenrolments = $DB->get_records('user_enrolments', array('userid' => $user->id));
                        $this->studentcohorts($user, $xmlenrolments, $userenrolments);

                        // Ajout aux cohortes d'UFR
                        //~ foreach ($xmlenrolments as $xmlenrolment) {
                                //~ if ($xmlenrolement->nodeType !== 1 ) {
                                //
                                        //~ continue;
                                //~ }
                                //~ $vetcode = $xmlenrolment->getAttribute('CodeEtape');
                                //~ $ufrcode = substr($vetcode, 0, 1);
                                //~ echo "$vetcode<br>";
                                $ufrcode = substr($longufrcode, 0, 1);
                                if ($ufrcode == '4') {

                                    $this->ufrcohort($user, 393);
                                } else if ($ufrcode == '5') {

                                    echo "UFR 5<br>";
                                    $this->ufrcohort($user, 394);
                                }
                        //~ }
                    }
                }
            }
        }
    }

    private function ufrcohort($user, $cohortid) {

        global $DB;
        $cohortmember = $DB->get_record('cohort_members', array('cohortid' => $cohortid, 'userid' => $user->id));

        if (!$cohortmember) {

            $member = new \stdClass();
            $member->cohortid = $cohortid;
            $member->userid = $user->id;
            $member->timeadded = time();
            $member->id = $DB->insert_record('cohort_members', $member);
            echo "Ajout de $user->username à la cohorte $cohortid<br>";
        }
    }

    private function studentcohorts($user, $xmlenrolments, $userenrolments) {

        global $CFG, $DB;
        $now = time();
        foreach ($xmlenrolments as $xmlenrolment) {

            if ($xmlenrolment->nodeType !== 1) {

                continue;
            }

            $vetcode = $xmlenrolment->getAttribute('CodeEtape');
            $cohortidnumber = "$CFG->yearprefix-$vetcode";
            $cohort = $DB->get_record('cohort', array('idnumber' => $cohortidnumber));
            if (!$cohort) {

                $cohort = new \stdClass();
                $cohort->contextid = 1;
                $cohort->name = $xmlenrolment->getAttribute('LibEtape');
                $cohort->idnumber = $cohortidnumber;
                $cohort->description = '';
                $cohort->descriptionformat = 1;
                $cohort->visible = 1;
                $cohort->component = 'block_coursesshowcase';
                $cohort->timecreated = $now;
                $cohort->timemodified = $now;
                $cohort->id = $DB->insert_record('cohort', $cohort);
            }

            $cohortmember = $DB->get_record('cohort_members', array('cohortid' => $cohort->id, 'userid' => $user->id));
            if (!$cohortmember) {

                $member = new \stdClass();
                $member->cohortid = $cohort->id;
                $member->userid = $user->id;
                $member->timeadded = $now;
                $member->id = $DB->insert_record('cohort_members', $member);
            }
            foreach ($userenrolments as $userenrolment) {

                $this->checkcoursegroups($user, $cohort, $userenrolment);
            }
        }
    }

    private function checkcoursegroups($user, $cohort, $userenrolment) {

        global $DB;
        $enrolmethod = $DB->get_record('enrol', array('id' => $userenrolment->enrolid));
        $group = $DB->get_record('groups', array('courseid' => $enrolmethod->courseid, 'idnumber' => $cohort->idnumber));
        if (!$group) {

            $group = new \stdClass();
            $group->name = $cohort->name;
            $group->idnumber = $cohort->idnumber;
            $group->courseid = $enrolmethod->courseid;
            $group->id = groups_create_group($group);
        }

        $ismember = groups_is_member($group->id, $user->id);

        if (!$ismember) {

                groups_add_member($group->id, $user->id);
        }
    }
}
