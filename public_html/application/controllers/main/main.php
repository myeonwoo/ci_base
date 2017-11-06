<?php
class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('validate');

		$this->dataset = array();
        $this->dataset['head'] = array(
        );
    }

    public function index()
    {
    	$this->main();
    }

    public function main(){
        $data = array();
        $data['os'] = check_browser();

        if (in_array($data['os'], array('ios','android'))) {
            $this->main_mobile();
        } else {
            $this->main_pc();
        }
    }

    public function main_pc(){
        
        $data = array();
        $params = array();

        $data['params'] = &$params;

        $data = $this->commondata->setHeaderData($this->dataset['head']);

        if ($params['type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $this->load->view('common/header', $data);
            $this->load->view('main/main', $data);
            $this->load->view('common/footer', $data);
        }

    }

    public function main_mobile(){

        $head = $this->commondata->setHeaderData($this->dataset['head']);
        
        $this->load->view('m/common/header', $head);
        $this->load->view('m/main/main', $data);
        $this->load->view('m/common/footer', $tail);
        return;
    }
}
