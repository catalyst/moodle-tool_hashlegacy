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

namespace tool_hashlegacy;

class hash_manager {
    public function force_pw_change($algo) {
        $users = self::generate_user_list($algo);
        var_dump($users);
    }

    public static function generate_user_list($algo) {
        global $DB;
        switch ($algo) {
            case 'bcrypt10':
                $match = '_2y_10_%';
                break;

            case 'bcrypt4':
                $match = '_2y_04_%';
                break;

            case 'md5':
                $match = '________________________________';
                break;
        }

        $sql = "SELECT id
                  FROM {user}
                 WHERE password like ?";

        return $DB->get_records_sql($sql, array($match));
    }
}
