<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 15:34
 */

namespace YevgenGrytsay\PidHelper\PidFileHelper;


use YevgenGrytsay\PidHelper\PidFileHelper\Exception\FopenException;

class FopenContext implements ContextInterface
{
    private $filename;
    private $mode;
    private $file;

    /**
     * FopenContext constructor.
     *
     * @param $filename
     * @param $mode
     */
    public function __construct($filename, $mode)
    {
        $this->filename = $filename;
        $this->mode = $mode;
    }

    public function enter()
    {
        try {
            $file = new \SplFileObject($this->filename, $this->mode);
        } catch (\Exception $e) {
            throw new FopenException(sprintf('Error occured while opening file
             "%s" with mode "%s". See previous exception for details',
                $this->filename, $this->mode), 0, $e);
        }

        $this->file = $file;
    }

    public function leave()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}