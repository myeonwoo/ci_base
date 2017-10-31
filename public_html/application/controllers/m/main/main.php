<?php

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $data = array (
            'meta_keywords' => '',
            'meta_description' => '',
        );

        $data = $this->commondata->setHeaderData($data);
        
        $this->load->view('m/common/header', $data);
        $this->load->view('m/main/main', $data);
        $this->load->view('m/common/footer', $data);
        return;
    }
} 