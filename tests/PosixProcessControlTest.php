<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 31.10.15
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
