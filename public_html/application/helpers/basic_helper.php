<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// 경고메세지를 경고창으로
function alert($msg='', $url='') {
	$CI =& get_instance();

	if (!$msg) $msg = '올바른 방법으로 이용하세요.';

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'>alert('".$msg."');";
	
	if (!$url) echo "history.go(-1);";
	
	echo "</script>";

	if ($url) goto_url($url);
	exit;
}

// 경고메세지 출력후 창을 닫음
// 추가 : url 전달 시 부모창 해당 url로 이동
function alert_close($msg, $url='') {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'> alert('".$msg."'); ";

	if ($url != '') echo "opener.location.href ='".$url."';";

	echo "window.close(); </script>";
	echo "</script>";
	exit;
}


// 경고메세지 출력후 부모창 새로고침 후 창을 닫음
function alert_opener($msg) {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'> alert('".$msg."'); opener.document.location.reload(); self.close();</script>";
	exit;
}

//confirm 창 출력 후 확인 선택 시 url 로 이동, 비선택 시 no action (부모창 이동)
function confirm($msg, $url) {
	$CI =& get_instance();

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$CI->config->item('charset')."\">";
	echo "<script type='text/javascript'> if(confirm('".$msg."')){ ";
	
	if ($url) echo "parent.location.replace('".$url."');";

	echo "}</script>  ";
	exit;
}

// 해당 url로 이동
function goto_url($url) {
	$CI =& get_instance();
	$CI->load->helper('url');

	$temp = parse_url($url);
	if (empty($temp['host'])) {
		$CI =& get_instance();
		$url = ($temp['path'] != '/') ? SITE_DOMAIN.'/'.$url : SITE_DOMAIN;
		//$url = $CI->config->item('base_url').$url;
	}
	echo "<script type='text/javascript'> location.replace('".$url."'); </script>";
	exit;
}


//부모창  해당 url로 이동
function goto_url_parent($url) {
	$CI =& get_instance();
	$CI->load->helper('url');

	$temp = parse_url($url);
	if (empty($temp['host'])) {
		$CI =& get_instance();
		$url = ($temp['path'] != '/') ? SITE_DOMAIN.'/'.$url : SITE_DOMAIN;
		$url = $CI->config->item('base_url').$url;
	}
	echo "<script type='text/javascript'> parent.location.replace('".$url."'); </script>";
	exit;
}


// 해당 url로 이동
function goto_url_blank($url) {
	$CI =& get_instance();
	$CI->load->helper('url');

	$temp = parse_url($url);
	if (empty($temp['host'])) {
		$CI =& get_instance();
		$url = ($temp['path'] != '/') ? SITE_DOMAIN.'/'.$url : SITE_DOMAIN;
		$url = $CI->config->item('base_url').$url;
	}	
	echo "<script type='text/javascript'> window.open('".$url."'); </script>";	
	exit;
}

function check_wrkey() {
	$CI =& get_instance();
	
	$key = $CI->session->userdata('captcha_keystring');
	if (!($key && $key == $CI->input->post('wr_key'))) {
		$CI->session->unset_userdata('captcha_keystring');
		alert('정상적인 접근이 아닙니다.', '/');
	}
}

function check_browser(){
    if(preg_match('/(iPhone|iPod|iPad)/', $_SERVER['HTTP_USER_AGENT'])){
        $os = "ios";
    }
    elseif(preg_match('/(Android)/', $_SERVER['HTTP_USER_AGENT'])){
        $os = "android";
    }
    else {
        $os = "etc";
    }
    return $os;
}

//alert 후 로그인 페이지로 이동
function alert_login($msg='로그인이 필요합니다.') {
	$url = "https://" . MEMBER_HOST . "/authorize/login?redirect_url=". urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	alert($msg, $url);
}
?>