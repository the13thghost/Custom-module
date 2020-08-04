<?php

namespace Drupal\date_block;

class DaysLeftCalc {

  // Returns the number of days left until an event start
  public function getDaysLeft($date) {
    $diff = $this->getDiffInDays($date);

    if($diff == 0) {
      return "This event is happening today.";   
    } else if($diff == 1) {
      return "1 day left until the event starts.";
    } else if($diff > 1) {
      return $diff . " days left until the event starts." ;
    } else {
      return "This event already passed.";
      }
  }
  
  // Calculates the difference in days between the current date and an event date
  public function getDiffInDays($date) {
    $now = time();
    $event_date = strtotime($date);
    $diff = $event_date - $now;
    return round($diff / (60 * 60 * 24));
  }
}