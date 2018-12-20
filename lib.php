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
 * @package    block_coursesshowcase
 * @author     Brice Errandonea <brice.errandonea@u-cergy.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * File : lib.php
 * Functions library.
 */

defined('MOODLE_INTERNAL') || die();

//~ function block_coursesshowcase_checkstudents($coursecontext) {
    //~ global $DB;
    //~ $students = $DB->get_records('role_assignments', array('roleid' => 5, 'contextid' => $coursecontext->id)); // mod/quiz:attempt

    //~ foreach ($students as $student) {
        //~ $studentuser = $DB->get_record('user', array('id' => $student->userid));
        //~ $studentcohorts = $DB->get_records('cohort_members', array('userid' => $student->userid));
        //~ foreach ($studentcohorts as $studentcohort) {
			
		//~ }

        //~ $studentxmlcohorts = block_coursesshowcase_xmlcohorts($studentuser->username, $studentuser->email);
        //~ foreach ($studentxmlcohorts as $studentxmlcohort) {
            //~ $studentlocalcohort = block_coursesshowcase_getlocalcohort($studentxmlcohort);
            //~ block_coursesshowcase_checkcohortmember($studentuser->id, $studentlocalcohort->id);
            //~ $group = block_coursesshowcase_getcohortgroup($studentlocalcohort);
            //~ block_coursesshowcase_checkgroupmember($studentuser->id, $group->id);
        //~ }
    //~ }
//~ }

//~ /**
 //~ * Fetch, from a remote Moodle, the training cohorts this student is part of.
 //~ */
//~ function block_coursesshowcase_xmlcohorts($username, $email) {
    //~ $xmlcohorts = array();
    //~ $xmldoc = new \DOMDocument();
    //~ $xmldoc->load('/home/referentiel/DOKEOS_Etudiants_Inscriptions.xml');
    //~ $xpathvar = new \Domxpath($xmldoc);
    //~ $liststudents = $xpathvar->query('//Student');
    //~ foreach ($liststudents as $student) {
       //~ $this->studentline($student);
    //~ }
    //~ return $xmlcohorts;
//~ }

//~ /**
 //~ * Get the local cohort corresponding to the remote one. Creates it if it does not exist yet.
 //~ */
//~ function block_coursesshowcase_getlocalcohort($xmlcohort) {
	//~ global $DB;
	//~ $localcohort = $DB->get_record('cohort', array('component' => 'block_coursesshowcase', 'idnumber' => $xmlcohort->idnumber));
    //~ if (!$localcohort) {
        //~ $localcohort = new stdClass();
        //~ $localcohort->contextid = 1;
        //~ $localcohort->name = $xmlcohort->name;
        //~ $localcohort->idnumber = $xmlcohort->idnumber;
        //~ $localcohort->component = 'block_coursesshowcase';
        //~ $localcohort->timecreated = time();
        //~ $localcohort->timemodified = time();
        //~ $localcohort->id = $DB->insert_record('cohort', $localcohort);
    //~ }
    //~ return $localcohort;
//~ }

//~ /**
 //~ * If the user is not yet a member of the given cohort, this function enrols him in this cohort.
 //~ */
//~ function block_coursesshowcase_checkcohortmember($userid, $cohortid) {
    //~ global $DB;
    //~ $member = $DB->get_record('cohort_members', array('cohortid' => $cohortid, 'userid' => $userid));
    //~ if (!$member) {
		//~ $member = new stdClass();
		//~ $member->cohortid = $cohortid;
		//~ $member->userid = $userid;
		//~ $member->timeadded = time();
		//~ $member->id = $DB->insert_record('cohort_members', $member);
	//~ }
//~ }

//~ /**
 //~ * Get the course group corresponding to this cohort. Creates it if it does not exist yet.
 //~ */
//~ function block_coursesshowcase_getcohortgroup($cohort) {
    //~ global $COURSE, $DB;
    //~ $group = $DB->get_record('groups', array('courseid' => $COURSE->id, 'idnumber' => $cohort->idnumber));
    //~ if (!$group) {
        //~ $group = new stdClass();
        //~ $group->courseid = $COURSE->id;
        //~ $group->idnumber = $cohort->idnumber;
        //~ $group->name = $cohort->name;
        //~ $group->timecreated = time();
        //~ $group->timemodified = time();
        //~ $group->id = $DB->insert_record('groups', $group);
	//~ }
	//~ return $group;
//~ }

//~ /**
 //~ * If the user is not yet a member of the given cohort, this function enrols him in this cohort.
 //~ */
//~ function block_coursesshowcase_checkgroupmember($userid, $groupid) {
	//~ global $COURSE;
    //~ $member = $DB->get_record('groups_members', array('groupid' => $groupid, 'userid' => $userid));
    //~ if (!$member) {
		//~ $member = new stdClass();
		//~ $member->groupid = $groupid;
		//~ $member->userid = $userid;
		//~ $member->timeadded = time();
		//~ $member->component = 'block_coursesshowcase';
		//~ $member->id = $DB->insert_record('groups_members', array('groupid' => $groupid, 'userid' => $userid));
	//~ }
//~ }

