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
 *  Legacy Hash Checker lang strings.
 *
 * @package    tool_hashlegacy
 * @copyright  2020 Peter Burnett <peterburnett@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Legacy password hash tools';
$string['hashreport'] = 'Legacy password hash report';
$string['tablealgorithm'] = 'Algorithm';
$string['tableforcechange'] = 'Bulk action';
$string['recentlogin'] = 'Most recent login';
$string['oldestlogin'] = 'Oldest login';
$string['clearhashes'] = 'Delete Password Hashes';
$string['deletehashcheck'] = 'Are you sure you want to delete stored password hashes for the selected users?';

// Check API Strings.
$string['hashlegacycheckname'] = 'Legacy password hashes';
$string['hashlegacychecklink'] = 'Legacy hash report';
$string['hashlegacycheck_details'] = 'Users whose hashes are old can potentially be cracked in the case of a database breach or dump.';
$string['securitycheck_problem'] = 'More than {$a}% of stored hashes are old. These can be manually reset, or the users messaged, through the plugin report.';
$string['securitycheck_ok'] = 'Less than {$a}% of users are on an old hash.';

// Privacy null provider.
$string['privacy:metadata'] = 'The Legacy password hash tool does not store any user data.';
