<?php
declare(strict_types=1);

namespace app;

/**
 * Some functions that span mulitple use cases
 */
abstract class Utility
{

  /**
    * Creates a token of a specified length
    * @return array
    */
    public function generateToken(int $length, int $tokensReturnedNumber, String $charactersIncluded):array
    {
      $symbols = array();
      $passwords = array();
      $used_symbols = '';
      $pass = '';

      // an array of different character types
      $symbols["numbers"] = '1234567890';

      $charactersIncluded = explode(",", $charactersIncluded); // get charactersIncluded types to be used for the passsword
      foreach ($charactersIncluded as $key=>$value) {
          $used_symbols .= $symbols[$value]; // build a string with all charactersIncluded
      }
      $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of charactersIncluded deduct 1

      for ($p = 0; $p < $tokensReturnedNumber; $p++) {
          $pass = '';
          for ($i = 0; $i < $length; $i++) {
              $n = rand(0, $symbols_length); // get a random character from the string with all charactersIncluded
             $pass .= $used_symbols[$n]; // add the character to the password string
          }
          $tokens[] = $pass;
      }

      return $tokens;
    }
}
