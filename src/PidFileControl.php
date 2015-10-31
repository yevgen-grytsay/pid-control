<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 15:00
 */

namespace YevgenGrytsay\PidHelper;


use Assert\Assertion;
use YevgenGrytsay\PidHelper\PidFileHelper\ContextChain;
use YevgenGrytsay\PidHelper\PidFileHelper\FlockContext;
use YevgenGrytsay\PidHelper\PidFileHelper\FopenContext;

class PidFileControl
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var int
     */
    private $pid;

    /**
     * PidFileHelper constructor.
     *
     * @param string $filename
     * @param int $pid
     * @throws \RuntimeException
     */
    public function __construct($filename, $pid)
    {
        $this->filename = (string)$filename;
        $this->pid = (int)$pid;

        Assertion::notEmpty($this->filename, 'Имя файла не может быть пустым.');
        Assertion::greaterThan($this->pid, 0, 'Pid не может быть пустым.');
    }

    /**
     * Пытается создать файл, заблокировать его и записать
     * в него заданное значение id.
     *
     * Файл не будет создан, если уже существует,
     * поэтому перед вызовом метода рекомендуется удалить файл
     * с помощью процедуры @see \app\components\PidFileHelper\Cleanup
     *
     * @throws \Exception
     */
    public function writePid()
    {
        $contextChain = new ContextChain();
        $fileCtx = new FopenContext($this->filename, 'x');

        $contextChain->with($fileCtx, [$this, 'onSuccessFileCreateAndOpen']);
    }

    /**
     * @param \YevgenGrytsay\PidHelper\PidFileHelper\ContextChain $chain
     * @param \YevgenGrytsay\PidHelper\PidFileHelper\FopenContext $ctx
     *
     * @throws \Exception
     */
    public function onSuccessFileCreateAndOpen(ContextChain $chain, FopenContext $ctx)
    {
        $lockCtx = new FlockContext($ctx->getFile(), LOCK_EX | LOCK_NB);
        $chain->with($lockCtx, [$this, 'onSuccessFileCreateOpenAndLock']);
    }

    /**
     * @param \YevgenGrytsay\PidHelper\PidFileHelper\ContextChain $chain
     * @param \YevgenGrytsay\PidHelper\PidFileHelper\FlockContext $ctx
     * @throws \RuntimeException
     */
    public function onSuccessFileCreateOpenAndLock(ContextChain $chain, FlockContext $ctx)
    {
        file_put_contents($this->filename, $this->pid);
        $this->ensurePidWritten();
    }

    /**
     * @throws \RuntimeException
     */
    public function ensurePidWritten()
    {
        $written = file_get_contents($this->filename);
        if (empty($written)) {
            throw new \RuntimeException('Проверка соответствия записанного в файл pid завершилась неудачей.');
        }

        if ((int)$written !== $this->pid) {
            throw new \RuntimeException(sprintf('Written pid %s does not match actual %s.',
                $written, $this->pid));
        }
    }
}