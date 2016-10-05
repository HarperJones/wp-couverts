<?php
/*
 * @author: petereussen
 * @package: lakes
 */

namespace HarperJones\Couverts;


class Helpers
{
  static $instance = null;
  static $admin    = null;

  /**
   * Rewrite of locate_template to also load from plugin directory
   *
   * @param $template_names
   * @param bool $load
   * @param bool $require_once
   * @return string
   */
  static public function locate_template($template_names,$load = false,$require_once = true)
  {
    $located = \locate_template($template_names,false,$require_once);

    if ( $located === '' ) {
      foreach( (array) $template_names as $template_name ) {
        if ( !$template_name ) {
          continue;
        }

        if ( file_exists(COUVERTS_PLUGIN_PATH . '/' . $template_name )) {
          $located = COUVERTS_PLUGIN_PATH . '/' . $template_name;
          break;
        }
      }
    }

    if ( $located && $load ) {
      load_template($located,$require_once);
    }
    return $located;
  }

  /**
   * Rewrite of get_template_part to also load from plugin
   *
   * @param $slug
   * @param null $name
   */
  static public function get_template_part($slug, $name = null)
  {
    do_action( "get_template_part_{$slug}", $slug, $name );

    $templates = array();
    $name = (string) $name;
    if ( '' !== $name )
      $templates[] = "{$slug}-{$name}.php";

    $templates[] = "{$slug}.php";

    Helpers::locate_template($templates, true, false);
  }

  /**
   * Instantiates the Reservation Service class
   *
   * @return ReservationService
   * @throws \RuntimeException
   */
  static public function getService()
  {
    if ( !static::requirementsMet()) {
      throw new \RuntimeException(__('Plugin not configured properly, please contact administrator','couverts'));
    }
    if ( self::$instance === null ) {
      $api            = new ReservationAPI(
        Config::getRestaurantCode(),
        Config::getApiKey(),
        Config::getLanguage(),
        Config::getAPiURL()
      );
      self::$instance = new ReservationService($api);
    }
    return self::$instance;
  }

  static public function setupAdmin()
  {
    if ( static::$admin === null ) {
      static::$admin = new \HarperJones\Couverts\AdminOptions();
    }
    return static::$admin;
  }

  static public function requirementsMet()
  {
    return Config::getApiKey() && Config::getRestaurantCode();
  }
}


function get_template_part($slug, $name = null)
{
  Helpers::get_template_part($slug,$name);
}

function locate_template($template_names,$load = false,$require_once = true)
{
  return Helpers::locate_template($template_names,$load,$require_once);
}

