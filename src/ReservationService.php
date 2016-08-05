<?php
/*
 * @author: petereussen
 * @package: lakes
 */

namespace HarperJones\Couverts;


class ReservationService
{
  protected $info;
  protected $service;


  public function __construct(ReservationAPI $service)
  {
    $this->service = $service;
    $this->info    = $this->service->getBasicInfo();
  }

  public function getBasicInfo()
  {
    return $this->info;
  }

  public function getAvailableTimeslots($date,$party)
  {
    $date  = new \DateTime($date);
    $reply = $this->service->getAvailableTimes($date,$party);

    if ( !isset($reply->Times) ) {
      $reply = new \stdClass();
      $reply->Times            = array();
      $reply->NoTimesAvailable = true;
    }
    return $reply;
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
    get_template_part('templates/couverts/form-js');
  }
}