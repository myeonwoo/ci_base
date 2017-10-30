<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth
 *
*/

class Auth extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('vd');

        $this->config->load('cf_namecheck');

        $this->data = array();
        $this->ret = array(
            'result' => false
            ,'msg' => 'no msg'
        );

        $this->data['user_id'] =  $this->session->userdata('ss_mb_id');
        $this->data['user_name'] =  $this->session->userdata('ss_mb_name');
        $this->data['user_level'] = (gettype($this->session->userdata('ss_mb_level'))=='string') ? $this->session->userdata('ss_mb_level') : 0;
        $this->data['is_login'] = ($this->data['user_level']) ? $this->data['user_level'] : 0;
    }

    public function member()
    {
        $data = &$this->data;
        $data['type_user_id'] = gettype($data['user_id']);
        $data['type_user_name'] = gettype($data['user_name']);
        $data['type_user_level'] = gettype($data['user_level']);
        $data['type_is_login'] = gettype($data['is_login']);

        /* header("Content-Type:application/json;");
        echo json_encode($data); */
        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }


}