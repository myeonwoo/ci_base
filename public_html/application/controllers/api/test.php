<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Test
 *
*/

class Test extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->data = array();
        $this->ret = array(
            'result' => false
            ,'msg' => 'no msg'
        );

        $this->data['ss_mb_id'] = $this->session->userdata('ss_mb_id');
        $this->data['ss_mb_serial'] = $this->session->userdata('ss_mb_serial');
        $this->data['ss_mb_name'] = $this->session->userdata('ss_mb_name');
        $this->data['ss_mb_level'] = $this->session->userdata('ss_mb_level');
        $this->data['is_login'] = $this->data['ss_mb_level'];

    }

    public function baduser()
    {
        $this->load->library('lapi/ST_lapi_common', array('encode'=>'UTF-8'));

        $data = array();
        $data['users'] = $this->st_lapi_common->get_baduser_list();

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

}