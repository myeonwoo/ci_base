<?php
/**
 * @explain 공통 데이터 세팅 library
 * 
 */
class CommonData {

	//construct
	public function __construct($params=null){
	}

	// 어드민 공통 데이타
	public function get_adm_header_data($data = array())
	{
		$this->CI = & get_instance();

		$data['adm_mb_id'] = $this->CI->session->userdata('adm_mb_id');
		$data['adm_name'] = $this->CI->session->userdata('adm_name');
		$data['adm_mb_level'] = $this->CI->session->userdata('adm_mb_level');
		$data['uri_string'] = $this->CI->uri->uri_string();
		$data['uri_segments'] = $this->CI->uri->segment_array();
		$data['get_params'] = $this->CI->input->get();

		return $data;
	}
	public function get_adm_footer_data($data = array())
	{
		return $data;
	}

	// 공통 데이타 설정
	public function setHeaderData($head){
		
		$CI =& get_instance();
		$CI->load->library('validate');

		$user = array();
		$user['user_id'] = $CI->session->userdata('ss_mb_id');
        $user['user_name'] = $CI->session->userdata('ss_mb_name');
        $user['user_level'] = $CI->session->userdata('ss_mb_level');
        $user['is_login'] = $this->data['user_level'];
        $head['user'] = $user;

		$head['title'] = "{SITE_NAME} :: {MSG_TITLE} ".$head['title'];
		$head['meta_keywords'] = $CI->validate->string($head['meta_keywords'], null);
		$head['meta_description'] = $CI->validate->string($head['meta_description'], null);

		return $head;
	}
	public function setHeaderDataMobile($head){
		
		$CI =& get_instance();
		$CI->load->library('validate');
		
		$user = array();
		$user['user_id'] = $CI->session->userdata('ss_mb_id');
        $user['user_name'] = $CI->session->userdata('ss_mb_name');
        $user['user_level'] = $CI->session->userdata('ss_mb_level');
        $user['is_login'] = $this->data['user_level'];
        $head['user'] = $user;

		$head['title'] = "{SITE_NAME} :: {MSG_TITLE} ".$head['title'];
		$head['meta_keywords'] = $CI->validate->string($head['meta_keywords'], null);
		$head['meta_description'] = $CI->validate->string($head['meta_description'], null);

		return $head;
	}
	
	
}