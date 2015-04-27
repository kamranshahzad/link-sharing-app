<?php
/**
 * TDateTimeStamp class file.
 *
 * COPYRIGHT
 * (c) 2003-2005 John Lim and released under BSD-style license except for code by
 * jackbbs, which includes getTimeStamp, getGMTDiff, isLeapYear
 * and originally found at http://www.php.net/manual/en/function.mktime.php
 *
 * @author Wei Zhuo <weizhuo[at]gamil[dot]com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2008 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @version $Id: TDateTimeStamp.php 2488 2008-08-06 01:34:06Z knut $
 * @package System.Util
 */

/*
	This code was originally for windows. But apparently this problem happens
	also with Linux, RH 7.3 and later!

	glibc-2.2.5-34 and greater has been changed to return -1 for dates <
	1970.  This used to work.  The problem exists with RedHat 7.3 and 8.0
	echo (mktime(0, 0, 0, 1, 1, 1960));  // prints -1

	References:
	 http://bugs.php.net/bug.php?id=20048&edit=2
	 http://lists.debian.org/debian-glibc/2002/debian-glibc-200205/msg00010.html
*/

if (!defined('ADODB_ALLOW_NEGATIVE_TS')
	&& !defined('ADODB_NO_NEGATIVE_TS')) define('ADODB_NO_NEGATIVE_TS',1);

/**
 * TDateTimeStamp Class
 *
 * TDateTimeStamp Class is adpated from the ADOdb Date Library,
 * part of the ADOdb abstraction library
 * Download: http://phplens.com/phpeverywhere/
 *
 * http://phplens.com/phpeverywhere/adodb_date_library
 *
 * PHP native date functions use integer timestamps for computations.
 * Because of this, dates are restricted to the years 1901-2038 on Unix
 * and 1970-2038 on Windows due to integer overflow for dates beyond
 * those years. This library overcomes these limitations by replacing the
 * native function's signed integers (normally 32-bits) with PHP floating
 * point numbers (normally 64-bits).
 *
 * Dates from 100 A.D. to 3000 A.D. and later have been tested. The minimum
 * is 100 A.D. as <100 will invoke the 2 => 4 digit year conversion.
 * The maximum is billions of years in the future, but this is a theoretical
 * limit as the computation of that year would take too long with the
 * current implementation of {@link getTimeStamp}.
 *
 * PERFORMANCE
 * For high speed, this library uses the native date functions where
 * possible, and only switches to PHP code when the dates fall outside
 * the 32-bit signed integer range.
 *
 * @author Wei Zhuo <weizhuo[at]gmail[dot]com>
 * @version $Id: TDateTimeStamp.php 2488 2008-08-06 01:34:06Z knut $
 * @package System.Util
 * @since 3.0.4
 */
class TDateTimeStamp
{
	protected static $_month_normal = array("",31,28,31,30,31,30,31,31,30,31,30,31);
	protected static $_month_leaf = array("",31,29,31,30,31,30,31,31,30,31,30,31);

	/**
	 * Gets day of week, 0 = Sunday,... 6=Saturday.
	 * Algorithm from PEAR::Date_Calc
	 */
	public function getDayofWeek($year, $month, $day)
	{
		/*
		Pope Gregory removed 10 days - October 5 to October 14 - from the year 1582 and
		proclaimed that from that time onwards 3 days would be dropped from the calendar
		every 400 years.

		Thursday, October 4, 1582 (Julian) was followed immediately by Friday, October 15, 1582 (Gregorian).
		*/
		if ($year <= 1582)
		{
			if ($year < 1582 ||
				($year == 1582 && ($month < 10 || ($month == 10 && $day < 15))))
			{
				$greg_correction = 3;
			}
			else
			{
				$greg_correction = 0;
			}
		}
		else
		{
			$greg_correction = 0;
		}

		if($month > 2)
		    $month -= 2;
		else
		{
		    $month += 10;
		    $year--;
		}

		$day =  floor((13 * $month - 1) / 5) +
		        $day + ($year % 100) +
		        floor(($year % 100) / 4) +
		        floor(($year / 100) / 4) - 2 *
		        floor($year / 100) + 77 + $greg_correction;

		return $day - 7 * floor($day / 7);
	}

	/**
	 * Checks for leap year, returns true if it is. No 2-digit year check. Also
	 * handles julian calendar correctly.
	 * @param float year to check
	 * @return boolean true if is leap year
	 */
	public function isLeapYear($year)
	{
		$year = $this->digitCheck($year);
		if ($year % 4 != 0)
			return false;

		if ($year % 400 == 0)
			return true;
		// if gregorian calendar (>1582), century not-divisible by 400 is not leap
		else if ($year > 1582 && $year % 100 == 0 )
			return false;
		return true;
	}

	/**
	 * Fix 2-digit years. Works for any century.
	 * Assumes that if 2-digit is more than 30 years in future, then previous century.
	 * @return integer change two digit year into multiple digits
	 */
	protected function digitCheck($y)
	{
		if ($y < 100){
			$yr = (integer) date("Y");
			$century = (integer) ($yr /100);

			if ($yr%100 > 50) {
				$c1 = $century + 1;
				$c0 = $century;
			} else {
				$c1 = $century;
				$c0 = $century - 1;
			}
			$c1 *= 100;
			// if 2-digit year is less than 30 years in future, set it to this century
			// otherwise if more than 30 years in future, then we set 2-digit year to the prev century.
			if (($y + $c1) < $yr+30) $y = $y + $c1;
			else $y = $y + $c0*100;
		}
		return $y;
	}

	public function get4DigitYear($y)
	{
		return $this->digitCheck($y);
	}

	/**
	 * @return integer get local time zone offset from GMT
	 */
	public function getGMTDiff()
	{
		static $TZ;
		if (isset($TZ)) return $TZ;

		$TZ = mktime(0,0,0,1,2,1970) - gmmktime(0,0,0,1,2,1970);
		return $TZ;
	}

	/**
	 * @return array an array with date info.
	 */
	function getDate($d=false,$fast=false)
	{
		if ($d === false) return getdate();
		// check if number in 32-bit signed range
		if ((abs($d) <= 0x7FFFFFFF))
		{
			if (!defined('ADODB_NO_NEGATIVE_TS') || $d >= 0) // if windows, must be +ve integer
				return @getdate($d);
		}
		return $this->_getDateInternal($d);
	}



	/**
	 * Low-level function that returns the getdate() array. We have a special
	 * $fast flag, which if set to true, will return fewer array values,
	 * and is much faster as it does not calculate dow, etc.
	 * @param float original date
	 * @param boolean false to compute the day of the week, default is true
	 * @param boolean true to calculate the GMT dates
	 * @return array an array with date info.
	 */
	protected function _getDateInternal($origd=false,$fast=true,$is_gmt=false)
	{
		static $YRS;

		$d =  $origd - ($is_gmt ? 0 : $this->getGMTDiff());

		$_day_power = 86400;
		$_hour_power = 3600;
		$_min_power = 60;

		if ($d < -12219321600)
			$d -= 86400*10; // if 15 Oct 1582 or earlier, gregorian correction

		$_month_table_normal =& self::$_month_normal;
		$_month_table_leaf = & self::$_month_leaf;

		$d366 = $_day_power * 366;
		$d365 = $_day_power * 365;

		if ($d < 0)
		{
			if (empty($YRS))
				$YRS = array(
					1970 => 0,
					1960 => -315619200,
					1950 => -631152000,
					1940 => -946771200,
					1930 => -1262304000,
					1920 => -1577923200,
					1910 => -1893456000,
					1900 => -2208988800,
					1890 => -2524521600,
					1880 => -2840140800,
					1870 => -3155673600,
					1860 => -3471292800,
					1850 => -3786825600,
					1840 => -4102444800,
					1830 => -4417977600,
					1820 => -4733596800,
					1810 => -5049129600,
					1800 => -5364662400,
					1790 => -5680195200,
					1780 => -5995814400,
					1770 => -6311347200,
					1760 => -6626966400,
					1750 => -6942499200,
					1740 => -7258118400,
					1730 => -7573651200,
					1720 => -7889270400,
					1710 => -8204803200,
					1700 => -8520336000,
					1690 => -8835868800,
					1680 => -9151488000,
					1670 => -9467020800,
					1660 => -9782640000,
					1650 => -10098172800,
					1640 => -10413792000,
					1630 => -10729324800,
					1620 => -11044944000,
					1610 => -11360476800,
					1600 => -11676096000);

			if ($is_gmt)
				$origd = $d;
			// The valid range of a 32bit signed timestamp is typically from
			// Fri, 13 Dec 1901 20:45:54 GMT to Tue, 19 Jan 2038 03:14:07 GMT
			//

			# old algorithm iterates through all years. new algorithm does it in
			# 10 year blocks

			/*
			# old algo
			for ($a = 1970 ; --$a >= 0;) {
				$lastd = $d;

				if ($leaf = _adodb_is_leap_year($a)) $d += $d366;
				else $d += $d365;

				if ($d >= 0) {
					$year = $a;
					break;
				}
			}
			*/

			$lastsecs = 0;
			$lastyear = 1970;
			foreach($YRS as $year => $secs)
			{
				if ($d >= $secs)
				{
					$a = $lastyear;
					break;
				}
				$lastsecs = $secs;
				$lastyear = $year;
			}

			$d -= $lastsecs;
			if (!isset($a)) $a = $lastyear;

			//echo ' yr=',$a,' ', $d,'.';

			for (; --$a >= 0;)
			{
				$lastd = $d;

				if ($leaf = $this->isLeapYear($a))
					$d += $d366;
				else
					$d += $d365;

				if ($d >= 0)
				{
					$year = $a;
					break;
				}
			}
			/**/

			$secsInYear = 86400 * ($leaf ? 366 : 365) + $lastd;

			$d = $lastd;
			$mtab = ($leaf) ? $_month_table_leaf : $_month_table_normal;
			for ($a = 13 ; --$a > 0;)
			{
				$lastd = $d;
				$d += $mtab[$a] * $_day_power;
				if ($d >= 0)
				{
					$month = $a;
					$ndays = $mtab[$a];
					break;
				}
			}

			$d = $lastd;
			$day = $ndays + ceil(($d+1) / ($_day_power));

			$d += ($ndays - $day+1)* $_day_power;
			$hour = floor($d/$_hour_power);

		} else {
			for ($a = 1970 ;; $a++)
			{
				$lastd = $d;

				if ($leaf = $this->isLeapYear($a)) $d -= $d366;
				else $d -= $d365;
				if ($d < 0)
				{
					$year = $a;
					break;
				}
			}
			$secsInYear = $lastd;
			$d = $lastd;
			$mtab = ($leaf) ? $_month_table_leaf : $_month_table_normal;
			for ($a = 1 ; $a <= 12; $a++)
			{
				$lastd = $d;
				$d -= $mtab[$a] * $_day_power;
				if ($d < 0)
				{
					$month = $a;
					$ndays = $mtab[$a];
					break;
				}
			}
			$d = $lastd;
			$day = ceil(($d+1) / $_day_power);
			$d = $d - ($day-1) * $_day_power;
			$hour = floor($d /$_hour_power);
		}

		$d -= $hour * $_hour_power;
		$min = floor($d/$_min_power);
		$secs = $d - $min * $_min_power;
		if ($fast)
		{
			return array(
			'seconds' => $secs,
			'minutes' => $min,
			'hours' => $hour,
			'mday' => $day,
			'mon' => $month,
			'year' => $year,
			'yday' => floor($secsInYear/$_day_power),
			'leap' => $leaf,
			'ndays' => $ndays
			);
		}


		$dow = $this->getDayofWeek($year,$month,$day);

		return array(
			'seconds' => $secs,
			'minutes' => $min,
			'hours' => $hour,
			'mday' => $day,
			'wday' => $dow,
			'mon' => $month,
			'year' => $year,
			'yday' => floor($secsInYear/$_day_power),
			'weekday' => gmdate('l',$_day_power*(3+$dow)),
			'month' => gmdate('F',mktime(0,0,0,$month,2,1971)),
			0 => $origd
		);
	}

	/**
	 * @return boolean true if valid date, semantic check only.
	 */
	public function isValidDate($y,$m,$d)
	{
		if ($this->isLeapYear($y))
			$marr =& self::$_month_leaf;
		else
			$marr =& self::$_month_normal;

		if ($m > 12 || $m < 1) return false;

		if ($d > 31 || $d < 1) return false;

		if ($marr[$m] < $d) return false;

		if ($y < 1000 && $y > 3000) return false;

		return true;
	}

	/**
	 * @return string formatted date based on timestamp $d
	 */
	function formatDate($fmt,$d=false,$is_gmt=false)
	{
		if ($d === false)
			return ($is_gmt)? @gmdate($fmt): @date($fmt);

		// check if number in 32-bit signed range
		if ((abs($d) <= 0x7FFFFFFF))
		{
			// if windows, must be +ve integer
			if (!defined('ADODB_NO_NEGATIVE_TS') || $d >= 0)
				return ($is_gmt)? @gmdate($fmt,$d): @date($fmt,$d);
		}

		$_day_power = 86400;

		$arr = $this->getDate($d,true,$is_gmt);

		$year = $arr['year'];
		$month = $arr['mon'];
		$day = $arr['mday'];
		$hour = $arr['hours'];
		$min = $arr['minutes'];
		$secs = $arr['seconds'];

		$max = strlen($fmt);
		$dates = '';

		$isphp5 = PHP_VERSION >= 5;

		/*
			at this point, we have the following integer vars to manipulate:
			$year, $month, $day, $hour, $min, $secs
		*/
		for ($i=0; $i < $max; $i++)
		{
			switch($fmt[$i])
			{
			case 'T': $dates .= date('T');break;
			// YEAR
			case 'L': $dates .= $arr['leap'] ? '1' : '0'; break;
			case 'r': // Thu, 21 Dec 2000 16:01:07 +0200

				// 4.3.11 uses '04 Jun 2004'
				// 4.3.8 uses  ' 4 Jun 2004'
				$dates .= gmdate('D',$_day_power*(3+$this->getDayOfWeek($year,$month,$day))).', '
					. ($day<10?'0'.$day:$day) . ' '.date('M',mktime(0,0,0,$month,2,1971)).' '.$year.' ';

				if ($hour < 10) $dates .= '0'.$hour; else $dates .= $hour;

				if ($min < 10) $dates .= ':0'.$min; else $dates .= ':'.$min;

				if ($secs < 10) $dates .= ':0'.$secs; else $dates .= ':'.$secs;

				$gmt = $this->getGMTDiff();
				if ($isphp5)
					$dates .= sprintf(' %s%04d',($gmt<=0)?'+':'-',abs($gmt)/36);
				else
					$dates .= sprintf(' %s%04d',($gmt<0)?'+':'-',abs($gmt)/36);
				break;

			case 'Y': $dates .= $year; break;
			case 'y': $dates .= substr($year,strlen($year)-2,2); break;
			// MONTH
			case 'm': if ($month<10) $dates .= '0'.$month; else $dates .= $month; break;
			case 'Q': $dates .= ($month+3)>>2; break;
			case 'n': $dates .= $month; break;
			case 'M': $dates .= date('M',mktime(0,0,0,$month,2,1971)); break;
			case 'F': $dates .= date('F',mktime(0,0,0,$month,2,1971)); break;
			// DAY
			case 't': $dates .= $arr['ndays']; break;
			case 'z': $dates .= $arr['yday']; break;
			case 'w': $dates .= $this->getDayOfWeek($year,$month,$day); break;
			case 'l': $dates .= gmdate('l',$_day_power*(3+$this->getDayOfWeek($year,$month,$day))); break;
			case 'D': $dates .= gmdate('D',$_day_power*(3+$this->getDayOfWeek($year,$month,$day))); break;
			case 'j': $dates .= $day; break;
			case 'd': if ($day<10) $dates .= '0'.$day; else $dates .= $day; break;
			case 'S':
				$d10 = $day % 10;
				if ($d10 == 1) $dates .= 'st';
				else if ($d10 == 2 && $day != 12) $dates .= 'nd';
				else if ($d10 == 3) $dates .= 'rd';
				else $dates .= 'th';
				break;

			// HOUR
			case 'Z':
				$dates .= ($is_gmt) ? 0 : -$this->getGMTDiff(); break;
			case 'O':
				$gmt = ($is_gmt) ? 0 : $this->getGMTDiff();

				if ($isphp5)
					$dates .= sprintf('%s%04d',($gmt<=0)?'+':'-',abs($gmt)/36);
				else
					$dates .= sprintf('%s%04d',($gmt<0)?'+':'-',abs($gmt)/36);
				break;

			case 'H':
				if ($hour < 10) $dates .= '0'.$hour;
				else $dates .= $hour;
				break;
			case 'h':
				if ($hour > 12) $hh = $hour - 12;
				else {
					if ($hour == 0) $hh = '12';
					else $hh = $hour;
				}

				if ($hh < 10) $dates .= '0'.$hh;
				else $dates .= $hh;
				break;

			case 'G':
				$dates .= $hour;
				break;

			case 'g':
				if ($hour > 12) $hh = $hour - 12;
				else {
					if ($hour == 0) $hh = '12';
					else $hh = $hour;
				}
				$dates .= $hh;
				break;
			// MINUTES
			case 'i': if ($min < 10) $dates .= '0'.$min; else $dates .= $min; break;
			// SECONDS
			case 'U': $dates .= $d; break;
			case 's': if ($secs < 10) $dates .= '0'.$secs; else $dates .= $secs; break;
			// AM/PM
			// Note 00:00 to 11:59 is AM, while 12:00 to 23:59 is PM
			case 'a':
				if ($hour>=12) $dates .= 'pm';
				else $dates .= 'am';
				break;
			case 'A':
				if ($hour>=12) $dates .= 'PM';
				else $dates .= 'AM';
				break;
			default:
				$dates .= $fmt[$i]; break;
			// ESCAPE
			case "\\":
				$i++;
				if ($i < $max) $dates .= $fmt[$i];
				break;
			}
		}
		return $dates;
	}

	/**
	 * Not a very fast algorithm - O(n) operation. Could be optimized to O(1).
	 * @return integer|float a timestamp given a local time. Originally by jackbbs.
     */
	function getTimeStamp($hr,$min,$sec,$mon=false,$day=false,$year=false,$is_gmt=false)
	{

		if ($mon === false)
			return $is_gmt? @gmmktime($hr,$min,$sec): @mktime($hr,$min,$sec);

		// for windows, we don't check 1970 because with timezone differences,
		// 1 Jan 1970 could generate negative timestamp, which is illegal
		if (1971 <= $year && $year < 2038 || !defined('ADODB_NO_NEGATIVE_TS')
			&& (1901 < $year && $year < 2038))
		{
			return $is_gmt ? @gmmktime($hr,$min,$sec,$mon,$day,$year)
						    : @mktime($hr,$min,$sec,$mon,$day,$year);
		}

		$gmt_different = ($is_gmt) ? 0 : $this->getGMTDiff();

		/*
		# disabled because some people place large values in $sec.
		# however we need it for $mon because we use an array...
		$hr = intval($hr);
		$min = intval($min);
		$sec = intval($sec);
		*/
		$mon = (int)$mon;
		$day = (int)$day;
		$year = (int)$year;


		$year = $this->digitCheck($year);

		if ($mon > 12)
		{
			$y = floor($mon / 12);
			$year += $y;
			$mon -= $y*12;
		}
		else if ($mon < 1)
		{
			$y = ceil((1-$mon) / 12);
			$year -= $y;
			$mon += $y*12;
		}

		$_day_power = 86400;
		$_hour_power = 3600;
		$_min_power = 60;

		$_month_table_normal = & self::$_month_normal;
		$_month_table_leaf = & self::$_month_leaf;

		$_total_date = 0;
		if ($year >= 1970)
		{
			for ($a = 1970 ; $a <= $year; $a++)
			{
				$leaf = $this->isLeapYear($a);
				if ($leaf == true) {
					$loop_table = $_month_table_leaf;
					$_add_date = 366;
				} else {
					$loop_table = $_month_table_normal;
					$_add_date = 365;
				}
				if ($a < $year) {
					$_total_date += $_add_date;
				} else {
					for($b=1;$b<$mon;$b++) {
						$_total_date += $loop_table[$b];
					}
				}
			}
			$_total_date +=$day-1;
			$ret = $_total_date * $_day_power + $hr * $_hour_power + $min * $_min_power + $sec + $gmt_different;

		} else {
			for ($a = 1969 ; $a >= $year; $a--) {
				$leaf = $this->isLeapYear($a);
				if ($leaf == true) {
					$loop_table = $_month_table_leaf;
					$_add_date = 366;
				} else {
					$loop_table = $_month_table_normal;
					$_add_date = 365;
				}
				if ($a > $year) { $_total_date += $_add_date;
				} else {
					for($b=12;$b>$mon;$b--) {
						$_total_date += $loop_table[$b];
					}
				}
			}
			$_total_date += $loop_table[$mon] - $day;

			$_day_time = $hr * $_hour_power + $min * $_min_power + $sec;
			$_day_time = $_day_power - $_day_time;
			$ret = -( $_total_date * $_day_power + $_day_time - $gmt_different);
			if ($ret < -12220185600) $ret += 10*86400; // if earlier than 5 Oct 1582 - gregorian correction
			else if ($ret < -12219321600) $ret = -12219321600; // if in limbo, reset to 15 Oct 1582.
		}
		//print " dmy=$day/$mon/$year $hr:$min:$sec => " .$ret;
		return $ret;
	}
}

?>
