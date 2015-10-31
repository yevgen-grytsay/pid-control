<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 20.07.15
 */

namespace YevgenGrytsay\PidHelper\PidFileHelper;

class ContextChain
{
    /**
     * Входит в заданный контекст и выполняет заданную функцию.
     * Если метод enter бросает исключение, считается, что
     * вход в контекст не был совершен, поэтому очистка
     * (@see YevgenGrytsay\PidHelper\PidFileHelper\ContextInterface::leave())
     * не выполняется. Из этого следуют следующие рекомендации:
     * - Нежелательно в методе enter открывать больше одного ресурса.
     * - Также нежелательно после открытия ресурса производить какие-либо действия,
     *   которые могут привести к исключениям.
     *
     * @param \YevgenGrytsay\PidHelper\PidFileHelper\ContextInterface $ctx
     * @param callable                                          $fnc
     *
     * @throws \Exception
     */
    public function with(ContextInterface $ctx, callable $fnc)
    {
        try {
            $ctx->enter();
        } catch (\Exception $e) {
            throw $e;
        }

        try {
            call_user_func_array($fnc, [$this, $ctx]);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            try {
                $ctx->leave();
            } catch (\Exception $e) {/* Not important */}
        }
    }
}