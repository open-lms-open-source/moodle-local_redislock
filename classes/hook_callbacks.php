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

namespace local_redislock;

use core\hook\di_configuration;
use local_redislock\lock\default_identity_provider;
use local_redislock\lock\identity_provider;

use function DI\autowire;

/**
 * Hook callbacks for the Redis lock plugin.
 *
 * @package   local_redislock
 * @copyright 2025 Cameron Ball <cameron@cameron1729.xyz>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Register DI definitions.
     *
     * @param di_configuration $hook
     */
    public static function di_configuration(di_configuration $hook): void {
        $hook->add_definition(identity_provider::class, autowire(default_identity_provider::class));
    }
}
