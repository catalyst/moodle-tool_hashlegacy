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
 * Reporting page for each factor vs auth type
 *
 * @package   tool_hashlegacy
 * @author    Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright 2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('hashreport');

$PAGE->set_title(get_string('hashreport', 'tool_hashlegacy'));
$PAGE->set_heading(get_string('hashreport', 'tool_hashlegacy'));

$form = new \tool_hashlegacy\form\hash_report();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/admin/search.php'));
} else if ($fromform = $form->get_data()) {
    // No actions yet;
}

echo $OUTPUT->header();
echo $form->display();
echo $OUTPUT->footer();
