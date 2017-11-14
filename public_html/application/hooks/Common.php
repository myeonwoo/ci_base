<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _Common {
	function index() {

		$CI =& get_instance();
		$data = array();
		$data['adm_mb_id'] = $CI->session->userdata('adm_mb_id');
		$data['segment1'] = $CI->uri->segment(1);
		$data['segment2'] = $CI->uri->segment(2);

		$data['is_member'] = $CI->session->userdata('ss_mb_id');
		$data['ssl'] = false;
	    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
	         $data['ssl'] = true;
	    } else if (!empty($_SERVER['HTTP_X_FORWARDED_CONNECTION']) && $_SERVER['HTTP_X_FORWARDED_CONNECTION'] == "SSL") {
	         $data['ssl'] = true;
	    }
	    $data['flag_dangi_host'] = strpos($CI->config->config['base_url'], 'global.dangi.co.kr');
		$data['flag_aws_dangi_host'] = strpos($CI->config->config['base_url'], 'st-event-global.dangi.co.kr');

		// st-event 호스트 아니며 dangi 호스트 인경우
		if ($data['flag_aws_dangi_host'] === false && $data['flag_dangi_host']) {
			$CI->config->config['base_url'] = str_replace("global.dangi.co.kr","global.conects.com", $CI->config->config['base_url']);
			redirect($_SERVER['REQUEST_URI']);
		}
        // 관리자 영역이면 https 로
		elseif($data['segment1'] === 'adm'){ // 관리자
	        // https가 아니면
	        if (!$data['ssl']) {
	        	$CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
	        	redirect($_SERVER['REQUEST_URI']);
	        }
			// 로그인 쿠키 없으면 로그인으로 redirect
			else if(!$data['adm_mb_id'] && $data['segment2'] !== "login"){
				goto_url($CI->config->item('base_url')."/adm/login");
			}
			else{
				$data['is_member'] =  $data['adm_mb_id'];
			}
		}
		// 회원 영역이면 http 로
		else{
			// https 이면
			if ($data['ssl']) {
				$CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
				redirect($_SERVER['REQUEST_URI']);
			}
		}

		define('IS_MEMBER', $data['is_member']);

	}
}
