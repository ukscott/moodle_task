<?php
// This file is part of MailTest for Moodle - http://moodle.org/
//
// MailTest is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// MailTest is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with MailTest.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Sample plugin
 *
 * @package    local_course_completion_report
 * @copyright  SB Dev
 * @author     Scott
 */

$pluginname = 'course_completion_report';

// Include config.php.
require_once(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/local/'.$pluginname.'/locallib.php');  // Include our function library.

require_once(dirname(__FILE__).'/classes/'.$pluginname.'_list.php');  // Include form.


$homeurl    = new moodle_url('/local/'.$pluginname.'/index.php');


function home_page(){
    global $USER, $CFG,$DB,$pluginname,$homeurl;


    $form = new home_page(null, array());

    if ($form->is_cancelled()) {
        redirect($homeurl);
    }
    $form->get_data();
    $form->display();



}


function show_user_details(){
    global $homeurl;

    $form = new course_completion_report_list(null, array());
    if ($form->is_cancelled()) {
        redirect($homeurl);
    }
    $data = $form->get_data();
    $form->display();


}


function delete_data(){

    global $DB, $OUTPUT,$homeurl,$table;


    $data['id']  = get_request('id');

    $result = $DB->delete_records($table, $data);

    if(!empty($result)){

        echo $OUTPUT->notification("Data has been deleted successfully!!...",'notifysuccess');
        redirect($homeurl);
    }
    else{
        echo $OUTPUT->notification("Sorry!!.. unable to delete the data");
    }
}
