<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 31.10.15
 */

namespace YevgenGrytsay\PidHelper\tests;

use YevgenGrytsay\PidHelper\ProcessControlInterface;

class ProcessControlDummy implements ProcessControlInterface
{
    /**
     * @param int $pid
     *
     * @return boolean
     */
    public function exists($pid)
    {
        return null;
    }

    /**
     * @param int $pid
     *
     * @return bool
     */
    public function kill($pid)
    {
        return null;
    }

    /**
     * @param int $pid
     *
     * @return bool
     */
    public function terminate($pid)
    {
        return null;
    }
}