<?php
/**
 * @author: yevgen
 */

namespace YevgenGrytsay\PidHelper\tests;

use YevgenGrytsay\PidHelper\ProcessControlInterface;

class ProcessControlStub implements ProcessControlInterface
{
    /**
     * @var
     */
    private $exists;

    /**
     * ProcessControlStub constructor.
     *
     * @param $exists
     */
    public function __construct($exists)
    {
        $this->exists = (boolean)$exists;
    }

    /**
     * @param int $pid
     *
     * @return boolean
     */
    public function exists($pid)
    {
        return $this->exists;
    }
}