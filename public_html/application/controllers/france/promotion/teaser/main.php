<?php
class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $data = $this->commondata->setHeaderData($data);
        $this->load->view('france/common/header', $data);
        $this->load->view('france/promotion/teaser/index', $data);
        $this->load->view('france/common/footer', $data);
        return;
    }
}


