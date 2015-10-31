<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 14:23
 */

namespace YevgenGrytsay\PidHelper\tests\ProcessHelper;


use Symfony\Component\Console\Helper\ProcessHelper;

class ProcessIsRunningTest extends \PHPUnit_Framework_TestCase
{
    private $pid;
    private $proc;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
//    protected function setUp()
//    {
//        $this->runInfiniteProcessWithPidFile();
//    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
//    protected function tearDown()
//    {
//        proc_terminate($this->proc, SIGKILL);
//        proc_close($this->proc);
//        sleep(1);
//        $success = posix_kill($this->pid, 0);
//        $this->assertFalse($success);
//    }

    public function testExists()
    {
        $helper = new \YevgenGrytsay\PidHelper\PosixProcessControl();
        $exists = $helper->exists(getmypid());

        $this->assertTrue($exists);
    }

//    protected function runInfiniteProcessWithPidFile()
//    {
//        $pidFile = sys_get_temp_dir().'/worker.pid';
//        $proc = proc_open('exec php infinite_with_pid.php '.$pidFile, [], $pipes);
//        sleep(1);
//        $this->assertNotFalse($proc);
//        $this->proc = $proc;
//
//        $pid = file_get_contents($pidFile);
//        $this->assertNotFalse($pid);
//
//        $this->pid = $pid;
//    }
}
