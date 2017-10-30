<?php

class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $head= array (
            'meta_description' => '',
            'meta_keywords' => '',
        );

        $head = $this->commondata->setHeaderData($this->dataset['head']);
        $tail = $this->commondata->setFooterData($this->dataset['tail']);
        
        $this->load->view('m/common/header', $head);
        $this->load->view('m/main/main', $data);
        $this->load->view('m/common/footer', $tail);
        return;
    }
} 