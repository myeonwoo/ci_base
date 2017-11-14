<?php

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $data = $this->commondata->setHeaderData($data);

        $this->load->view('france/m/common/header', $data);
        $this->load->view('france/m/main/main', $data);
        $this->load->view('france/m/common/footer', $data);
        return;
    }
}
