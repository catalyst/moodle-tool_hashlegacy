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
 * Page showing information on each algorithm in the database
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

if (!empty($reset)) {
    $algorithmarray = \tool_hashlegacy\local\hash_manager::ALGORITHMS[$reset];
    \tool_hashlegacy\local\hash_manager::force_pw_change($algorithmarray);
}

$PAGE->set_title(get_string('hashreport', 'tool_hashlegacy'));
$PAGE->set_heading(get_string('hashreport', 'tool_hashlegacy'));

echo $OUTPUT->header();
echo generate_table();
echo $OUTPUT->footer();

function generate_table() {
    global $DB;
    $table = new html_table();
    $table->attributes['class'] = 'generaltable table table-bordered';
    $table->head = array (
        get_string('tablealgorithm', 'tool_hashlegacy'),
        get_string('count', 'tag'),
        get_string('oldestlogin', 'tool_hashlegacy'),
        get_string('recentlogin', 'tool_hashlegacy'),
        get_string('action')
    );

    // Construct switch query from algorithm params.
    $startfrag = "SELECT CASE ";
    $select = '';
    $params = array();
    foreach (\tool_hashlegacy\local\hash_manager::ALGORITHMS as $algo) {
        $match = $algo['match'];
        $name = $algo['name'];
        $matchname = $name . '_match';

        // Check for empty special case.
        if ($algo['name'] === 'empty') {
            $select .= "WHEN password='' THEN :{$name} ";
            $params = array_merge($params, array($name => $name));
            continue;
        }

        $select .= "WHEN password like :{$matchname} THEN :{$name} ";
        $params = array_merge($params, array($matchname => $match, $name => $name));
    }
    $endfrag = "ELSE password
                END AS algo,
                       count(*) cnt,
                       max(lastlogin) lastdate,
                       min(NULLIF(lastlogin, 0)) firstdate
               FROM {user}
           GROUP BY algo
           ORDER BY lastdate DESC";
    $sql = $startfrag . $select . $endfrag;
    $hashtypes = $DB->get_records_sql($sql, $params);

    foreach ($hashtypes as $type) {
        $actionurl = new moodle_url('/admin/tool/hashlegacy/index.php', array('reset' => $type->algo));
        $link = html_writer::link($actionurl, get_string('tableforcechange', 'tool_hashlegacy'));
        $displayname = \tool_hashlegacy\local\hash_manager::ALGORITHMS[$type->algo]['displayname'];
        $format = get_string('strftimedatetimeshort', 'langconfig');
        $row = array(
            $displayname,
            $type->cnt,
            userdate($type->firstdate, $format),
            userdate($type->lastdate, $format),
            $link,
        );
        $table->data[] = $row;
    }

    return html_writer::table($table);
}
