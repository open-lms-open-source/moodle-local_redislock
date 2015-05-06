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

use core\lock\lock_config;

/**
 * Unit tests for \local_redislock\lock\redis_lock_factory.
 *
 * @package    local_redislock
 * @author     Sam Chaffee
 * @copyright  2015 Moodlerooms, Inc.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class local_redislock_redis_lock_factory_test extends \advanced_testcase {

    public function setUp() {
        global $CFG;

        $this->resetAfterTest();
        $CFG->local_redislock_redis_server = !empty($CFG->local_redislock_redis_server) ? $CFG->local_redislock_redis_server : 'tcp://127.0.0.1';
        $CFG->lock_factory = '\\local_redislock\\lock\\redis_lock_factory';
    }

    public function test_aquire_lock() {
        if (!$this->is_redis_available()) {
            $this->markTestSkipped('Redis server not available');
        }

        $redislockfactory = lock_config::get_lock_factory('core_cron');
        $lock1 = $redislockfactory->get_lock('test', 10);
        $this->assertNotEmpty($lock1);

        $lock2 = $redislockfactory->get_lock('test', 10);
        $this->assertEmpty($lock2);

        $this->assertTrue($lock1->release());

        $lock3 = $redislockfactory->get_lock('another_test', 2, 2);
        $this->assertNotEmpty($lock3);
        sleep(3);

        $lock4 = $redislockfactory->get_lock('another_test', 2);
        $this->assertNotEmpty($lock4);

        $this->assertTrue($lock4->release());
        $this->assertTrue($lock3->release());
    }

    public function test_lock_extendttl() {
        if (!$this->is_redis_available()) {
            $this->markTestSkipped('Redis server not available');
        }

        /** @var local_redislock\lock\redis_lock_factory $redislockfactory */
        $redislockfactory = lock_config::get_lock_factory('conduit_cron');
        $lock1 = $redislockfactory->get_lock('test', 10, 200);
        $this->assertNotEmpty($lock1);
        $this->assertTrue($lock1->extend(10000));

        $newttl = $redislockfactory->get_ttl($lock1);
        $this->assertGreaterThanOrEqual(9990, $newttl);

        $lock1->release();
    }

    public function test_lock_autorelease() {
        if (!$this->is_redis_available()) {
            $this->markTestSkipped('Redis server not available');
        }

        /** @var local_redislock\lock\redis_lock_factory $redislockfactory */
        $redislockfactory = lock_config::get_lock_factory('conduit_cron');
        $lock1 = $redislockfactory->get_lock('test', 10, 200);
        $this->assertNotEmpty($lock1);

        $lock2 = $redislockfactory->get_lock('another_test', 10, 200);
        $this->assertNotEmpty($lock2);

        // core\lock\lock has a __destruct method that throws a coding exception if the lock wasn't released.
        // The test fails when that happens. Simulate the auto-release being called by the shutdown manager.
        $redislockfactory->auto_release();
    }

    public function test_lock_timeout() {
        $redis = $this->getMockBuilder('Redis')
            ->setMethods(array('setnx'))
            ->disableOriginalConstructor()
            ->getMock();

        $redislockfactory = new \local_redislock\lock\redis_lock_factory('cron', $redis);


        $redis->expects($this->atLeastOnce())
            ->method('setnx')
            ->will($this->returnValue(false));

        $starttime = time();
        $timedoutlock = $redislockfactory->get_lock('block_conduit', 3);
        $endtime = time();

        $this->assertEmpty($timedoutlock);
        $this->assertGreaterThanOrEqual(3, $endtime - $starttime);
    }

    protected function is_redis_available() {
        return defined('LOCAL_REDISLOCK_REDIS_LOCK_TEST') && LOCAL_REDISLOCK_REDIS_LOCK_TEST;
    }
}