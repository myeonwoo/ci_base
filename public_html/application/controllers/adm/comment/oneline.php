<?php
class Oneline extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('comment/m_comment');

		$this->data = array();
	}

	public function index()
	{
		$this->config_list();
	}

	public function config_list()
	{
		$data = &$this->data;
		$params = array();
		$data['params'] = &$params;
		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

		$data['comment_config_list'] = $this->m_comment->get_config_list();

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$data = $this->commondata->get_adm_header_data($data);
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/comment/oneline/config_list', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
		return;
	}

	public function config_write()
	{
		$data = array();
		$params = array();
		$data['params'] = &$params;
		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
		$params['comment_config_id'] = $this->validate->int($this->input->get_post('comment_config_id', true), null);

		$data['config'] = array();
		if ($params['comment_config_id']) {
			$data['config'] = $this->m_comment->get_config($params['comment_config_id']);
		}

		if ($params['render_type'] == 'json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$data = $this->commondata->get_adm_header_data($data);
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/comment/oneline/config_write', $data);
			$this->load->view(ADM_F.'/tail', $data);
			return;
		}
	}

	public function submit_comment_config()
	{
		$data = array();
		$params = array();
		$data['params'] = &$params;

		$params['comment_config_id'] = $this->validate->int($this->input->get_post('comment_config_id', true), null);
		$params['desc_title'] = $this->validate->string($this->input->get_post('desc_title', false), null);
		$params['yn_login'] = $this->validate->int($this->input->get_post('yn_login', true), 1);
		$params['desc_note'] = $this->validate->string($this->input->get_post('desc_note', false), null);

		// $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

		if($params['comment_config_id']){
			$data['result'] = $this->m_comment->update_config($params['comment_config_id'], $params);
			alert("수정되었습니다.","/adm/comment/oneline/config_list");
		}else{
			$data['result'] = $this->m_comment->insert_config($params);
			alert("등록되었습니다.","/adm/comment/oneline/config_list");             
		}
	}
	// 댓글 다운로드
	public function download_comment()
	{

		$data = array();
		$params = array();
		$data['params'] = &$params;
		$params['comment_config_id'] = $this->validate->int($this->input->get_post('comment_config_id', true), null);
		$params['limit'] = $this->validate->int($this->input->get_post('limit', true), 1000);
		$params['offset'] = 0;
		$data['result'] = $this->m_comment->get_list($params);

		$data['key_names'] = array('댓글번호','댓글게시판번호','타입','작성자','nickname','ip_address','yn_1','yn_2','yn_3','desc_content','yn_deleted','dt_created');
		$data['keys'] = array('comment_id','comment_config_id','type','author_id','nickname','ip_address','yn_1','yn_2','yn_3','desc_content','yn_deleted','dt_created');

		$data['dataset'] = $data['result'];
		$data['filename'] ="댓글_{$params['comment_config_id']}";

		// $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

		$this->load->view('/common/xls', $data); 
	}

}