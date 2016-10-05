<?php
/*
Plugin Name:        Dinner Reservations Calendar with Couverts
Plugin URI:         https://github.com/HarperJones/wp-couverts
Description:        Module which will allow you to create a nicer looking Couverts reservation screen on your site
Version:            1.1
Author:             HarperJones
Author URI:         https://harperjones.nl
Text Domain:        couverts

License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

require_once('src/Config.php');
require_once('src/Helpers.php');
require_once('src/Reservation.php');
require_once('src/ReservationAPI.php');
require_once('src/ReservationService.php');
require_once('src/NoTimeAvailableException.php');
require_once('src/AdminOptions.php');

define("COUVERTS_PLUGIN_PATH",__DIR__);
define("COUVERTS_PLUGIN_FILE", plugin_basename(__FILE__));
define("COUVERTS_PLUGIN_BASEURL", plugin_dir_url(__FILE__));

/**
 * Call this function from the template where you want to have the reservation form
 *
 * @param bool $date
 * @throws Exception
 */
function couverts_reservation($date = false)
{
  try {
    $reservation = \HarperJones\Couverts\Helpers::getService();
    $reservation->getForm();
  } catch(\Exception $e) {
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
      throw $e;
    }
    HarperJones\Couverts\get_template_part('templates/couverts/error');
  }
}

/**
 * Returns a set of dates on which the restaurant is open
 *
 * @param $amount
 * @return array
 */
function couverts_get_open_dates($amount)
{
  return \HarperJones\Couverts\Helpers::getService()->getOpenDates($amount);
}

/**
 * Returns information about maximum party size etc
 *
 * @param $daysAhead
 * @return array
 */
function couverts_get_day_config($daysAhead)
{
  return \HarperJones\Couverts\Helpers::getService()->getDayConfig($daysAhead);
}

/**
 * Return day configuration for a number of days in the future as a javascript object
 *
 * @param $daysAhead
 * @return string
 */
function couverts_get_day_config_js($daysAhead)
{
  return \HarperJones\Couverts\Helpers::getService()->getConfigObject($daysAhead);
}

/**
 * Get a list of all available times
 *
 */
function couverts_get_available_times()
{
  $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
  $party= isset($_POST['party']) ? $_POST['party'] : 2;


  try {
    echo json_encode(\HarperJones\Couverts\Helpers::getService()->getAvailableTimeSlots($date,$party));
  } catch ( \Exception $e) {
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
      throw $e;
    }
    HarperJones\Couverts\get_template_part('templates/couverts/error');
  }
  exit();
}

/**
 * Tries to place a reservation with couverts
 *
 */
function couverts_handle_reservation()
{
  try {
    $reservation = \HarperJones\Couverts\Reservation::createFromGlobals($_POST);
    $reply       = \HarperJones\Couverts\Helpers::getService()->makeReservation($reservation);

    echo json_encode($reply);
  } catch ( \Exception $e) {
    if ( defined('WP_DEBUG') && WP_DEBUG ) {
      throw $e;
    }
    HarperJones\Couverts\get_template_part('templates/couverts/error');
  }

  exit();
}


function couverts_basic_info()
{
  return \HarperJones\Couverts\Helpers::getService()->getBasicInfo();
}

function couverts_get_form_fields($datetime = false)
{
  return \HarperJones\Couverts\Helpers::getService()->getContactFormFields($datetime);
}

function couverts_language()
{
  return \HarperJones\Couverts\Config::getLanguage();
}

function couverts_get_contact_form()
{
  $selectedDate = isset($_POST['dt']) ? $_POST['dt'] : false;
  $selectedTime = isset($_POST['ts']) ? $_POST['ts'] : false;

  if ( $selectedDate && $selectedTime ) {
    $dt = new DateTime($selectedDate . ' ' . $selectedTime);

    \HarperJones\Couverts\Helpers::getService()->getFormFields($dt);
  }
  exit();
}

add_action('wp_ajax_couverts_available_times','couverts_get_available_times');
add_action('wp_ajax_nopriv_couverts_available_times','couverts_get_available_times');
add_action('wp_ajax_couverts_handle_reservation','couverts_handle_reservation');
add_action('wp_ajax_nopriv_couverts_handle_reservation','couverts_handle_reservation');
add_action('wp_ajax_couverts_get_contact_form','couverts_get_contact_form');
add_action('wp_ajax_nopriv_couverts_get_contact_form','couverts_get_contact_form');
add_action('init',function() {
  load_plugin_textdomain('couverts',false,dirname(plugin_basename(__FILE__)) . '/languages');
});

if(is_admin()) {
  \HarperJones\Couverts\Helpers::setupAdmin();
}

if ( !\HarperJones\Couverts\Helpers::requirementsMet()) {
  add_action('admin_notices', function() {
    $class = 'notice notice-error';
    $message = __( 'Couverts plugin is not configured properly. You probably forgot to define the variables COUVERTS_API_KEY and COUVERTS_RESTAURANT_CODE', 'couverts' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
  });
}
add_shortcode('couverts', function($atts) {
  $date = isset($_GET['couverts_date']) ? $_GET['couverts_date'] : false;

  couverts_reservation($date);
});
