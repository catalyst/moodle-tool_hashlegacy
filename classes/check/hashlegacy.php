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
 * Check class for security check API
 *
 * @package   tool_hashlegacy
 * @author    Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright 2020 Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace tool_hashlegacy\check;

use core\check\check;
use core\check\result;

defined('MOODLE_INTERNAL') || die();

class hashlegacy extends check {

    const WARNING_THRESHOLD = 0.2;
    const CRITICAL_THRESHOLD = 0.5;

    /**
     * Constructor
     */
    public function __construct() {
        $this->id = 'hashlegacy';
        $this->name = get_string('hashlegacycheckname', 'tool_hashlegacy');
        $this->actionlink = new \action_link(
            new \moodle_url('/admin/tool/hashlegacy/index.php'),
            get_string('hashlegacychecklink', 'tool_hashlegacy')
        );
    }

    public function get_result() : result {
        global $CFG, $DB;

        $details = get_string('hashlegacycheck_details', 'tool_hashlegacy');

        // Get currently set algorithm.
        if (!empty($CFG->hashalgorithm)) {
            $algo = $CFG->hashalgorithm;
        } else {
            // For now, hardcode this to Bcrypt10.
            $algo = 'blowfish10';
        }

        // Count all users not on most current hash.
        $sql = "SELECT COUNT(*)
                  FROM {user}
                 WHERE password NOT LIKE :algomatch";

        $oldhashcount = $DB->count_records_sql($sql,
            array('algomatch' => \tool_hashlegacy\local\hash_manager::ALGORITHMS[$algo]['match']));

        // Now get a count of all users to figure out how many aren't on latest.
        $allcount = $DB->count_records('user', array());

        // 20% warning, 50% critical.
        if ($oldhashcount / $allcount > self::CRITICAL_THRESHOLD) {
            $status = result::CRITICAL;
            $summary = get_string('securitycheck_problem', 'tool_hashlegacy', '50');

        } else if ($oldhashcount / $allcount > self::WARNING_THRESHOLD) {
            $status = result::WARNING;
            $summary = get_string('securitycheck_problem', 'tool_hashlegacy', '20');
        } else {
            $status = result::OK;
            $summary = get_string('securitycheck_ok', 'tool_hashlegacy', '20');
        }

        return new result($status, $summary, $details);
    }
}
