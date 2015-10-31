<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 15:34
 */

namespace YevgenGrytsay\PidHelper\PidFileHelper;


interface ContextInterface
{
    public function enter();
    public function leave();
}