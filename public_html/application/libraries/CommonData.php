<?php
/**
 * @explain 공통 데이터 세팅 library
 * 
 */
class CommonData {
	//private $widget_skin = '';

	//construct
	public function __construct($params=null){
	}

	// 어드민 공통 데이타
	public function get_adm_header_data($data = array())
	{
		$this->CI = & get_instance();
		$this->CI->load->model('adm/adm_member/m_member');

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
		
		if($head['is_top']){
			// 회원 정보 필요하면 회원정보 세팅
			$head['mb_name'] = $CI->session->userdata('ss_mb_name');
			$head['common_css_use'] = true;

			//SITE_NAME.' :: 회원가입 - 미래를 앞당기는 용기
			if($head['title']){
				$head['title'] = SITE_NAME.' :: 중국어 1위의 근거 있는 자신감 '.$head['title'];
			}
			else{
				$head['title'] = SITE_NAME;
			}

			if($head['meta-keywords'] ==  '화면 keyword'){
				unset($head['meta-keywords']);
			}
			if($head['meta-description'] == '화면 description'){
				unset($head['meta-description']);
			}

			if($head['meta-keywords']){
				$head['meta_keywords'] = $head['meta-keywords'];
			}
			if($head['meta-description']){
				$head['meta_description'] = $head['meta-description'];
			}
	    
		}

		return $head;
	}
	public function setHeaderDataMobile($head){
		
		$CI =& get_instance();
		
		if($head['is_top']){
			// 회원 정보 필요하면 회원정보 세팅
			$head['mb_name'] = $CI->session->userdata('ss_mb_name');
			$head['common_css_use'] = true;

			//SITE_NAME.' :: 회원가입 - 미래를 앞당기는 용기
			if($head['title']){
				$head['title'] = SITE_NAME.' :: 중국어 1위의 근거 있는 자신감 '.$head['title'];
			}
			else{
				$head['title'] = SITE_NAME;
			}

			if($head['meta-keywords'] ==  '화면 keyword'){
				unset($head['meta-keywords']);
			}
			if($head['meta-description'] == '화면 description'){
				unset($head['meta-description']);
			}

			if($head['meta-keywords']){
				$head['meta_keywords'] = $head['meta-keywords'];
			}
			if($head['meta-description']){
				$head['meta_description'] = $head['meta-description'];
			}
	    
		}

		return $head;
	}
	
	
}
?>
