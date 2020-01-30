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
 * Page defining the clear_hashes bulk action.
 *
 * @package   tool_hashlegacy
 * @author    Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright 2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
admin_externalpage_setup('userbulk');

$return = new moodle_url('/admin/user/user_bulk.php');

if (empty($SESSION->bulk_users)) {
    redirect($return);
}

echo $OUTPUT->header();

if ($confirm and confirm_sesskey()) {
    $users = $SESSION->bulk_users;

    foreach ($users as $user) {
        $DB->set_field('user', 'password', '', array('id' => (int) $user));
    }

    // Output continue page.
    echo $OUTPUT->box_start('generalbox', 'notice');
    echo $OUTPUT->notification(get_string('changessaved'), 'notifysuccess');
    $continue = new single_button(new moodle_url($return), get_string('continue'), 'post');
    echo $OUTPUT->render($continue);
    echo $OUTPUT->box_end();

} else {
    echo $OUTPUT->heading(get_string('confirmation', 'admin'));
    $formcontinue = new single_button(new moodle_url('clear_hashes.php', array('confirm' => 1)), get_string('yes'));
    $formcancel = new single_button(new moodle_url('/admin/user/user_bulk.php'), get_string('no'), 'get');
    echo $OUTPUT->confirm(get_string('deletehashcheck', 'tool_hashlegacy'), $formcontinue, $formcancel);
}
echo $OUTPUT->footer();
