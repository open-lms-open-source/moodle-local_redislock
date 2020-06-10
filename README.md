#About
Provides a Moodle lock factory class for locking with Redis. This plugin was contributed by the Blackboard Open LMS Product Development team.  Blackboard is an education technology company dedicated to bringing excellent online teaching to institutions across the globe.  We serve colleges and universities, schools and organizations by supporting the software that educators use to manage and deliver instructional content to learners in virtual classrooms.

#Requirements
* Moodle 2.9 or greater
* Redis
* PHP Redis extension

#Installation
Clone the repository or download and extract the code into the local directory of your Moodle install (e.g. $CFG->wwwroot/local/redislock) and run the site's upgrade script. Set $CFG->local_redislock_redis_server with your Redis server's connection string. Set $CFG->lock_factory to '\\\\local_redislock\\\\lock\\\\redis_lock_factory' in your config file.

Use the boolean flag `$CFG->local_redislock_logging` to control whether verbose
logging should be emitted. If not set, logging is automatically-enabled when running
in the CLI environment with debugging enabled on `DEBUG_NORMAL` level at least.
