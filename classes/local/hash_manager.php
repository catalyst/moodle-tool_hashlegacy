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

    const ALGO_BCRYPT10 = 'bcrypt10';
    const ALGO_BCRYPT4 = 'bcrypt4';
    const ALGO_MD5 = 'md5';
    const ALGO_SHA256 = 'sha256';
    const ALGO_SHA256FAST = 'sha256fast';
    const ALGO_SHA512 = 'sha512';
    const ALGO_SHA512FAST = 'sha512fast';

    const ALGO_BCRYPT10_MATCH = '_2y_10_%';
    const ALGO_BCRYPT4_MATCH = '_2y_04_%';
    const ALGO_MD5_MATCH = '________________________________';
    const ALGO_SHA256_MATCH = '_5_rounds=5000_%';
    const ALGO_SHA256FAST_MATCH = '_5_rounds=1000_%';
    const ALGO_SHA512_MATCH = '_6_rounds=5000_%';
    const ALGO_SHA512FAST_MATCH = '_6_rounds=1000_%';

    public static function force_pw_change($algo) {
        global $SESSION;
        // Generate user list with that algorithm.
        $users = self::generate_user_list($algo);

        // Store in session then redirect to the bulk action.
        $SESSION->bulk_users = $users;
        $bulkurl = new \moodle_url('/admin/user/user_bulk.php',
            array ('sesskey' => sesskey()));

        redirect($bulkurl);
    }

    public static function generate_user_list($algo) {
        global $DB;
        switch ($algo) {
            case self::ALGO_BCRYPT10:
                $match = self::ALGO_BCRYPT10_MATCH;
                break;

            case self::ALGO_BCRYPT4:
                $match = self::ALGO_BCRYPT4_MATCH;
                break;

            case self::ALGO_MD5:
                $match = self::ALGO_MD5_MATCH;
                break;

            case self::ALGO_SHA256:
                $match = self::ALGO_SHA256_MATCH;
                break;

            case self::ALGO_SHA256FAST:
                $match = self::ALGO_SHA256FAST_MATCH;
                break;

            case self::ALGO_SHA512:
                $match = self::ALGO_SHA512_MATCH;
                break;

            case self::ALGO_SHA512FAST:
                $match = self::ALGO_SHA512FAST_MATCH;
                break;
        }

        $sql = "SELECT id
                  FROM {user}
                 WHERE password like ?";

        $users = $DB->get_records_sql($sql, array($match));

        return array_map(function($userobject) {
            return $userobject->id;
        }, $users);
    }
}
