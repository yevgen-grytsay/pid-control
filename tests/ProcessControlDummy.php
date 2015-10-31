<?php
/**
 * @author: yevgen
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
}