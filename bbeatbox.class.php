<?php
/**
* BBeatBox is a set of general functions, usually for text handling, made for personal use.
* 
* Usually there are functions to be used in ADITION with frameworks functions.
* 
* @package    BTooLs
* @copyright  Copyright (c) 2007 through 2012+, Georgescu Adrian (B3aT)
* @license    http://www.gnu.org/licenses/gpl.txt     GPL 
* @author     B.Georgescu Adrian (B3aT) <btools@gmail.com>
* @version    0.4 2011-11-18
* @link       http://btools.eu
* @source     https://github.com/BTooLs/BBeatBox-PHP   
*/
class BBeatBox{
	
	/**
	* Most from validtion used, and in contact forms:     
	* @param string $email
	* @param bool $check_dns_domain
	*/
	public static function isValidEmail($email,$check_dns_domain=false)
	{
		$valid = false;
		
		if (preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/',$email))
			$valid = true;
		
		//if active domain check is on
		if ($check_dns_domain AND $valid)
			{
				
			}
		return $valid;
	}
	
	 /**
	 * Returns in bytes the maxim upload size, from your PHP.ini active configuration
	 * @return int Bytes
	 */
	public static function getPhpMaxUploadSize()
	 {                    
	     $php_post_max_size = (int)ini_get('post_max_size')*1024*1024; //is in MB
	     $php_memory_limit = (int)ini_get('memory_limit')*1024*1024;//in MB 
	     $php_max_filesize = (int)ini_get('upload_max_filesize')*1024*1024;//also in MB
	     return min(array($php_post_max_size,$php_memory_limit,$php_post_max_size));
	     
	 }
	 
	 /**
	* Function for the smart_size mode. 
	* This function will never die ! :) An extension from http://us2.php.net/manual/en/function.memory-get-usage.php#96280
	* @param int $size  The filesize in Bytes
	* @param mixed $decimals  Round to how many decimals (the final result) usual 1 or 0
	*/
	public static function transformBytes($size, $decimals = 1) {
	    $suffix = array('Bytes','KB','MB','GB','TB','PB','EB','ZB','YB','NB','DB');
	    $i = 0;
	    $size = (int)$size;
	    $decimals = (int)$decimals;
	    
	    while ($size >= 1024 && ($i < count($suffix) - 1)){
	    $size /= 1024;
	    $i++;
	    }
	return round($size, $decimals).' '.$suffix[$i];
	}  
	
	/**
	* Simple {cycle} replacement from Smarty.
	* 
	* @param array $arr  Array of strings, or numbers, NOT empty
	* @return string Next value in array.
	*/
	public static function arrCycle(&$arr)
	{
		$v = next($arr);
		if ($v === false)
			return reset($arr);
		return $v;
	}
	/**
	* transform_array() - Function made by Mirel Mitache, in MPF PHP Framework.
	* Transforms array after given rule. ex($rule = array('id'=>'name')) 
	* returns an array with key = id value from original array and 
	* value = name value from original array
	* @param array $array
	* @param mixed $rule
	* @return
	*/
	public static function transform_array($array, $rule){
		if (is_array($rule)){
			$result = array();
			foreach ($rule as $k=>$v){
				if (!is_array($v)){
					foreach ($array as $a){
						$v1 = explode(',', $v);
						if (count($v1) > 1){
							$res =  array();
							foreach ($v1 as $va){
								$res[trim($va)] = $a[trim($va)];
							}
							$result[$a[$k]] = $res;
						} else {
							$result[$a[$k]] = $a[$v1[0]];
						}
					}
				}
			}
			return $result;
		} elseif(is_string($rule)){
			$result = array();
			$fields = explode(',', $rule);
			foreach ($array as $a){
				if (count($fields) > 1){
					$r = array();
					foreach ($fields as $f){
						$r[trim($f)] = $a[trim($f)];
					}
				} else {
					$r = $a[trim($fields[0])];
				}
				$result[] = $r;
			}
			return $result;
		}
	}
		
		/**
		* Calculate the execution time of the script. 
		* @example executionTime();//at your website start script
		* @example executionTime(false);//will output the extecution time, at your scripts end
		* 
		* @param bool $start At end use false
		* @param bool $echo  Will echo the text : Generated in x.xxxx seconds.
		* @param bool $decimals  Decimals used in execution time
		* @return float Returns the time (timestamp) at start and end ..
		*/
		public static function executionTime($start=true,$echo=true,$decimals=4,$request_time=false)
		{
			//if is the start ..store into session
			if ($start)
			{            
				 $time = microtime();
			     $time = explode(' ', $time);
			     $time = $time[1] + $time[0];
			     $_SESSION['BTOOLS_SESSION_START_TIME'] = $time;
			     return $time;
			     //$start = $time;
			}
			elseif(isset($_SESSION['BTOOLS_SESSION_START_TIME']))
			{
				$time = microtime();
				$time = explode(' ', $time);
				$time = $time[1] + $time[0];
				//$finish = $time;
				$total_time = round(($time - (int)$_SESSION['BTOOLS_SESSION_START_TIME']), $decimals);
				if ($echo) echo PHP_EOL.'<p>Page generated in '.$total_time.' seconds.</p>'.PHP_EOL;
				return $total_time;
			}
			elseif($request_time)
			{
				$time = microtime();
				$time = explode(' ', $time);
				$time = $time[1] + $time[0];
				//$finish = $time;
				$total_time = round(($time - (int)$_SERVER['REQUEST_TIME']), $decimals);
				if ($echo) echo PHP_EOL.'<p>Page generated in '.$total_time.' seconds.</p>'.PHP_EOL;
				return $total_time;
			}
		}
		
		/**
		* Calculate in a nice human way, the difference between two dates (x months ago)
		* 
		* @param timestamp/datetime $date  Start interval
		* @param int $granularity How specific you want the answer (the biggest x periods will be shown)
		* @param false/timestamp/datetime $to_date If you don't want NOW, write a custom end interval
		* @param false/array $translate_periods false for english or associative array to translate : 
		* 		$translate_periods = array('decade'=>'decada','year'=>'an','month'=>'luna', etc)
		* @param false/array $translate_periods_plural Same as previous, but with plural translated versions
		* @param false/int $max_days_to_diff If the difference between dates is bigger then x days, the returned values is NOT 
		*   y time ago, is the date (M d, Y)
		* @return string
		*/
		function timeAgo($date,$granularity=2,$to_date=false,$translate_periods=false,$translate_periods_plural=false,$max_days_to_diff=false) 
		{
			if (is_numeric($date) === false) $date = strtotime($date);
			
			//specific time difference
			if ($to_date === false)
				$difference = time() - $date;  //normal usage, difference from $date to NOW
			elseif (is_numeric($to_date))
				{
					$difference = $to_date - $date;  //difference from $date to $to_date (when $to_date is a timestamp) 
				}
			elseif (is_string($to_date))
				$difference = strtotime($to_date) - $date; //difference from $date to $to_date (when $to_date is a datetime)    
			
			if ($difference < 1) return 0;
			
			//if the max is set, and active
			if ($max_days_to_diff AND ((((int)$max_days_to_diff)*86400) < $difference))
			{   
				return date('M d, Y',$date); ;
			}
			
			$periods = array(
					'decade' => 315360000,
			        'year' => 31536000,
			        'month' => 2628000,
			        'week' => 604800, 
			        'day' => 86400,
			        'hour' => 3600,
			        'minute' => 60,
			        'second' => 1);
		 $retval = '';                                
			foreach ($periods as $key => $value) {
				if ($difference >= $value) {
					$time = floor($difference/$value);
					$difference %= $value;
					$retval .= ($retval ? ' ' : '').$time.' ';
					if (!($translate_periods) OR !($translate_periods_plural))
						$retval .= (($time > 1) ? $key.'s' : $key);
					else
						$retval .= (($time > 1) ? $translate_periods_plural[$key] : $translate_periods[$key]);
						
					$granularity--;
				}
				if ($granularity == '0') { break; }
			}
			return $retval;      
		}
		
		
		
	/** Masks a text (ex email address - bto...@...ail.com)
	 * @param string $str The string we want to mask
	 * @param int $start How many characters we want to keep at start (absolute OR percent if $fixed=true)
	 * @param int $end How many characters to keep at end (absolute OR percent if $fixed=true).
	 * 	If set to false and $middle <> false the text after middle will NOT be masked.
	 * @param string|bool $middle The middle character we want to keep and calcule the percents. False will ignore it, just mask entire text.
	 * @param bool $fixed If the $start and $end values are in absolute (number of characters). If you use perecents set false.
	 * @param string $mask The text we will mask it.
	*/
	public static function maskText($str,$start=3,$end=6,$middle="@",$fixed=true,$mask='..')
	{
		if ($fixed)
		{
			$mp = strpos($str,$middle);//middle position
			//if the middle is set, but not found ..
			if (false !== $middle AND false === $mp)
			{
				$middle = null;
			}
			//if we want all the start
			if (false === $start AND null !== $middle)
			{
				$sr = substr($str,0,$mp);//start part of the result
			}
			else//we want only x characters from start
			{
				$sr = substr($str,0,(($start)?$start:3)).$mask;
			}
			//we want all the end
			if (false === $end AND null !== $middle)
			{
				$er = substr($str,$mp+1);//end part of the result 
			}
			else//only x characters from the end
			{
				$er = $mask.substr($str,(0-(($end)?$end:3)));
			}
			
			return $sr.$middle.$er;
		}
	}
		
}//end class
return;