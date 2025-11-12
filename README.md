# Redis lock Local plugin
Provides a Moodle lock factory class for locking with Redis.

This plugin was contributed by the Open LMS Product Development team. 
Open LMS is an education technology company dedicated to bringing excellent online teaching to institutions across the globe.
We serve colleges and universities, schools and organizations by supporting the software that educators use to manage and deliver instructional content to learners in virtual classrooms.

## Requirements
* Moodle 2.9 or greater
* Redis
* PHP Redis extension

## Installation
Extract the contents of the plugin into _/wwwroot/local_ then visit `admin/upgrade.php` or use the CLI script to upgrade your site.

Set:
* `$CFG->local_redislock_redis_server` with your Redis server's connection string.
  - It can be the `hostname` or IP address of the Redis server.
  - It can also be `hostname:port` if you want to use other port different than `6379` (Default)
* `$CFG->lock_factory` to `'\\local_redislock\\lock\\redis_lock_factory'` in your config file.
* `$CFG->local_redislock_auth` with your Redis server's password string.

## Flags
* Logging is only available in the CLI environment with debugging enabled on `DEBUG_NORMAL` level at least.
Use the boolean flag `$CFG->local_redislock_logging` to control whether verbose
logging should be emitted. If not set, logging is automatically-enabled.
* Use the boolean flag `$CFG->local_redislock_disable_shared_connection` to force creation
of the redis connection for each factory instance.

## Customising the lock identity payload
The value stored alongside each Redis lock is produced by an `identity_provider` service. By default it records the hostname and the current PHP process ID, but you can override the DI binding to record whatever metadata you need (for example an AWS ARN, container identifier).

Registering an override inside another plugin via **local/my_plugin/classes/hook_callbacks.php**:

```php
namespace local_my_plugin

class hook_callbacks {
    public static function di_configuration(\core\hook\di_configuration $hook): void {
        $hook->add_definition(
            \local_redislock\lock\identity_provider::class,
            \DI\autowire(\local_yourplugin\lock\arn_identity_provider::class),
        );
    }
}
```

Because local_redislock registers its DI callback with priority 999, any plugin using the default priority (100) will run afterwards and automatically overwrite the binding without extra effort.

### Override via config.php
If it's ever needed to restore the default identity provider or change priorities without touching code, hook priorities can be adjusted via **config.php**:

```php
$CFG->hooks_callback_overrides = [
    \core\hook\di_configuration::class => [
        '\local_redislock\hook_callbacks::di_configuration' => [
            'priority' => 0, // Ensure redislock runs last, restoring the default identity provider.
        ],
    ],
];
```

Setting `'disabled' => true` for another plugin's callback will stop it from overriding the provider entirely.

## License
Copyright (c) 2021 Open LMS (https://www.openlms.net)

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.
