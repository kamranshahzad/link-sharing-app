<?php

	class RelativeTime{
		
		public static function show($datetime, $seconds = false, $ago = true) {
			 
			 date_default_timezone_set("America/New_York");
			 
			 $time = time(); // Store time to ensure consistency.
			 $datetime = (is_int($datetime) && strlen($datetime) >= 1 && strlen($datetime) <= strlen($time)) ? $datetime : strtotime($datetime); // Determines if $datetime is in date format or if it's the number of seconds since the Unix Epoch.
			 $shift = $time - $datetime; // Calculates time difference in seconds.
			
			 $minute = 60;
			 $hour = 3600;
			 $day = 86400;
			 $week = 604800;
			 $month = 2592000;
			 $year = 31536000;
			 $decade = 315360000;
			 $diff2 = 0;
			 
			 if ($seconds == true) {
			  return $shift;
			 } else {
			  if ($shift < 0) { // Date in the future
			   $diff = "future";
			   $term = "event";
			  } elseif ($shift < 5) { // Less than 5 seconds
			   $diff = "just a";
			   $term = "moment";
			  } elseif ($shift < $minute) { // Less than 60 seconds
			   $diff = $shift;
			   $term = "second";
			  } elseif ($shift < $minute+30) { // Less than 1 minute, 30 seconds
			   $diff = "about a";
			   $term = "minute";
			  } elseif ($shift < $minute*3) { // Less than 3 minutes
			   $diff = "a few";
			   $term = "minutes";
			  } elseif ($shift >= $minute*30 && $shift < $minute*40) { // About half an hour
			   $diff = "about half an";
			   $term = "hour";
			  } elseif ($shift < $hour) { // Less than 60 minutes
			   $diff = floor($shift / $minute);
			   $term = "minute";
			  } elseif ($shift < 4200) { // Less than 1 hour, 10 minutes
			   $diff = "about an";
			   $term = "hour";
			  } elseif ($shift < $day) { // Less than 1 day
			   $diff = floor($shift / $hour);
			   $term = "hour";
			   $diff2 = floor($shift / $minute);
			   $term2 = "minute";
			
			   $maxdiff = $minute;
			   $modulus = $diff * $maxdiff;
			   $diff2 = $diff2 % $modulus;
			  } elseif ($shift < $week) { // Less than 1 week
			   $diff = floor($shift / $day);
			   $term = "day";
			   $diff2 = floor($shift / $hour);
			   $term2 = "hour";
			
			   $maxdiff = 24;
			   $modulus = $diff * $maxdiff;
			   $diff2 = $diff2 % $modulus;
			  } elseif ($shift < $month) { // Less than 1 month
			   $diff = floor($shift / $week);
			   $term = "week";
			   $diff2 = floor($shift / $day);
			   $term2 = "day";
			
			   $maxdiff = 7;
			   $modulus = $diff * $maxdiff;
			   $diff2 = $diff2 % $modulus;
			  } elseif ($shift < $year) { // Less than 1 year
			   $diff = floor($shift / $month);
			   $term = "month";
			   $diff2 = floor($shift / $day);
			   $term2 = "day";
			
			   $maxdiff = 30;
			   $modulus = $diff * $maxdiff;
			   $diff2 = $diff2 % $modulus;
			   if ($diff2 == 7 || $diff2 == 14 || $diff2 == 21) {
				$diff2 = $diff2 / 7;
				$term2 = "week";
			   }
			  } elseif ($shift < $decade) { // Less than 10 years
			   $diff = floor($shift / $year);
			   $term = "year";
			   $diff2 = floor($shift / $month);
			   $term2 = "month";
			
			   $maxdiff = 12;
			   $modulus = $diff * $maxdiff;
			   $diff2 = $diff2 % $modulus;
			  } elseif ($shift < $decade*3) { // Less than 30 years
			   $diff = floor($shift / $decade);
			   $term = "decade";
			   $diff2 = floor($shift / $year);
			   $term2 = "year";
			
			   $maxdiff = 10;
			   $modulus = $diff * $maxdiff;
			   $diff2 = $diff2 % $modulus;
			  } elseif ($shift >= $decade*3) { // More than 30 years
			   $diff = "many";
			   $term = "year";
			  }
			
			  if (($term == "year" && $diff == "many") || ($diff != 1 && $diff != '' && preg_match("/^[0-9]+$/", $diff))) {
			   $term .= "s";
			  }
			  if ($diff2 != 1 && $diff2 != '' && preg_match("/^[0-9]+$/", $diff2)) {
			   $term2 .= "s";
			  }
			  if ($diff != "future" && (!isset($ago) || $ago != false)) { // Appends "ago" to the end of the relative time stamp unless event is in the future.
			   $ago = " ago";
			  } else {
			   $ago = ""; // Remove "ago" for future date.
			  }
			  if (isset($diff2) && $diff2 != '' && isset($term2) && $term2 != '') {
			   return "$diff $term, $diff2 $term2$ago";
			  } else {
			   return "$diff $term$ago";
			  }
			 }
			}
		
	}//$

?>
