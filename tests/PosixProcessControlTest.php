<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 14:23
 */

namespace YevgenGrytsay\PidHelper\tests;

use YevgenGrytsay\PidHelper\PosixProcessControl;

class PosixProcessControlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @requires extension posix
     */
    public function testExists()
    {
        $helper = new PosixProcessControl();
        $exists = $helper->exists(getmypid());

        $this->assertTrue($exists);
    }
}
