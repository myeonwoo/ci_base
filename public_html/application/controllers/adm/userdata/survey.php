<?php
class Survey extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('userdata/m_survey');
		$this->data = array();
	}

	public function index()
	{
		$this->survey_list();
	}

	public function survey_list()
	{
		$data = &$this->data;
		$params = array();
		$data['params'] = &$params;

		$data['list'] = $this->m_survey->get_config_list();

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$data = $this->commondata->get_adm_header_data($data);
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/content/survey_list', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
	}
}