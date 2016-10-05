<?php
/*
 * @author: petereussen
 * @package: lakes
 */

namespace HarperJones\Couverts;

/**
 * Base service class which wraps the Couverts API to make things a bit easier to use
 *
 * @package HarperJones\Couverts
 */
class ReservationService
{
  /**
   * The Basic Information of a company
   *
   * @var object
   */
  protected $info;

  /**
   * The actual API wrapper when we need to some info
   *
   * @var ReservationAPI
   */
  protected $service;

  public function __construct(ReservationAPI $service)
  {
    $this->service = $service;
    $this->info    = $this->service->getBasicInfo();
  }

  /**
   * Returns the basic information of a company/restaurant as supplied by Couverts
   *
   * @return object
   */
  public function getBasicInfo()
  {
    return $this->info;
  }

  /**
   * Returns all day configuration information as an Array
   *
   * @param $daysAhead
   * @return array
   */
  public function getDayConfig($daysAhead)
  {
    $dayConfig = get_site_transient('couverts_day_config_' . $daysAhead);

    if ( $dayConfig && is_array($dayConfig) ) {
      return $dayConfig;
    }

    $curdate   = new \DateTime();
    $dayInfo   = [];

    for ($d = 0; $d < $daysAhead; $d++) {
      try {
        $info = $this->service->getDateConfig($curdate);
      } catch( \Exception $e) {
        continue;
      }

      $info->Date                         = clone $curdate;
      $dayInfo[$curdate->format('Y-m-d')] = $info;

      $curdate->add(new \DateInterval('P1D'));
    }
    set_site_transient('couverts_day_config_' . $daysAhead, $dayInfo, Config::get('COUVERTS_CACHE_TIMEOUT',3600));

    return $dayInfo;
  }

  /**
   * Returns a set of dates on which the restaurant is open
   *
   * @param $daysAhead
   * @return array|bool
   */
  public function getOpenDates($daysAhead)
  {
    $final       = [];
    $openingInfo = false;
    $openingInfo = get_site_transient('couverts_opening_info_' . $daysAhead);

    if ( $openingInfo && is_array($openingInfo) ) {
      return $openingInfo;
    }

    $dayConfig = $this->getDayConfig($daysAhead);

    foreach( $dayConfig as $config ) {
      if ( apply_filters('couverts_open_on_date',!$config->IsRestaurantClosed,$config->Date) ) {
        $final[] = $config->Date;
      }
    }

    set_site_transient('couverts_opening_info_' . $daysAhead, $final, Config::get('COUVERTS_CACHE_TIMEOUT',3600));

    return $final;
  }


  /**
   * Get a list of available times for a specific date and group size
   *
   * @param $date
   * @param $party
   * @return object
   */
  public function getAvailableTimeslots($date,$party)
  {
    $date  = new \DateTime($date);

    try {
      $reply = $this->service->getAvailableTimes($date, $party);

      // Failsafe in case there is no time available
      if ( !isset($reply->Times) ) {
        $reply = new \stdClass();
        $reply->Times            = array();
        $reply->NoTimesAvailable = true;
      }
    } catch ( NoTimeAvailableException $e) {
      $reply = new \stdClass();
      $reply->Times            = array();
      $reply->NoTimesAvailable = true;
    }

    return $reply;
  }

  /**
   * Returns the day configuration as javascript object
   *
   * @param $daysAhead
   * @return string
   */
  public function getConfigObject($daysAhead)
  {
    $dayInfo = $this->getDayConfig($daysAhead);

    $entries = [];
    foreach( $dayInfo as $dayConfig ) {
      $entries[] = sprintf(
        '"%s": { min: %d, max: %d }',
        $dayConfig->Date->format('Y-m-d'),
        $dayConfig->MinimumNumberOfPeople,
        $dayConfig->GroupReservationFromNumberOfPeople - 1
      );
    }

    return "{" . implode($entries,",") . "};\n";
  }

  public function getFormFields($datetime = false)
  {
    if ( ! $datetime instanceof \DateTime ) {
      // Couverts expects dates to be in 15 minute increments
      $ts = floor(time() / 900) * 900;
      $datetime = new \DateTime($ts);
    }
    set_query_var('couverts_date',$datetime);
    set_query_var('inputFields', $this->service->GetInputFields($datetime));

    get_template_part('templates/couverts/form-contact');
  }

  public function makeReservation(Reservation $reservation)
  {
    list($reservation,$response) = $this->service->makeReservation($reservation);

    if ( isset($response->ConfirmationText)) {
      $response->status  = 'ok';
      $response->message = $response->ConfirmationText->{couverts_language()};
    } else {
      $response->status  = 'error';
    }
    return array('reservation' => $reservation, 'response' => $response);
  }

  public function getForm()
  {
    add_action('wp_footer',array($this,'addFormHandling'),100);

    get_template_part('templates/couverts/form-html');
  }

  public function addFormHandling()
  {
    // Maybe this should be an enqueue, but this works fine for now
    get_template_part('templates/couverts/form-js');
  }

}