<?php 
class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {

		
		$data = $this->commondata->get_adm_header_data($data);
		// $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

        $this->load->view(ADM_F.'/head', $data);
        $this->load->view(ADM_F.'/main', $data);
        $this->load->view(ADM_F.'/tail', $data);
        return;
	}

	public function info()
	{
		$this->load->library('vd');

		$this->vd->dump($_SERVER);
	}
}
