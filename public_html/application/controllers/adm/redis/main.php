<?php
class main extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->data = array();
	}

	public function index()
	{
		$this->search();
	}

	public function search()
	{
		$data = $this->commondata->get_adm_header_data($this->data);

		$params = array();
		$data['params'] = &$params;

		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

		$data['redis_keys'] = array(
			array('name'=>'테스트 페이지', 'key'=>'engdangi:winter-vacation-lc-201612:id', 'type'=>'array')
		);

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/redis/main/search', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
	}
}