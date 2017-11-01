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

    public function constant()
    {
        $data = array();
        $data['SITE_DOMAIN'] = SITE_DOMAIN;
        $data['SERVER_DOMAIN'] = SERVER_DOMAIN;
        $data['SITE_DIR'] = SITE_DIR;
        $data['RT_PATH'] = RT_PATH;
        $data['SKIN_ROOT'] = SKIN_ROOT;
        $data['JS_ROOT'] = JS_ROOT;
        $data['CSS_ROOT'] = CSS_ROOT;
        $data['IMG_ROOT'] = IMG_ROOT;
        $data['SKIN_PATH'] = SKIN_PATH;
        $data['JS_DIR'] = JS_DIR;
        $data['CSS_DIR'] = CSS_DIR;
        $data['DATA_DIR'] = DATA_DIR;
        $data['DATA_PATH'] = DATA_PATH;
        $data['FILE_IMG_DIR'] = FILE_IMG_DIR;
        $data['DANGI'] = DANGI;
        $data['SITE_NAME'] = SITE_NAME;
        $data['BIZ_CODE'] = BIZ_CODE;
        $data['ADM_F'] = ADM_F;
        $data['IMG_DIR'] = IMG_DIR;
        $data['M_IMG_DIR'] = M_IMG_DIR;
        $data['UPDATE_ROOT_PATH'] = UPDATE_ROOT_PATH;

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    public function baduser()
    {
        $this->load->library('lapi/ST_lapi_common', array('encode'=>'UTF-8'));

        $data = array();
        $data['users'] = $this->st_lapi_common->get_baduser_list();

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

}