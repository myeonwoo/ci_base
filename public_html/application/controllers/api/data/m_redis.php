<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Redis
*
*/

class M_redis extends CI_Controller {

    function __construct(){
        parent::__construct();

        $this->load->library('redis');

        $this->data = array();
        $this->data['user_id'] = $this->session->userdata('ss_mb_id');
        $this->data['user_level'] = $this->session->userdata('ss_mb_level');
        $this->data['user_serial'] = $this->session->userdata('ss_mb_serial');
        $this->data['is_login'] = $this->data['ss_mb_level'];
    }

    public function get_data()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['key'] = $this->validate->string($this->input->get_post('key', true), null);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), null);

        $data['value'] = $this->redis->get('engdangi:1000-event-201609:counter:2016-08-25');
        $data['array'] = $this->redis->lrange('engdangi:700-event-201508:detail:2015-08-04', 0, -1);

        if ($params['type'] == 'value') {
            $data['dataset'] = $this->redis->get($params['key']);
        }
        else if ($params['type'] == 'array') {
            $data['dataset'] = $this->redis->lrange($params['key'], 0, -1);
        }
        else if ($params['type'] == 'array_json') {
            $data['dataset'] = $this->redis->lrange($params['key'], 0, -1);
            foreach ($data['dataset'] as $key => &$item) {
                $item = json_decode($item);
            }
        }
        else if ($params['type'] == 'array_json_toexcel') {
            $data['dataset'] = $this->redis->lrange($params['key'], 0, -1);
            foreach ($data['dataset'] as $key => &$item) {
                $item = json_decode($item, true);
            }

            $data['key_names'] = array("아이디","참여 날자");
            $data['keys'] = array('user_id', 'datetime');

            $this->load->view('/common/xls', $data);
            return;
        }


        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }


}