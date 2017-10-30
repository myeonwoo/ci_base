<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*
 * 
*/
class ST_lapi_common{
	private $CI;

	function __construct(){
		$this->CI =& get_instance();
		if(!$this->CI->lapirestclient){
			$this->CI->load->library('LapiRestClient', array('encode'=>'UTF-8') );
		}

		$this->errer_default = array(
  			"status" => "200",
			"resultCode" => "ERR-PARAM",
			"userMsg" => "ok good",
			"sysTemMsg" => "필수 파라메터 누락"
		);
		
	}

	function get_baduser_list(){
		$config = array(
			'url' => LOCAL_API_HOST_SSL."/myroom/common/common/get_baduser_list",			
			'method' => 'GET',
			'data' => array(
			)
		);
			
		$this->CI->lapirestclient->setData($config['url'], $config['method'], $config['data']);
		$this->CI->lapirestclient->setApiData($user_id, "Lapi", __FUNCTION__);
		return $this->CI->lapirestclient->execute();

	}
}