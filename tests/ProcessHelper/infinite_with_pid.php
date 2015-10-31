<?php
/**
 * Created by PhpStorm.
 * User: yevgen
 * Date: 20.07.15
 * Time: 14:13
 */

/**
 * @param $signal
 */
$onTermination = function($signal) {
    exit("Terminated: {$signal}");
};

$pidFileName = $argv[1];
if (file_put_contents($pidFileName, getmypid())) {
    pcntl_signal(SIGTERM, $onTermination);
    while (true) {
        sleep(1);
        pcntl_signal_dispatch();
    }
}
