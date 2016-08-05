<?php
/*
 * @author: petereussen
 * @package: lakes
 */

namespace HarperJones\Couverts;

/**
 * A Reservation Request object
 *
 * @package HarperJones\Couverts
 */
class Reservation
{
  /**
   * @var \DateTime
   */
  public $datetime = null;

  /**
   * @var int
   */
  public $persons = null;

  /**
   * @var string
   */
  public $language = 'Dutch';

  /**
   * @var string
   */
  public $gender = null;

  /**
   * @var string
   */
  public $firstname = null;

  /**
   * @var string
   */
  public $lastname = null;

  /**
   * @var string
   */
  public $email = null;

  /**
   * @var string
   */
  public $phonenr = null;

  /**
   * @var string
   */
  public $postalcode = null;

  /**
   * @var \DateTime
   */
  public $birthdate = null;

  /**
   * @var string
   */
  public $comments = '';

  /**
   * @var array
   */
  public $customFields = array();

  public function __construct($datetime)
  {
    if ( ! $datetime instanceof \DateTime ) {
      $datetime = \DateTime::createFromFormat('Y-m-d H:i',$datetime);
    }

    $this->datetime = $datetime;
  }

  /**
   * Returns the date part of a specific reservation
   *
   * @return string
   */
  public function getDate()
  {
    return $this->datetime->format('Y-m-d');
  }

  /**
   * Returns the time string of a specific reservation
   *
   * @return string
   */
  public function getTime()
  {
    return $this->datetime->format('H:i');
  }

  /**
   * Converts the data to something the API can understand
   *
   * @return array
   */
  public function toArray()
  {
    $all = array(
      'NumPersons'               => $this->persons,
      'Gender'                   => $this->gender,
      'FirstName'                => $this->firstname,
      'LastName'                 => $this->lastname,
      'Email'                    => $this->email,
      'PhoneNumber'              => $this->phonenr,
      'PostalCode'               => $this->postalcode,
      'Comments'                 => $this->comments,
      'RestaurantSpecificFields' => array_map(
        function($id, $value) {
          return array("Id"=>$id, "Value"=>$value);
        }
        ,array_keys( $this->customFields )
        ,array_values( $this->customFields )
      )
    );

    $all['Date'] = $this->dateToParts($this->datetime);
    $all['Time'] = $this->timeToParts($this->datetime);

    if ( $this->birthdate ) {
      $all['birthdate'] = $this->dateToParts($this->birthdate);
    }

    return $all;
  }

  /**
   * Convert a POST request to a reservation object
   *
   * @param $post
   * @return static
   */
  static public function createFromGlobals($post)
  {
    $dt   = self::v($post,'reservation_date') . ' ' . self::v($post,'reservation_time');

    $item = new static($dt);
    $item->persons   = self::v($post,'reservation_party');
    $item->gender    = self::v($post,'gender');
    $item->firstname = self::v($post,'firstname');
    $item->lastname  = self::v($post,'lastname');
    $item->comments  = self::v($post,'comments');
    $item->email     = self::v($post,'email');
    $item->postalcode= self::v($post,'postalcode');
    $item->phonenr   = self::v($post,'phonenumber');
    $birthday        = self::v($post,'birthdate');

    if ( $birthday ) {
      $item->birthdate  = \DateTime::createFromFormat('Y-m-d',$birthday);
    }

    $item->customFields = array_filter(self::v($post,'RestaurantSpecificFields',array()));

    return $item;
  }

  /**
   * Safely get a value from a multidimensional array
   *
   * @param $array
   * @param $key
   * @param null $default
   * @return null
   */
  static private function v($array,$key,$default = null)
  {
    $keys   = explode('.',$key);
    $curKey = array_shift($keys);

    if ( isset($array[$curKey])) {
      if ( $keys ) {
        return self::v($array[$curKey],implode('.',$keys),$default);
      } else {
        return $array[$curKey];
      }
    }
    return $default;
  }

  /**
   * Converts the date to an array of its parts
   * @param \DateTime $date
   * @return array
   */
  private function dateToParts(\DateTime $date)
  {
    return array(
      'Year'  => $date->format("Y"),
      'Month' => $date->format("m"),
      'Day'   => $date->format("d")
    );
  }

  /**
   * Converts the time part to an array of its parts
   * @param \DateTime $date
   * @return array
   */
  private function timeToParts(\DateTime $datetime)
  {
    return array(
      'Hours'   => $datetime->format("H"),
      'Minutes' => $datetime->format("i")
    );
  }

}