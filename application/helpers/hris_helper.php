<?php

if (!function_exists('camelToSlug')) {
	/**
	 * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
	 * @param    string   $str    String in camel case format
	 * @return   string           $str Translated into underscore format
	 */
	function camelToSlug($str, $glue = '_') {
		$slug = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1' . $glue, $str));
		$explode = explode($glue, $slug);

		if (count($explode) < 2) {
			$slug = strtolower($str);
		}

		return $slug;
	}
}

if (!function_exists('slugToCamel')) {
	/**
	 * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
	 * @param    string   $str                     String in underscore format
	 * @param    bool     $capitalise_first_char   If true, capitalise the first char in $str
	 * @return   string                            $str translated into camel caps
	 */
	function slugToCamel($str, $glue = '_', $capitalise_first_char = false) {
		$camel_case = str_replace($slug, '', ucwords($str, $slug));

		if ($capitalise_first_char === false) {
			$camel_case = lcfirst($camel_case);
		}

		return $camel_case;
	}
}

if (!function_exists('value')) {
	function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}

if (!function_exists('def')) {
	function def($stack, $offset, $default = null)
	{
		if (is_array($stack)) {
			if (array_key_exists($offset, $stack)) {
				return $stack[$offset];
			}
		} elseif (is_object($stack)) {
			if (property_exists($stack, $offset)) {
				return $stack->{$offset};
			} elseif ($stack instanceof ArrayAccess) {
				return $stack[$offset];
			} elseif (method_exists($stack, '__isset')) {
				if ($stack->__isset($offset)) {
					if (method_exists($stack, '__get')) {
						return $stack->__get($offset, $default);
					}

					return $stack->$offset;
				}
			} else {
				return def((array) $stack, $offset, value($default));
			}
		}

		return value($default);
	}
}

if (!function_exists('isWeekend')) {
	function isWeekend($date = null) {
		$date = empty($date) ? date('Y-m-d') : $date;

		return (date('N', strtotime($date)) >= 6);
	}
}

function CekGroupProfil($data){
	switch ($data) {
		case Group::ADMIN:
		$urut = 1;
		break;
		case Group::ADMIN_DIR:
		$urut = 2;
		break;
		case Group::ADMIN_CABANG:
		$urut = 3;
		break;
		case Group::USER:
		$urut = 4;
		break;
		case Group::MANAJEMEN_HO:
		$urut = 5;
		break;
		case Group::MANAJEMEN_DIR:
		$urut = 6;
		break;
		case Group::MANAJEMEN_CABANG:
		$urut = 7;
		break;
		default:
		$urut = null;
		break;
	}

	return $urut;
}

function Cek_Keterangan($ket){
	if($ket == 'H'){
		$result = [
			'keterangan' => 'Hadir',
			'class' => 'class="label label-sm label-success"'
		];
	}elseif($ket == 'S'){
		$result = [
			'keterangan' => 'Sakit',
			'class' => 'class="label label-sm label-info"'
		];
	}
	elseif($ket == 'I'){
		$result = [
			'keterangan' => 'Izin',
			'class' => 'class="label label-sm label-warning"'
		];
	}elseif($ket =='TH'){
		$result = [
			'keterangan' => 'Tidak Hadir',
			'class' => 'class="label label-sm label-danger"'
		];
	}elseif($ket =='CT'){
		$result = [
			'keterangan' => 'Cuti',
			'class' => 'class="label label-sm label-primary"'
		];
	}else{
		$result = [
			'keterangan' => null,
			'class' => null
		];
	}
	return $result;
}

function Cek_Weekend($week,$holi){

	if($week == 1 || $holi == 1){
		$backgroud_color = "style='background-color: #FF0000; color:#FFFFFF;'";
	}else{
		$backgroud_color = "";
	}
	return $backgroud_color;
}

function Format_Letter($no_urut,$romawi,$year){
	
	if(strlen($no_urut) == 1){
		$format =  '000'.$no_urut.'/__/___/__/____/'.$romawi.'/'.$year;
	}elseif(strlen($no_urut) == 2){
		$format =  '00'.$no_urut.'/__/___/__/____/'.$romawi.'/'.$year;
	}elseif(strlen($no_urut) == 3){
		$format =  '0'.$no_urut.'/__/___/__/____/'.$romawi.'/'.$year;
	}else{
		$format = $no_urut.'/__/___/__/____/'.$romawi.'/'.$year;
	}

	return $format;
}

function diffDay($start, $end)
{
	$start = date_create(date('Y-m-d', strtotime($start)));
	$end = date_create(date('Y-m-d', strtotime($end)));
	$diff = date_diff($start, $end);

	return $diff->format("%d%") + 1;
}

/**
 * Count the number of working days between two dates.
 *
 * This function calculate the number of working days between two given dates,
 * taking account of the Public festivities, Easter and Easter Morning days,
 * the day of the Patron Saint (if any) and the working Saturday.
 * @link https://gist.github.com/massiws/9593008
 * 
 * @param   string  $date1    Start date ('YYYY-MM-DD' format)
 * @param   string  $date2    Ending date ('YYYY-MM-DD' format)
 * @param   boolean $workSat  TRUE if Saturday is a working day
 * @param   string  $patron   Day of the Patron Saint ('MM-DD' format)
 * @return  integer           Number of working days ('zero' on error)
 *
 * @author Massimo Simonini <massiws@gmail.com>
 */
function diffWorkDay($date1, $date2, $workSat = TRUE, $patron = NULL) {
	if (!defined('SATURDAY')) define('SATURDAY', 6);
	if (!defined('SUNDAY')) define('SUNDAY', 0);

	// Array of all public festivities
	// $publicHolidays = array('01-01', '01-06', '04-25', '05-01', '06-02', '08-15', '11-01', '12-08', '12-25', '12-26');
	// The Patron day (if any) is added to public festivities
	// if ($patron) {
	// 	$publicHolidays[] = $patron;
	// }

	/*
	* Array of all Easter Mondays in the given interval
	*/
	$yearStart = date('Y', strtotime($date1));
	$yearEnd   = date('Y', strtotime($date2));

	for ($i = $yearStart; $i <= $yearEnd; $i++) {
		$easter = date('Y-m-d', easter_date($i));
		list($y, $m, $g) = explode("-", $easter);
		$monday = mktime(0,0,0, date($m), date($g)+1, date($y));
		$easterMondays[] = $monday;
	}

	$start = strtotime($date1);
	$end   = strtotime($date2);
	$workdays = 0;

	for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
		$day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
		$mmgg = date('m-d', $i);
		if (
			$day != SUNDAY
			// && !in_array($mmgg, $publicHolidays)
			&& !in_array($i, $easterMondays)
			&& !($day == SATURDAY && $workSat == FALSE)
		) {
			$workdays++;
		}
	}

	return intval($workdays);
}

/**
 * Generate an array of string dates between 2 dates
 *
 * @param string $start Start date
 * @param string $end End date
 * @param string $format Output format (Default: Y-m-d)
 *
 * @return array
 */
function getDatesFromRange($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) { 
        $array[] = $date->format($format); 
    }

    return $array;
}

/**
 * [getWorkDatesFromRange description]
 * @param   string  $date1    Start date ('YYYY-MM-DD' format)
 * @param   string  $date2    Ending date ('YYYY-MM-DD' format)
 * @param   boolean $workSat  TRUE if Saturday is a working day
 * @param   string  $patron   Day of the Patron Saint ('MM-DD' format)
 * @return  array             List of working dates
 */
function getWorkDatesFromRange($date1, $date2, $workSat = TRUE, $patron = NULL) {
	if (!defined('SATURDAY')) define('SATURDAY', 6);
	if (!defined('SUNDAY')) define('SUNDAY', 0);

	// Array of all public festivities
	// $publicHolidays = array('01-01', '01-06', '04-25', '05-01', '06-02', '08-15', '11-01', '12-08', '12-25', '12-26');
	// The Patron day (if any) is added to public festivities
	// if ($patron) {
	// 	$publicHolidays[] = $patron;
	// }

	/*
	* Array of all Easter Mondays in the given interval
	*/
	$yearStart = date('Y', strtotime($date1));
	$yearEnd   = date('Y', strtotime($date2));

	for ($i = $yearStart; $i <= $yearEnd; $i++) {
		$easter = date('Y-m-d', easter_date($i));
		list($y, $m, $g) = explode("-", $easter);
		$monday = mktime(0,0,0, date($m), date($g)+1, date($y));
		$easterMondays[] = $monday;
	}

	$start = strtotime($date1);
	$end   = strtotime($date2);
	$workdays = [];

	for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
		$day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
		$mmgg = date('m-d', $i);
		if (
			$day != SUNDAY
			// && !in_array($mmgg, $publicHolidays)
			&& !in_array($i, $easterMondays)
			&& !($day == SATURDAY && $workSat == FALSE)
		) {
			$workdays[] = date('Y-m-d', $i);
		}
	}

	return $workdays;
}

if (!function_exists('isDate')) {
	function isDate($string)
	{
		return strtotime($string) !== false;
	}
}

if (!function_exists('multipleUnset')) {
	function multipleUnset($arrays, $keys)
	{
		foreach ($keys as $key) {
			if (array_key_exists($key, $arrays)) {
				unset($arrays[$key]);
			}
		}

		return $arrays;
	}
}
