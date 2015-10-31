<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 21.07.15
 * Time: 17:00
 */

namespace YevgenGrytsay\PidHelper\tests\PidFileHelper;


use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use YevgenGrytsay\PidHelper\PidCleaner;
use YevgenGrytsay\PidHelper\tests\ProcessControlDummy;
use YevgenGrytsay\PidHelper\tests\ProcessControlStub;

class CleanupTest extends \PHPUnit_Framework_TestCase
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

    public function testNoSuchProcess()
    {
        $file = $this->createFileWithPidNotNull();
        $processControl = new ProcessControlStub(false);
        $fileInfo = new \SplFileInfo($file->url());
        $cleanup = new PidCleaner($processControl);
        $cleanup->clean($fileInfo);

        $this->assertFalse($this->dir->hasChild($this->pidBasename));
    }

    public function testProcessIsRunning()
    {
        $file = $this->createFileWithExistingPid();
        $processControl = new ProcessControlStub(true);
        $fileInfo = new \SplFileInfo($file->url());
        $cleanup = new PidCleaner($processControl);

        $this->setExpectedException(\RuntimeException::class, sprintf('Нельзя удалять pid-файл "%s": процесс с pid=%s запущен.',
            $file->url(), $file->getContent()));
        $cleanup->clean($fileInfo);

        $this->assertTrue($this->dir->hasChild($this->pidBasename));
    }

    public function testFailDelete()
    {
        $file = $this->createDummyFile();
        $processControl = new ProcessControlDummy();
        $this->dir->chmod(0444);
        $fileInfo = new \SplFileInfo($file->url());
        $cleanup = new PidCleaner($processControl);

        $this->setExpectedException(\RuntimeException::class, sprintf('Файл "%s" подлежит удалению, но удалить его не удалось.',
                $file->url())
        );

        $cleanup->clean($fileInfo);
    }

    public function testFileOpenFail()
    {
        $file = $this->createNotReadableDummyFile();
        $processControl = new ProcessControlDummy();
        $fileInfo = new \SplFileInfo($file->url());
        $cleanup = new PidCleaner($processControl);

        $this->setExpectedException(\RuntimeException::class);

        $cleanup->clean($fileInfo);
    }

    public function testShouldThrowExceptionIfFileLockingFails()
    {
        $file = $this->createDummyLockedFile();
        $processControl = new ProcessControlDummy();
        $fileInfo = new \SplFileInfo($file->url());
        $cleanup = new PidCleaner($processControl);

        $lockOperation = LOCK_EX | LOCK_NB;
        $this->setExpectedException(\RuntimeException::class, sprintf('Не удается заблокировать файл "%s" в режиме "%s".',
            $file->url(), $lockOperation));

        $cleanup->clean($fileInfo);
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createNotReadableDummyFile()
    {
        $file = vfsStream::newFile($this->pidBasename)
            ->chmod(0333);
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
        $impossiblePid = 0;
        $file = vfsStream::newFile($this->pidBasename)
                         ->setContent((string)$impossiblePid);
        $this->dir->addChild($file);
        $this->assertTrue($this->dir->hasChild($this->pidBasename));

        return $file;
    }

    /**
     * @return \org\bovigo\vfs\vfsStreamFile
     */
    protected function createFileWithPidNotNull()
    {
        $file = vfsStream::newFile($this->pidBasename)
                         ->setContent('12345');
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
