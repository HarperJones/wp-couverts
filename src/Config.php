<?php
/*
 * @author: petereussen
 * @package: lakes
 */

namespace HarperJones\Couverts;

/**
 * Obtain configuration in a backward compatible kind of way
 *
 * Either load the config from environment, or from a constant
 *
 * @package HarperJones\Couverts
 */
class Config
{
  /**
   * Generic get of a value, with a backup default value if it can't be found
   *
   * @param string $key
   * @param null $default
   * @return mixed|null
   */
  static public function get($key,$default = null)
  {
    $value = self::getOption($key);

    if ( $value !== null ) {
      return $value;
    }
    return $default;
  }

  /**
   * Returns the Couverts API Key value
   *
   * @return null|string
   */
  static public function getApiKey()
  {
    return self::getOption('COUVERTS_API_KEY');
  }

  static public function getRestaurantCode()
  {
    return self::getOption('COUVERTS_RESTAURANT_CODE');
  }

  /**
   * Gets the URL defined by the COUVERTS_API_URL environment or define variable
   *
   * @return null|string
   */
  static public function getAPiURL()
  {
    $url = self::getOption('COUVERTS_API_URL');

    if ( !$url ) {
      return 'https://api.testing.couverts.nl';
    }
    return $url;
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
      return constant($key);
    }
    $val = getenv($key);

    if ( $val ) {
      return $val;
    }

    $options = get_option('couverts_settings');

    if( isset($options[$key]) ) {
      return $options[$key];
    }

    return null;
  }
}