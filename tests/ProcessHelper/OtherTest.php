<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 20.07.15
 */

namespace YevgenGrytsay\PidHelper\tests\ProcessHelper;

use YevgenGrytsay\PidHelper\PosixProcessControl;

class OtherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @requires extension posix
     */
    public function testNoPermission()
    {
        $helper = new PosixProcessControl();

        $this->setExpectedException('\RuntimeException', 'Call to posix_kill caused EPERM error: Operation not permitted (POSIX.1)');

        $helper->exists(1);
    }
}
