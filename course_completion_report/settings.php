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

$section = 'reports';

if ($hassiteconfig OR has_capability('local/course_completion_report:view', $PAGE->context,$USER->id, false)) {

    $section = 'reports';

    $ADMIN->add($section, new admin_externalpage('course_completion_report',
            get_string('pluginname', 'local_course_completion_report'),
            new moodle_url('/local/course_completion_report/')
    ));
}
