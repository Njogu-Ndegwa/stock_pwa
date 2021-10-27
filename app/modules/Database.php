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

    /**
      * Perform an INSERT SQL query to a database supplied by the connection passed in params
      */
    protected function insertSQLStatement(String $SQLQueryString, \mysqli $DBConnection): array
    {
        if ($this->getSQLQueryType($SQLQueryString) != "INSERT") {
            throw new \Exception("Expecting 'INSERT' SQL statement, got '". $this->getSQLQueryType($SQLQueryString) . "' SQL statement", 1);
        }
        // attempt the query
        $queryResult = mysqli_query($DBConnection, $SQLQueryString);

        if ($queryResult) {

      // in separate occassions the id of a successful insert maybe required
            $insertID = mysqli_insert_id($DBConnection);

            // add array to return
            $responseArray['response'] = '200';
            $responseArray['message'] = 'Success';
            $responseArray['data'] = ["The Insert Was Performed OK", $insertID];
        } else {
            $responseArray['response'] = '500';
            $responseArray['message'] = mysqli_error($DBConnection);
            $responseArray['data'] = null;

            Logger::logToFile('Error', $responseArray['message']." SQL Statement: ". $SQLQueryString );
        }

        return $responseArray;
    }

    /**
    * Reading data from the database
    */
    protected function selectSQLStatement(String $SQLQueryString, \mysqli $DBConnection): array
    {
        if ($this->getSQLQueryType($SQLQueryString) != "SELECT") {
            throw new \Exception("Expecting 'SELECT' SQL statement, got '". $this->getSQLQueryType($SQLQueryString) . "' SQL statement", 1);
        }
        // attempt the query
        $queryResult = mysqli_query($DBConnection, $SQLQueryString);

        if ($queryResult) {
            // success
            $rowCount = mysqli_affected_rows($DBConnection);

            if ($rowCount > 0) {

       // move result set to an associative array
                $queryData = array();
                while ($datum= mysqli_fetch_assoc($queryResult)) {
                    $data[]=$datum;
                }

                $responseArray['response'] = '200';
                $responseArray['message'] = 'Success';
                $responseArray['data'] = $data;
            } else {
                // no data returned
                $responseArray['response'] = '204';
                $responseArray['message'] = 'No Matches!';
                $responseArray['data'] = [];
            }
        } else {
            $responseArray['response'] = '500';
            $responseArray['message'] = mysqli_error($DBConnection);
            $responseArray['data'] = null;
            Logger::logToFile('Error', $responseArray['message']." SQL Statement: ". $SQLQueryString );
        }


        return $responseArray;
    }

    /**
     *Pass an SQL statement to get the query type
    */
    private function getSQLQueryType(String $SQLQueryString): String
    {
        if (is_array($SQLQueryString)) {
            $SQLQueryString = key($SQLQueryString);
        }

        $matches = null;
        if (!preg_match('/^\s*(SELECT|INSERT|REPLACE|UPDATE|DELETE|TRUNCATE|CALL|DO|HANDLER|LOAD\s+(?:DATA|XML)\s+INFILE|(?:ALTER|CREATE|DROP|RENAME)\s+(?:DATABASE|TABLE|VIEW|FUNCTION|PROCEDURE|TRIGGER|INDEX)|PREPARE|EXECUTE|DEALLOCATE\s+PREPARE|DESCRIBE|EXPLAIN|HELP|USE|LOCK\s+TABLES|UNLOCK\s+TABLES|SET|SHOW|START\s+TRANSACTION|BEGIN|COMMIT|ROLLBACK|SAVEPOINT|RELEASE SAVEPOINT|CACHE\s+INDEX|FLUSH|KILL|LOAD|RESET|PURGE\s+BINARY\s+LOGS|START\s+SLAVE|STOP\s+SLAVE)\b/si', $SQLQueryString, $matches)) {
            return null;
        }

        $type = strtoupper(preg_replace('/\s++/', ' ', $matches[1]));
        if ($type === 'BEGIN') {
            $type = 'START TRANSACTION';
        }

        return $type;
    }

    /**
    * Sanitises data based on the specific instance of the dbconnections
    * @return string
    */
    protected function sanitiseData($variable, \mysqli $DBConnection): String
    {
      return $DBConnection->real_escape_string($variable);
    }
}