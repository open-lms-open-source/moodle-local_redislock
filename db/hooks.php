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

/**
 * Hook callbacks.
 *
 * @package   local_redislock
 * @copyright Cameron Ball <cameron@cameron1729.xyz>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

declare(strict_types=1);

defined('MOODLE_INTERNAL') || die();

$callbacks = [
    [
        // The hostname resolver is retrieved via DI, and we provide the default
        // implementation by listening to the DI configuration hook.
        'hook' => \core\hook\di_configuration::class,
        'callback' => [\local_redislock\hook_callbacks::class, 'di_configuration'],
        // Higher priority means "runs sooner". Setting 999 here means pretty much
        // any plugin that listens to this hook will run after us, allowing them
        // to switch in their own hostname resolver.
        'priority' => 999,
    ],
];
