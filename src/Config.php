<?php
/*
 * @author: petereussen
 * @package: lakes
 */

namespace HarperJones\Couverts;


class Config
{
  static public function getApiKey()
  {
    return self::getOption('COUVERTS_API_KEY');
  }

  static public function getRestaurantCode()
  {
    return self::getOption('COUVERTS_RESTAURANT_CODE');
  }

  static public function getAPiURL()
  {
    $url = self::getOption('COUVERTS_API_URL');

    if ( !$url ) {
      return 'https://api.testing.couverts.nl';
    }
  }

  static public function getLanguage()
  {
    $lang   = self::getOption('COUVERTS_LANGUAGE');

    if ( $lang ) {
      return $lang;
    }

    $locale = get_locale();

    if ( substr($locale,-2) === 'NL') {
      return 'Dutch';
    }
    return 'English';
  }

  static private function getOption($key)
  {
    if ( defined($key)) {
      return $$key;
    }
    $val = getenv($key);

    if ( $val ) {
      return $val;
    }
    return null;
  }
}