<?php

namespace HarperJones\Couverts;

/**
 * Couverts Reservation API interface.
 * Based on the works provided by Couverts (@see https://github.com/couverts/php_example_reservationapi)
 * but converted to use CURL instead of url_fopen, which is restricted on some
 * systems.
 *
 * @package HarperJones\Couverts
 */
class ReservationAPI
{
  /**
   * The Base URL to use for the API (so you can switch between test and production)
   * (defaults to test)
   * @var string
   */
  private $_baseUrl;

  /**
   * The Couverts API key
   * @var
   */
  private $_apiKey;

  /**
   * The Couverts Restaurant ID
   * @var string
   */
  private $_restaurantId;

  /**
   * The Couverts language to use (Dutch or English)
   * @var string
   */
  private $_language;

  /**
   * Instantiaties the API
   * @param string $restaurantId
   * @param string $apiKey
   * @param string $language
   * @param string $url
   */
  public function __construct($restaurantId, $apiKey, $language = 'Dutch', $url = 'https://api.testing.couverts.nl')
  {
    $this->_restaurantId = $restaurantId;
    $this->_language     = $language;
    $this->_apiKey       = $apiKey;
    $this->_baseUrl      = $url;
  }

  /**
   * Returns the basic information for a restaurant
   *
   * @return object
   */
  public function getBasicInfo()
  {
    // Sometimes the API needs to be "booted" so it seems, causing quote long loading times.
    // If you feel you are sufforing from this on your site. It maybe wise to set the
    // COUVERTS_CACHE_TIMEOUT setting to a higher value. It now defaults to 5 minutes
    $response = get_transient('drc-couverts-basic-info');

    if ( $response !== false ) {
      return $response;
    }

    $url      = sprintf('%s/BasicInfo', $this->_baseUrl);
    $response = $this->_request($url);

    set_transient('drc-couverts-basic-info',$response, Config::get('COUVERTS_CACHE_TIMEOUT',300));
    return $response;
  }

  public function getDateConfig(\DateTime $date)
  {
    $url = sprintf(
      "%s/configforday?year=%d&month=%d&day=%d",
      $this->_baseUrl,
      $date->format("Y"),
      $date->format("m"),
      $date->format("d")
    );

    return $this->_request($url);
  }

  /**
   * Get available times for a reservation on a specific date
   *
   * @param \DateTime $date
   * @param int $numPersons
   * @return object
   * @throws NoTimeAvailableException
   */
  public function getAvailableTimes(\DateTime $date, $numPersons)
  {
    $url = sprintf(
      '%s/AvailableTimes?numPersons=%d&year=%d&month=%d&day=%d',
      $this->_baseUrl,
      $numPersons,
      $date->format("Y"),
      $date->format("m"),
      $date->format("d")
    );

    $response = $this->_request($url);

    if ($response->NoTimesAvailable) {
      throw new NoTimeAvailableException($response->NoTimesAvailable->Reason . ": " . $response->NoTimesAvailable->Message->{$this->_language});
    }

    return $response;
  }

  /**
   * Get the reservation contact fields that should be displayed
   *
   * @param \DateTime $dateTime
   * @return object
   */
  public function getInputFields(\DateTime $dateTime)
  {
    $url = sprintf(
      '%s/InputFields?year=%d&month=%d&day=%d&hours=%d&minutes=%d',
      $this->_baseUrl,
      $dateTime->format("Y"),
      $dateTime->format("m"),
      $dateTime->format("d"),
      $dateTime->format("H"),
      $dateTime->format("i")
    );
    return $this->_request($url);
  }

  /**
   * Request the reservation for a specific time/date & number of persons
   * @param Reservation $reservation
   * @return array
   */
  public function makeReservation(Reservation $reservation)
  {
    $_reservation = $reservation->toArray();
    $url = sprintf('%s/Reservation', $this->_baseUrl);
    $response = $this->_request($url,$_reservation);

    return array($_reservation, $response);
  }

  /**
   * Calls the API and parses the response
   *
   * @param $url
   * @param null|mixed $payload
   * @return array|mixed|object
   * @throws \InvalidArgumentException
   */
  private function _request($url,$payload = null)
  {
    if ( $payload ) {
      $payload = json_encode($payload);
    }

    $ch = curl_init($url);
    curl_setopt_array(
      $ch,
      array(
        CURLOPT_HTTPHEADER => array(
          "Authorization: Basic " . base64_encode($this->_restaurantId . ":" . $this->_apiKey),
          "Content-Type: application/json",
        ),
        CURLOPT_RETURNTRANSFER => true,
      )
    );

    // If we have a payload, we need to POST, otherwise we GET
    if ( $payload ) {
      curl_setopt($ch,CURLOPT_POST,true);
      curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);
    }
    $reply = curl_exec($ch);

    if ( curl_errno($ch) != 0 ) {
      throw new \InvalidArgumentException(curl_error($ch),curl_errno($ch));
    }
    curl_close($ch);
    return json_decode($reply);
  }

}