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
		$data = &$this->data;
		$params = array();
		$data['params'] = &$params;

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$data = $this->commondata->get_adm_header_data($data);
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/redis/main/search', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
	}
}