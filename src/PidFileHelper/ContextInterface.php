<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 20.07.15
 */

namespace YevgenGrytsay\PidHelper\PidFileHelper;

interface ContextInterface
{
    public function enter();
    public function leave();
}