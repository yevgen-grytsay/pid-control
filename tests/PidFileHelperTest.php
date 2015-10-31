<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 20.07.15
 */

namespace YevgenGrytsay\PidHelper\tests;

use YevgenGrytsay\PidHelper\PidFileControl;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use YevgenGrytsay\PidHelper\PidFileHelper\Exception\FopenException;

class PidFileHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    protected $dir;
    protected $pidBasename = 'worker_1.pid';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->dir = vfsStream::setup('pidDir');
    }

    public function testShouldThrowExceptionIfFileExists()
    {
        $file = $this->createDummyFile();
        $ph = new PidFileControl($file->url(), 1);

        $this->setExpectedException(FopenException::class, sprintf('Error occured while opening file
             "%s" with mode "%s". See previous exception for details',
            $file->url(), 'x'));

        $ph->writePid();
    }

    public function testShouldWritePid()
    {
        $pid = 12345;
        $ph = new PidFileControl($this->dir->url().'/'.$this->pidBasename, $pid);

        $ph->writePid();

        $file = $this->dir->getChild($this->pidBasename);
        $writtenPid = $file->getContent();
        $this->assertEquals($pid, $writtenPid);
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createNotReadableDummyFile()
    {
        $file = vfsStream::newFile($this->pidBasename)
                         ->chmod(0);
        $this->dir->addChild($file);
        $this->assertTrue($this->dir->hasChild($this->pidBasename));

        return $file;
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createFileWithExistingPid()
    {
        $file = vfsStream::newFile($this->pidBasename)
                         ->setContent((string)getmypid());
        $this->dir->addChild($file);
        $this->assertTrue($this->dir->hasChild($this->pidBasename));

        return $file;
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createFileWithNonExistingPid()
    {
        $file = vfsStream::newFile($this->pidBasename)
                         ->setContent('123456789');
        $this->dir->addChild($file);
        $this->assertTrue($this->dir->hasChild($this->pidBasename));

        return $file;
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createDummyFile()
    {
        $file = vfsStream::newFile($this->pidBasename);
        $this->dir->addChild($file);
        $this->assertTrue($this->dir->hasChild($this->pidBasename));

        return $file;
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createDummyLockedFile()
    {
        $file = vfsStream::newFile($this->pidBasename);
        $this->dir->addChild($file);
        $this->assertTrue($this->dir->hasChild($this->pidBasename));

        $lock = $file->lock($file, LOCK_EX | LOCK_NB);
        $this->assertTrue($lock);

        return $file;
    }
}
