<?php
class Login extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

        $this->load->helper("cookie");
	}

	function index()
	{
		if(_IS_DEV_QA){
			$url = 'https://qa-my.conects.com/admin/login?redirect_url=';
		}
		else{
			$url = 'https://my.conects.com/admin/login?redirect_url=';
		}
		$url .= urlencode('http://'.$_SERVER['HTTP_HOST'].'/adm/login/in');
		goto_url($url);
	}

	function in(){
		//LDAP 결과 쿠키 디코딩
		$this->load->helper('cookie');
		$this->load->library('ldap/ST_Mcrypt');
		$st_info = get_cookie('STINFO');

		if($st_info){
			$decrypt_info = $this->st_mcrypt->decrypt($st_info);

			if($decrypt_info){
				$decrypt_info_arr = json_decode($decrypt_info);

				$this->session->set_userdata('adm_mb_id', $decrypt_info_arr->userid);
				$this->session->set_userdata('adm_name', $decrypt_info_arr->display_name);
				$this->session->set_userdata('adm_mb_level', 8);
			}
		}

		if($decrypt_info_arr){
			goto_url('adm/main/index');
		}
		else{
			goto_url('adm/login');
		}
	}

	function out() {
		if (IS_MEMBER) {
			$this->session->sess_destroy();
			$this->load->helper('cookie'); //LDAP 인증 쿠키 삭제
			delete_cookie("STINFO");
		}
		goto_url('adm/login');
	}

}