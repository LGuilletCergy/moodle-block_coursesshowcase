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
 * Displays links for the current course.
 *
 * @package    block_coursesshowcase
 * @author     Brice Errandonea <brice.errandonea@u-cergy.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * File : block_coursesshowcase.php
 * Block class definition
 */

require_once($CFG->dirroot.'/blocks/coursesshowcase/lib.php');

use core_completion\progress;

class block_coursesshowcase extends block_base {

    public function init() {

        $this->title = get_string('shortcuts', 'block_coursesshowcase');
    }

    public function get_content() {

        global $CFG, $COURSE, $PAGE;

        if ($this->content !== null) {

            return $this->content;
        }

        $coursecontext = context_course::instance($COURSE->id);

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text = '';

        //~ $pagepath = $PAGE->url->get_path();
        //~ if (($pagepath == '/user/index.php')||($pagepath == '/report/exportlist/index.php')) {
            //~ block_coursesshowcase_checkstudents($coursecontext);
        //~ }

        if (!has_capability('moodle/course:update', $coursecontext)) {

            return $this->content;
        }

        if ($COURSE->id > 1) {

            $paramurl = "$CFG->wwwroot/course/edit.php?id=$COURSE->id";
            $this->content->text .= "<p style='text-size:5px!important'>".$this->button($paramurl,
                    'parameters', '#0065A4')."</p>";
            $detailsurl = "$CFG->wwwroot/local/coursesshowcase/coursedetails.php?id=$COURSE->id";
            $this->content->text .= $this->button($detailsurl, 'details', '#731472');
            $courseurl = "$CFG->wwwroot/course/view.php?id=$COURSE->id";
            $this->content->text .= $this->button($courseurl, 'content', '#B10034');
            $studentsurl = "$CFG->wwwroot/report/exportlist/index.php?id=$COURSE->id";
            $this->content->text .= $this->button($studentsurl, 'studentslist', '#009900');
            $studentsurl = "$CFG->wwwroot/local/coursesshowcase/wantedlist.php?id=$COURSE->id";
            $this->content->text .= $this->button($studentsurl, 'wantedlist', '#F78E1E');

            //Vert : #519f24
        }

        return $this->content;
    }

    public function specialization() {

    }

    public function has_config() {

        return false;
    }

    public function button($url, $identifier, $color) {
        
        $html = "<p><a href='$url'>";
        $html .= "<button class='btn btn-secondary' style='width:100%;background-color:$color;color:white'>";
        $html .= get_string($identifier, 'block_coursesshowcase');
        $html .= "</button>";
        $html .= "</a></p>";
        return $html;
    }

}
