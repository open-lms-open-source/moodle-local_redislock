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
 * Interface for the metadata stored inside Redis locks.
 *
 * Implementations must return the same associative array for the lifetime
 * of the current process, otherwise releases will fail because the stored value
 * will not match. Keys and values in the returned associative array must be strings.
 *
 * @package   local_redislock
 * @copyright 2025 Cameron Ball <cameron@cameron1729.xyz>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
interface identity_provider {
    /**
     * Produce the identifying metadata for the current process.
     *
     * @return array Associative array that will be encoded into the lock value.
     *               Keys and values must be strings.
     */
    public function build(): array;
}
