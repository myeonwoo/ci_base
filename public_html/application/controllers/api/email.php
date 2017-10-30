<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Email
 *
*/

class Email extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('url');

        $this->config->load('cf_namecheck');

        $this->data = array();
        $this->data['user_id'] = $this->session->userdata('ss_mb_id');
        $this->data['user_name'] = $this->session->userdata('ss_mb_name');
        $this->data['user_level'] = $this->session->userdata('ss_mb_level');
        $this->data['is_login'] = $this->data['user_level'];
    }

    public function test()
    {
        $data = &$this->data;

        $this->load->library('email');

        $this->email->from('mw.lim@st-company.net', 'Your Name');
        $this->email->to('myeonwoo.lim@gmail.com'); 

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');  

        $this->email->send();

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    public function send()
    {
        $this->load->library('validate');

        $data = &$this->data;
        $data['user_name'] = $this->validate->string($this->input->get_post('user_name', true), null);
        $data['user_mail'] = $this->validate->string($this->input->get_post('user_mail', true), null);
        $data['user_number'] = $this->validate->string($this->input->get_post('user_number', true), null);
        $data['user_id'] = $this->validate->string($this->input->get_post('user_id', true), null);
        $data['user_title'] = $this->validate->string($this->input->get_post('user_title', true), null);
        $data['user_cont'] = $this->validate->string($this->input->get_post('user_cont', true), null);

        $this->load->library('email');

        $this->email->from($data['user_mail'], $data['user_name']);
        $this->email->to('chinadangi@st-company.net'); 

        $content = "{$data['user_name']}({$data['user_id']}) : {$data['user_number']}";
        $content .= $data['user_cont'];

        $this->email->subject($data['user_title']);
        $this->email->message($content);  

        $this->email->send();
        $data['msg'] = '메일 송신되었습니다.';

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

}