<?php 
class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->library('vd');
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
		$this->vd->dump($_SERVER);
	}

	public function info_constant()
    {
        $dataset = array();
        $dataset['SITE_DOMAIN'] = SITE_DOMAIN;
        $dataset['SERVER_DOMAIN'] = SERVER_DOMAIN;
        $dataset['SITE_DIR'] = SITE_DIR;
        $dataset['RT_PATH'] = RT_PATH;
        $dataset['SKIN_ROOT'] = SKIN_ROOT;
        $dataset['JS_ROOT'] = JS_ROOT;
        $dataset['CSS_ROOT'] = CSS_ROOT;
        $dataset['IMG_ROOT'] = IMG_ROOT;
        $dataset['SKIN_PATH'] = SKIN_PATH;
        $dataset['JS_DIR'] = JS_DIR;
        $dataset['CSS_DIR'] = CSS_DIR;
        $dataset['DATA_DIR'] = DATA_DIR;
        $dataset['DATA_PATH'] = DATA_PATH;
        $dataset['FILE_IMG_DIR'] = FILE_IMG_DIR;
        $dataset['DANGI'] = DANGI;
        $dataset['SITE_NAME'] = SITE_NAME;
        $dataset['BIZ_CODE'] = BIZ_CODE;
        $dataset['ADM_F'] = ADM_F;
        $dataset['IMG_DIR'] = IMG_DIR;
        $dataset['M_IMG_DIR'] = M_IMG_DIR;
        $dataset['UPDATE_ROOT_PATH'] = UPDATE_ROOT_PATH;

        $data = $this->commondata->get_adm_header_data($data);
        $data['dataset'] = $dataset;

        $this->load->view(ADM_F.'/head', $data);
        $this->load->view(ADM_F.'/info/constant', $data);
        $this->load->view(ADM_F.'/tail', $data);
    }
}
