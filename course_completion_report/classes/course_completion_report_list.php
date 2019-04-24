<?php
// This file is part of eMailTest plugin for Moodle - http://moodle.org/
//
// eMailTest is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// eMailTest is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with eMailTest.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Sample plugin
 *
 * @package    local_course_completion_report
 * @copyright  SB Dev
 * @author     Scott
 */

defined('MOODLE_INTERNAL') || die;
$pluginname = 'course_completion_report';
require_once($CFG->libdir.'/formslib.php');
$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/local/'.$pluginname.'/js/custom.js'));

class course_completion_report_list extends moodleform {


    public function definition() {
        global $USER, $CFG,$DB;



    }

    public function reset() {
        $this->_form->updateSubmission(null, null);
    }


}
use core_completion\progress;

class home_page extends moodleform {


    public function definition() {

        global $DB;
        $html   = "";
        $mform  = $this->_form;
        $dropdown = array("" => "--Select--");

        //$mform->addElement('html','<h6>User Course Report</h6><hr>');


        $users = $DB->get_records("user",array());

        foreach ($users as $user)
        {
            $dropdown[$user->id] = fullname($user);
        }

        $mform->addElement('select', 'user_list', "Select User: ", $dropdown, 'width:300px" id="user_list"');

        $user_id = optional_param('id', '', PARAM_INT);

        if(!empty($user_id)) {
            $mform->setDefault("user_list",$user_id);

            $mform->addElement('html', '<h5>Users Enrolled Courses</h5><hr>');

            // Get the courses that the user is enrolled in (only active).
            $courses = enrol_get_users_courses($user_id, true);

            $table = new html_table();
            $table->width = '70%';


            $table->head = array("No","Course Name", "Completion status", "Completed Date");
            $table->align = array('left', 'left', 'left', 'left');
            $table->size = array('10%', '50%', "20%", "20%");

            $count = 0;
            foreach ($courses as $course) {
                $completion = new \completion_completion(array("userid"=>$user_id,"course" =>$course->id));

                $percentage = progress::get_course_progress_percentage($course);

                $courseLink = (new moodle_url('/course/view.php', array('id' => $course->id)));
                $URL        = "<a href='$courseLink'>$course->fullname</a>";

                if($percentage >=100 ) $com = "Completed";
                else                   $com = "Not Completed";
                $table->data[] = new html_table_row(array(++$count, $URL,$com,!empty($completion->timecompleted) ? date("d-M-Y",$completion->timecompleted) : "--"));
            }
            $html .= html_writer::table($table);
            $html .= "<hr></br>";


            $mform->addElement('html', $html, 'local_course_completion_report');
        }

    }


}
