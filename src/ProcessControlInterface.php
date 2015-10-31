<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 31.10.15
 */

namespace YevgenGrytsay\PidHelper;

interface ProcessControlInterface
{
    /**
     * @param int $pid
     *
     * @return boolean
     */
    public function exists($pid);

    /**
     * @param int $pid
     *
     * @return bool
     */
    public function kill($pid);

    /**
     * @param int $pid
     *
     * @return bool
     */
    public function terminate($pid);
}