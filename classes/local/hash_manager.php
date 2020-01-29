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
 *  Hash Manager Class
 *
 * @package     tool_hashlegacy
 * @author      Peter Burnett <peterburnett@catalyst-au.net>
 * @copyright   Catalyst IT
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_hashlegacy\local;
defined('MOODLE_INTERNAL') || die;

class hash_manager {

    const ALGORITHMS = array(
        'blowfish10' => array (
            'displayname' => 'BLOWFISH cost 10',
            'name' => 'blowfish10',
            'match' => '_2y_10_%',
            'prefix' => '_2y_',
            'suffix' => '_%'
        ),
        'blowfish04' => array (
            'displayname' => 'BLOWFISH cost 04',
            'name' => 'blowfish04',
            'match' => '_2y_04_%',
            'prefix' => '_2y_',
            'suffix' => '_%'
        ),
        'md5' => array (
            'displayname' => 'MD5',
            'name' => 'md5',
            'match' => '________________________________',
            'prefix' => '',
            'suffix' => ''
        ),
        'sha256' => array (
            'displayname' => 'SHA256',
            'name' => 'sha256',
            'match' => '_5_rounds=5000_%',
            'prefix' => '_5_rounds=',
            'suffix' => '_%'
        ),
        'sha256fast' => array (
            'displayname' => 'SHA256 1000 rounds',
            'name' => 'sha256fast',
            'match' => '_5_rounds=1000_%',
            'prefix' => '_5_rounds=',
            'suffix' => '_%'
        ),
        'sha512' => array (
            'displayname' => 'SHA512',
            'name' => 'sha512',
            'match' => '_6_rounds=5000_%',
            'prefix' => '_6_rounds=',
            'suffix' => '_%'
        ),
        'sha512fast' => array (
            'displayname' => 'SHA512 1000 rounds',
            'name' => 'sha512fast',
            'match' => '_6_rounds=1000_%',
            'prefix' => '_6_rounds=',
            'suffix' => '_%'
        ),
        'restored' => array (
            'displayname' => 'Restored Hash',
            'name' => 'restored',
            'match' => 'restore%',
            'prefix' => '',
            'suffix' => ''
        ),
        'notcached' => array (
            'displayname' => 'Not Cached',
            'name' => 'notcached',
            'match' => 'not cache%',
            'prefix' => '',
            'suffix' => '',
        ),
        'empty' => array (
            'displayname' => 'Empty Hash',
            'name' => 'empty',
            'match' => '',
            'prefix' => '',
            'suffix' => ''
        )
    );

    public static function force_pw_change($algo) {
        global $SESSION;
        // Generate user list with that algorithm.
        $users = self::generate_user_list($algo['match']);

        // Store in session then redirect to the bulk action.
        $SESSION->bulk_users = $users;
        $bulkurl = new \moodle_url('/admin/user/user_bulk.php',
            array ('sesskey' => sesskey()));

        redirect($bulkurl);
    }

    public static function generate_user_list($algomatch) {
        global $DB;

        $sql = "SELECT id
                  FROM {user}
                 WHERE password like ?";

        $users = $DB->get_records_sql($sql, array($algomatch));

        return array_map(function($userobject) {
            return $userobject->id;
        }, $users);
    }
}
