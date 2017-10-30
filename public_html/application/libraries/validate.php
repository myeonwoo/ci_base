<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 * 수치 개산/포맷 관련 모듈
*/
class Validate{

	function Validate( )
	{
		// noop
	}

	/****
		* @Desc 	태그제거 (onclick, iframe, etc)
		* @param	
	*****/
	private function xss_clean($str)
	{
		//Convert utf-8
		// $char_arr = array("EUC-KR","ASCII","UTF-8");
		// $charset = mb_detect_encoding($str, $char_arr);
		// if($charset != "UTF-8") $str = iconv($charset,"UTF-8",$str);
			
		// Fix &entity\n;
		$str = str_replace(array('&','<','>'), array('&amp;','&lt;','&gt;'), $str);
		$str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
		$str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
		$str = htmlspecialchars_decode($str, ENT_COMPAT);

		// Remove any attribute starting with "on" or xmlns
		$str = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $str);

		// Remove javascript: and vbscript: protocols
		$str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
		$str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
		$str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
		$str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
		$str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $str);

		// Remove namespaced elements (we do not need them)
		$str = preg_replace('#</*\w+:\w[^>]*+>#i', '', $str);

		do
		{
			// Remove really unwanted tags
			$old_data = $str;
			$str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);
		}
		while ($old_data !== $str);

		//Restore charset
		// if($charset != "UTF-8") $str = iconv("UTF-8",$charset,$str);

		return $str;
	}

	public function string_clean($str)
	{
		$str = $this->xss_clean($str);
		// $str = addslashes($str);
		return $str;
	}

	/****
		* @Desc 	aMerge 배열 값중 aData 배열에 키값으로 없으면 NULL 값으로 할당
		* @param	
	*****/
	public function join_key($aData = array(), $aMerge=array())
	{
		foreach ($aMerge as $key => $value) {
			if ( !isset($aData[$value]) ) {
				$aData[$value] = NULL;
			}
		}
		return $aData;
	}

	/****
		* @Desc 	datet 포맷 검증
		* @param	
	*****/
	public function date($input, $type = 1)
	{
		switch ($type) {
			// YYYY-MM-DD
			case 1:
				$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
				$date_regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
				break;
			// YYYY-MM-DD
			default:
				$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/';
				$date_regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
				# code...
				break;
		}
		return preg_match($date_regex, $input);
	}

	public function in_array($input, $pieces = array(), $default = NULL)
	{
		if (in_array($input, $pieces)) {
			return $input;
		} else {
			return $default;
		}
	}

	/****
		* @Desc 	integer 포맷인지 확인
		* @param	input : 값이 integer 이면 원값 리턴, 아니면 default 값 리턴
	*****/
	public function int($input, $default = NULL)
	{
		$input = (string) $input;
		if( preg_match('/^\d+$/', $input) ) {
			return intval($input);
		}
		return $default;
	}

	public function integer($input)
	{
		$input = (string) $input;
		if( preg_match('/^\d+$/', $input) ) {
			return true;
		}
		return false;
	}

	/****
		* @Desc 	integer 포맷인지 확인
		* @param	dataset : 배열값 모두 integer 이면 true 리턴, 아니면 false
	*****/
	public function int_array($input=array())
	{
		if ( sizeof($input) == 0 ) return false;

		foreach ($input as $key => $value) {
			if( ! preg_match('/^\d+$/', $value) ) {
				return false;
			}
		}
		
		return true;
	}

	/** ip address 포맷인지 확인 **/
	public function ip_address($input, $default = NULL)
	{
		if( preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $input) ) {
			return $input;
		}
		return $default;
	}

	/** integer 포맷인지 확인 **/
	public function int_positive($input, $default = NULL)
	{
		if( preg_match('/^[1-9][0-9]*$/', $input) ) {
			return $input;
		}
		return $default;
	}

	public function not_empty($input, $default = NULL)
	{
		if( preg_match('/[\S]+/', $input) ) {
			return $input;
		}
		return $default;
	}
	/****
		* @Desc 	input 값이 string 이면 trim 된 값 리턴
		* @param	
	*****/
	public function string($input, $default = NULL)
	{
		if (gettype($input) == 'string') {
			$input = trim($input);
			if (strlen($input)>0) {
				return $input;
			} else {
				return $default;
			}
		} else {
			return $default;
		}
	}

	public function compare($input1, $input2, $return_for_match = true, $return_for_mismatch = false)
	{
		if ($input1 === $input2)
			return $return_for_match;
		else
			return $return_for_mismatch;
	}
	
	/****
		* @Desc 	입력값이 공란이면 null 리턴 아니면 trim된 값 리턴
		* @param	
	*****/
	public function string_empty($input, $default = NULL)
	{
		if ($input === false) return $default;

		if ($input === null) return $default;
		
		$input = trim($input);
		if ($input === '') return $default;
		else return $input;
	}

	/****
		* @Desc 	존재 여부 확인
		* @param	
	*****/
	public function exist($input)
	{
		if (in_array($input, array(null,'')))
			return false;
		else
			return true;
	}

	public function not_null($input)
	{
		if ($input === null)
			return false;
		else
			return true;
	}

	/****
		* @Desc 	따옴표 (', ", (, ))를 blank로 대체해 반환
		* @param	
	*****/
	public function strip_notation($value)
	{
		$strip = array("'", '"', "(", ")", "+");
		$value = str_replace($strip, " ",$value);
		$value = str_replace($strip, " ",$value);
		return $value;
	}

	/****
		* @Desc 	숫자 digit으로 쪼개기
		* @param	
	*****/
	public function get_digits($number, $length)
	{
		$data = array();
		$data['d'] = strrev("{$number}");
		for ($i=1; $i <= $length; $i++) {
			$key = "d".$i;
			$data[$key] = (strlen($data['d']) >= $i) ? $data['d'][$i-1] : '0';
		}

		return $data;
	}

	public function get_today() {
		$toDayDay		= date("Y-m-d"); 
		$toDayHour		= date("H"); 
		$toDayMinute	= date("i");
		$toDaySecond	= date("s");

		$toDayDt = array(
			'date' 	=> $toDayDay
			, 'hour' 	=> $toDayHour
			, 'min' 	=> $toDayMinute
			, 'sec' 	=> $toDaySecond
			, 'hour_i' 	=> (int)$toDayHour
			, 'min_i' 	=> (int)$toDayMinute
			, 'sec_i' 	=> (int)$toDaySecond
		);

		return $toDayDt;
	}

	/**
	 * remain days (include)
	 * @param  [string] $date1 [예 : 2012-07-25 12:00:00]
	 * @return [type]        [description]
	 */
	public function get_remain_days($date1)
	{
		$now = new Datetime();
		$dEnd  = new DateTime($date1);
		if ($dEnd < $now) {
			return 0;
		}
		$dDiff = $now->diff($dEnd);
		return $dDiff->days + 1;
	}
}