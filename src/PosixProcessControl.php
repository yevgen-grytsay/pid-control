<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 20.07.15
 */

namespace YevgenGrytsay\PidHelper;

class PosixProcessControl implements ProcessControlInterface
{
    /**
     * @param $pid
     *
     * @return bool
     * @throws \RuntimeException Если не удается определить,
     *                           существует процесс или нет.
     */
    public function exists($pid)
    {
        $result = false;
        $success = posix_kill($pid, 0);
        if ($success === false) {
            $errno = posix_get_last_error();
            if($errno !== 0) {
                $result = $this->handleErrorCode($errno);
            }
        }
        else {
            $result = true;
        }

        return $result;
    }

    /**
     * @param $pid
     *
     * @return bool
     */
    public function kill($pid)
    {
        return posix_kill($pid, SIGKILL);
    }

    /**
     * @param $pid
     *
     * @return bool
     */
    public function terminate($pid)
    {
        return posix_kill($pid, SIGTERM);
    }

    /**
     * @param $errno
     *
     * @return bool
     * @throws \RuntimeException
     */
    protected function handleErrorCode($errno)
    {
        switch ($errno) {
            /* No such process */
            case PCNTL_ESRCH:
                $result = false;
                break;

            /* Operation not permitted */
            case PCNTL_EPERM:
                throw new \RuntimeException('Call to posix_kill caused EPERM error: Operation not permitted (POSIX.1)');
                break;

            default:
                $msg = posix_strerror($errno);
                throw new \RuntimeException("Call to posix_kill caused '{$errno}' error: {$msg}");
                break;
        }

        return $result;
    }
}