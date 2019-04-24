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

defined('MOODLE_INTERNAL') || die;

function get_userInfo($data){
    global $DB, $USER;;
    return $DB->get_record('user',$data);


}
function uploadFile($filename,$report_type,$id){


    //echo "</pre>";
    global $CFG;
    $url = $CFG->dataroot.'/filedir/upload';
    if(isset($_FILES[$filename])){


        $errors= array();
        $file_name = $_FILES[$filename]['name'];
        //$file_size = $_FILES[$filename]['size'];
        $file_tmp  = $_FILES[$filename]['tmp_name'];
        //$file_type = $_FILES[$filename]['type'];
        $file_ext=strtolower(end(explode('.',$_FILES[$filename]['name'])));
        if (!file_exists("$url/$report_type/$id")) {
            mkdir("$url/$report_type/$id", 0777, true);
        }

        move_uploaded_file($file_tmp,"$url/$report_type/$id/$filename".".".$file_ext);

        return "$report_type/$id/$filename".".".$file_ext;

    }
    return null;

}

function createDropdown($option){
    $option[""] = "--Select--";
    ksort($option);

    return $option;
}

function get_dropdown_data($report_id,$dropdown_name=null){
    global $DB, $USER;
    $query     = array('field_status'=>1);
    $dropdown  = array();
    $tableName = 'standing_table';

    if(!empty($report_id))   $query['report_id']       = $report_id;
    if(!empty($dropdown_name)) $query['dropdown_name'] = $dropdown_name;

    $result = $DB->get_records($tableName, $query);

    if(!empty($result)){
        foreach ($result as $data){

            $dropdown[$data->dropdown_name][$data->id] = $data->field_value;
        }
    }

    return $dropdown;


}

function is_admin(){
    global $CFG, $USER;

    $admins = explode(",",$CFG->siteadmins);

    if(in_array($USER->id,$admins)) return 1;

    return 0;

}

function is_manager(){
    global $DB, $USER;
    $context = get_context_instance (CONTEXT_SYSTEM);
    $roles = get_user_roles($context, $USER->id, false);

    if(!empty($roles)){
        foreach ($roles as $role){
            if($role->shortname == 'manager') return 1;
        }
    }

    return 0;

}

function is_senior_manager($user_id=null){
    global $DB, $USER;
    $context = get_context_instance (CONTEXT_SYSTEM);
    $roles = get_user_roles($context, $user_id ? $user_id : $USER->id, false);

    //echo "<pre>";
    //print_r($roles);
    //echo "</pre>";

    if(!empty($roles)){
        foreach ($roles as $role){
            if($role->shortname == 'seniormanager') return 1;
        }
    }

    return 0;

}

function is_complieance($user_id=null){
    global $DB, $USER;
    $context = get_context_instance (CONTEXT_SYSTEM);
    $roles = get_user_roles($context, $user_id ? $user_id : $USER->id, false);

    if(!empty($roles)){
        foreach ($roles as $role){
            if($role->shortname == 'compliancemanager') return 1;
        }
    }

    return 0;

}



function get_manager_list(){
    global $DB,$USER;

    $arr =  array();

    $sql = "SELECT u.id ,CONCAT(u.firstname, ' ', u.lastname) AS name
              FROM {user} u LEFT JOIN {role_assignments} ra ON (u.id = ra.userid)
             WHERE ra.contextid=1 and ra.roleid IN(1)  AND ra.roleid NOT IN(9,10) ORDER BY u.id";

    $data = $DB->get_records_sql($sql);

    if(!empty($data)){
        foreach ($data as $value){
            if(!is_senior_manager($value->id)  and !is_complieance($value->id))
            $arr[$value->id] = $value->name;

        }
    }

    return $arr;
}

function get_user_role(){
    global $DB, $USER;
    $context = get_context_instance (CONTEXT_SYSTEM);
    $roles = get_user_roles($context, $USER->id, false);

    $role = key($roles);

    return $roleid = $roles[$role]->roleid;


}

function get_request($fieldName){

    if(isset($_REQUEST[$fieldName])){
        return trim($_REQUEST[$fieldName]);
    }

    return null;
}

function save_data($data,$table){
    global $DB, $USER;

    if(empty($data->id)) {
        $data->user_id    = $USER->id;
        $data->created_at = time();
        return $DB->insert_record($table, $data, $returnid = true, $bulk = false);
    }
    else{
        $data->manager_id  = $USER->id;
        $data->updated_at  = time();
        $DB->update_record($table, $data);
        return $data->id;
    }
}

function get_data($data,$table){
    global $DB, $USER;;
    return $DB->get_record($table,$data);


}


function update_data($data,$table){
    global $DB, $USER;
    return $DB->update_record($table,$data);
}

