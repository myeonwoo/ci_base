<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _Common {
	function index() {

		$CI =& get_instance();
		$data = array();
		$data['adm_mb_id'] = $CI->session->userdata('adm_mb_id');
		$data['segment1'] = $CI->uri->segment(1);
		$data['segment2'] = $CI->uri->segment(2);

		$is_member = $CI->session->userdata('ss_mb_id');
		$ssl = false;
	    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
	         $ssl = true;
	    } else if (!empty($_SERVER['HTTP_X_FORWARDED_CONNECTION']) && $_SERVER['HTTP_X_FORWARDED_CONNECTION'] == "SSL") {
	         $ssl = true;
	    }
        // 관리자 영역이면 https 로
		if($data['segment1'] === 'adm'){ // 관리자
	        // https가 아니면
	        if (!$ssl) {
	        	$CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
	        	redirect($_SERVER['REQUEST_URI']);
	        }
			// 로그인 쿠키 없으면 로그인으로 redirect
			else if(!$data['adm_mb_id'] && $data['segment2'] !== "login"){
				goto_url($CI->config->item('base_url')."/adm/login");
			}
			else{
				$is_member =  $data['adm_mb_id'];
			}
		}
		// 회원 영역이면 http 로
		else{
			// https 이면
			if ($ssl) {
				$CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
				redirect($_SERVER['REQUEST_URI']);
			}
		}

		define('IS_MEMBER', $is_member);

	}
}
