<?php
declare(strict_types=1);

namespace app;

/**
 * This is the database abstract module,
 * it is extended by each class having to perform actions in the database
 */
abstract class Database
{

  /**
    * Creates a new connection to the database
    * @return array
    */
    protected function dbConnect():array
    {
      $dotENVInstance = \Dotenv\Dotenv::createImmutable(__DIR__.'/../config');
      $dotENVInstance->load();

      $DBConnection= new \mysqli($_ENV['DB_HOSTNAME'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

      if ($DBConnection->connect_error) {

        $responseArray['response'] = '500';
        $responseArray['message'] = 'There was an error connecting to the database. Description: ' . $DBConnection->connect_error;
        $responseArray['data'] = null;
        Logger::logToFile('Error', $responseArray['message']." Required script:". __FILE__ ." Line Number: ". __LINE__);

      } else {

        $responseArray['response'] = '200';
        $responseArray['message'] = 'Connection to the database was successful';
        $responseArray['data'] = $DBConnection;

      }

        return $responseArray;
    }
}
