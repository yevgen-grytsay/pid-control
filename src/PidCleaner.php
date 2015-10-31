<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 21.07.15
 */

namespace YevgenGrytsay\PidHelper;

use YevgenGrytsay\PidHelper\PidFileHelper\ContextChain;
use YevgenGrytsay\PidHelper\PidFileHelper\FlockContext;

class PidCleaner
{
    /**
     * @var ProcessControlInterface
     */
    private $processControl;

    /**
     * PidCleaner constructor.
     *
     * @param ProcessControlInterface $processControl
     */
    public function __construct(ProcessControlInterface $processControl)
    {
        $this->processControl = $processControl;
    }

    /**
     * @param \SplFileInfo $fileInfo
     *
     * @return bool
     * @throws \Exception
     */
    public function clean(\SplFileInfo $fileInfo)
    {
        $filename = $fileInfo->getPathname();
        if (!file_exists($filename)) {
            return true;
        }

        /** @throws \RuntimeException */
        $file = $fileInfo->openFile('r');

        $chain = new ContextChain();
        $lockOperation = LOCK_EX | LOCK_NB;
        $flockCtx = new FlockContext($file, $lockOperation);

        $chain->with($flockCtx, function(ContextChain $chain, FlockContext $ctx) {
            $filename = $ctx->getFileInfo()->getPathname();
            $content = file_get_contents($filename);
            if ($content === false) {
                throw new \RuntimeException(sprintf('Не удалось получить содержимое файла "%s".',
                    $filename));
            }

            $prevPid = (int)$content;
            if ($prevPid > 0) {
                if ($this->processExists($prevPid)) {
                    throw new \RuntimeException(sprintf('Нельзя удалять pid-файл "%s": процесс с pid=%s запущен.',
                        $filename, $prevPid));
                }
            }

            unlink($filename);
            if (file_exists($filename)) {
                throw new \RuntimeException(sprintf('Файл "%s" подлежит удалению, но удалить его не удалось.',
                    $filename));
            }
        });
    }

    /**
     * @param $pid
     *
     * @return bool
     */
    protected function processExists($pid)
    {
        return $this->processControl->exists($pid);
    }
}