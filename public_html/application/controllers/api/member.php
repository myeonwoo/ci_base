<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member : 토익 관련 API
 *
*/

class Member extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('STRestClient', array('encode' => 'UTF-8') );

        $this->data = array();
        $this->ret = array(
            'result' => false
            ,'msg' => 'no msg'
        );

        $this->data['ss_mb_id'] = $this->session->userdata('ss_mb_id');
        $this->data['ss_mb_level'] = $this->session->userdata('ss_mb_level');
        $this->data['ss_mb_serial'] = $this->session->userdata('ss_mb_serial');
        $this->data['is_login'] = $this->data['ss_mb_level'];

    }
    /****
        * @Desc     현재 로그인 상태
        * @param     
        * @Table    
        * @Author    mw.lim
    *****/
    public function whoami()
    {
        $data = array();
        $data['user_id'] = $this->data['ss_mb_id'];
        $data['is_login'] = $this->data['is_login'];

        /* header("Content-Type:application/json;");
        echo json_encode($data); */
        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    /****
        * @Desc     회원 정보
        * @param    
        * @result     total_amt: 남은 캐쉬
    *****/
    public function account()
    {
        $data = &$this->data;
        $user_id = $data['ss_mb_id'];
        $user_serial = $data['ss_mb_serial'];
        $config = array(
            'url' => BILLING_API_HOST."/api/account",
            'method' => 'GET',
            'data' => array(
                'site_code'=>'STN',
                'user_id'=>$user_id,
                'site_member_srl'=>$user_serial,
                'svc_code'=>'DANGI',
            ),
        );
        $this->strestclient->setData($config['url'], $config['method'], $config['data']);
        $result = $this->strestclient->execute();

        if (isset($result['result']['total_amt'])) $data['total_amt'] = $result['result']['total_amt'];
        else $data['total_amt'] = 0;

        /* header("Content-Type:application/json;");
        echo json_encode($data); */
        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
}