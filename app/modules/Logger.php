<?php
declare(strict_types=1);

namespace app;

/**
 * The Logger module allows to write text files for routine operations and errors occurring
 */
class Logger
{
    const LOGFILESPATH = __DIR__.'/../logs/';

    /**
     * Log either an error or a routine using this static method
     */
    public static function logToFile(String $logType, String $logMessage)
    {
        $clientIP = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $scriptName = $_SERVER["SCRIPT_FILENAME"];
        $date = new \DateTime("now", new \DateTimeZone('Africa/Nairobi'));
        $time = $date->format('Y-m-d H:i:s');

        if ($logType == 'Error') {
         $logFile = self::LOGFILESPATH.'error_log.txt';
         if (!file_exists($logFile)) {
            file_put_contents($logFile, '');
            chmod($logFile, 0777);
         }
        } elseif ($logType === 'Routine') {
            $logFile = self::LOGFILESPATH.'routine_log.txt';
            if (!file_exists($logFile)) {
             file_put_contents($logFile, '');
             chmod($logFile, 0777);
            }
        }

        $contents = file_get_contents($logFile);

        $contents .= $time ."\tIP Address:".$clientIP."\tClient User Agent:".$userAgent."\tScript running:".$scriptName."\tError Message:".$logMessage."\n";

        file_put_contents($logFile, $contents);
    }
}
