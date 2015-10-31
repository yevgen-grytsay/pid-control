<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 15:37
 */

namespace YevgenGrytsay\PidHelper\PidFileHelper;


class FlockContext implements ContextInterface
{
    private $operation;
    private $file;

    /**
     * FlockContext constructor.
     *
     * @param \SplFileObject $file
     * @param $operation
     */
    public function __construct(\SplFileObject $file, $operation)
    {
        $this->file = $file;
        $this->operation = $operation;
    }

    public function enter()
    {
        $lock = $this->file->flock($this->operation);
        if ($lock === false) {
            throw new \RuntimeException(sprintf('Не удается заблокировать файл "%s" в режиме "%d".',
                $this->file->getPathname(), $this->operation
        ));
        }
    }

    public function leave()
    {
        $this->file->flock(LOCK_UN);
    }

    /**
     * @return \SplFileInfo
     */
    public function getFileInfo()
    {
        return $this->file->getFileInfo();
    }
}