<?php
/**
  staticred Text Utilities
  
  Author: Darren James Harkness (darren@staticred.com)
    
  The staticred Text Utilities are a suite of text-manipulation and analysis
  methods.
    
  License

  The code in this library is licensed under Creative Commons Attribution-
  Sharealike 3.0 Unported (CC-BY-SA 3.0).
  
  http://creativecommons.org/licenses/by-sa/3.0/
  
  You are free to share and remix this code, and make commercial use of it, 
  provided you attribute the work to the original author, and redistribute
  your changes under the same or similar license. 
  
  Requirements
  
  The following is required to use this library
  
    PHP 5.x
  
*/

class srTextUtil {

  // input attributes
  public $content = "";
  
  // output attributes
  public $readtime;
  public $wordcount = 0;
  
  
  /**
    * Analyzes supplied content and returns a reading time in minutes and 
    * seconds.
    *
    * Builds an array of results and sets the readtime attribute:
    *
    *   minutes - integer reading time in minutes (e.g., 5 minutes)
    *   seconds - carryover reading time in seconds (e.g., 400 seconds)
    *   natural - reading time in natural language (e.g., 2 1/2 minutes)
    *   decimal - reading time in decimal (e.g., 5.25 minutes)
    *
    * @param none
    * @return none
    */    
  public function readtime() {
    if (!isset($this->wordcount) || $this->wordcount == 0) {
      $this->countwords();
    }
    
    $minutes = floor($this->wordcount / (200/60) / 60);
    $seconds = floor($this->wordcount / (200/60)) - ($minutes * 60);
    
    $est = '';
    
    // build string for natural language reading estimate
    if ($minutes > 0) {
      $est .= $minutes;
    }    
    if ($seconds > 15 && $seconds < 30) {
      $est .= "&frac14;"
    }
    if ($seconds > 30 && $seconds < 45) {
      $est .= "&frac12;"
    }
    if ($seconds > 45 && $seconds < 60) {
      $est .= "&frac34;"
    }
    
    // determine reading time in decimal, rounded to 2 decimal digits

    $this->readtime['minutes'] = $minutes;
    $this->readtime['seconds'] = $seconds;
    $this->readtime['natural'] = $est;
    $this->readtime['decimal'] = $minutes + round($seconds,2)  
  }

  /**
    * Returns wordcount of supplied content.
    *
    * @param none;
    * @return none;
    */  
  public function countwords() {
    $this->wordcount = str_word_count(strip_tags($this->content));
  }

  /** 
    * pulls links out of text and provides links and text offset as arrays
    *
    * Array structure:
    *   
    *   array(3) {
    *     [0]=>
    *     array(2) {
    *       [0]=>
    *       string(20) "http://staticred.com"
    *       [1]=>
    *       int(18)
    *     }
    *     [1]=>
    *     array(2) {
    *       [0]=>
    *       string(19) "ftp://staticred.net"
    *       [1]=>
    *       int(43)
    *     }
    *     [2]=>
    *     array(2) {
    *       [0]=>
    *       string(20) "https://linkedin.com"
    *       [1]=>
    *       int(92)
    *     }
    *   }

    *
    * @param none
    * @return mixed  array if links found, false if no links found
    */  
  public function getlinks() {
    if (isset($this->content)) {
      $matches = array();
      // Regex supplied from http://forums.phpfreaks.com/topic/151507-solved-extract-url-from-string/?p=795890
      if (preg_match_all('$\b(https?|ftp|file)://[-A-Z0-9+&@#/%?=~_|!:,.;]*[-A-Z0-9+&@#/%=~_|]$i' , $this->content , $matches, PREG_OFFSET_CAPTURE)) {
        return $matches[0];
      } else {
        return FALSE;
      }
    } else {
      return FALSE;
    }
  }
  
  
}
