<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

declare(strict_types=1);

namespace local_redislock\lock;

/**
 * Default identity provider for Redis locks.
 *
 * @package   local_redislock
 * @copyright 2025 Cameron Ball <cameron@cameron1729.xyz>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class default_identity_provider implements identity_provider {
    /**
     * Build default metadata.
     *
     * @return array Associative array of hostname and process ID.
     */
    public function build(): array {
        return ['hostname' => gethostname() ?: 'UNKNOWN', 'processid' => (string)getmypid()];
    }
}
