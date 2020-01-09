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
$reset = optional_param('reset', '', PARAM_TEXT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

if (!empty($reset) && $confirm && confirm_sesskey()) {
    \tool_hashlegacy\hash_manager::force_pw_change($reset);
}

$PAGE->set_title(get_string('hashreport', 'tool_hashlegacy'));
$PAGE->set_heading(get_string('hashreport', 'tool_hashlegacy'));

echo $OUTPUT->header();
if (!empty($reset) && !$confirm) {
    $proceedurl = new moodle_url($PAGE->url, array('reset' => $reset, 'confirm' => 1, 'sesskey' => sesskey()));
    echo $OUTPUT->confirm(get_string('confirmreset', 'tool_hashlegacy', $reset), $proceedurl, $PAGE->url);
} else {
    echo generate_table();
}
echo $OUTPUT->footer();


function generate_table() {
    global $DB;
    $table = new html_table();
    $table->head = array (
        get_string('tablealgorithm', 'tool_hashlegacy'),
        get_string('count', 'tag'),
        get_string('action')
    );

    $sql = "SELECT count(*) cnt,
              CASE
                WHEN password like :bc10_match THEN :bc10
                WHEN password like :bc4_match THEN :bc4
                WHEN password like :md5_match  THEN :md5
                WHEN password like 'restore%'   THEN password
                WHEN password like 'not cache%' THEN password
                ELSE password
               END AS algo,
                      max(timemodified) lastmod,
                      to_timestamp(max(timemodified)) lastdate
              FROM {user}
          GROUP BY algo
          ORDER BY cnt DESC";
    $hashtypes = $DB->get_records_sql($sql, array (
        'bc10_match' => \tool_hashlegacy\hash_manager::ALGO_BCRYPT10_MATCH,
        'bc10' => \tool_hashlegacy\hash_manager::ALGO_BCRYPT10,
        'bc4_match' => \tool_hashlegacy\hash_manager::ALGO_BCRYPT4_MATCH,
        'bc4' => \tool_hashlegacy\hash_manager::ALGO_BCRYPT4,
        'md5_match' => \tool_hashlegacy\hash_manager::ALGO_MD5_MATCH,
        'md5' => \tool_hashlegacy\hash_manager::ALGO_MD5,
    ));

    foreach ($hashtypes as $type) {
        $actionurl = new moodle_url('/admin/tool/hashlegacy/hash_report.php', array('reset' => $type->algo));
        $link = html_writer::link($actionurl, get_string('reset'));
        $row = array(
            $type->cnt,
            $type->algo,
            $link,
        );
        $table->data[] = $row;
    }

    return html_writer::table($table);
}
