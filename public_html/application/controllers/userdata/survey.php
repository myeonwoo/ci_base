<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Survey : 
 *
*/

class Survey extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('validate');
        $this->load->library('vd');

        $this->load->model('userdata/m_survey');

        $data = array();
        $data['ss_mb_id'] = $this->session->userdata('ss_mb_id');
        $data['ss_mb_level'] = $this->session->userdata('ss_mb_level');
        $data['ss_mb_serial'] = $this->session->userdata('ss_mb_serial');
        $data['is_login'] = $data['ss_mb_level'];
        $this->data = $data;

    }

    // 사용 예제: window.open('/userdata/survey?survey_config_id=1','','height=1,width=1,left=80,top=80,scrollbars=yes,toolbar=no,status=no');
    public function index()
    {
        $data = &$this->data;
        $data['url_login'] = "https://". MEMBER_HOST. "/authorize/login?redirect_url=" . urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $data['url_main'] = "/main";
        $data['now'] = date('Y-m-d H:i:s');

        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['survey_config_id'] = $this->validate->int($this->input->get_post('survey_config_id', true), null);
        
        $data['survey_config'] = $this->m_survey->get_config($params['survey_config_id']);


        if (! $data['survey_config']) {
            alert_close('등록되지 않은 이벤트입니다.');
            return;
        }

        if ($data['survey_config']['yn_login'] && ! $data['is_login']) {
            alert('로그인 후 이용가능합니다.', $data['url_login']);
            return;
        }

        if (!($data['survey_config']['dt_start'] < $data['now'] && $data['now'] < $data['survey_config']['dt_end'])) {
            alert_close('이벤트가 종료되었습니다.');
            return;
        }
        if ($data['cnt_limit'] < $data['cnt_inserted']) {
            alert_close('이벤트가 종료되었습니다.');
            return;
        }

        if ($params['render_type'] == 'json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $this->load->view('/userdata/survey/index', $data);
        }
    }
    /****
        * @Desc     등록
        * @param     
        * @Table    
        * @Author    mw.lim
    *****/
    public function insert()
    {
        $this->load->library('data_file');

        $data = &$this->data;
        $data['_post'] = $_POST;
        $data['_FILES'] = $_FILES;
        $params = array();
        $data['params'] = &$params;

        $params['data_survey_config_id'] = $this->validate->int($this->input->get_post('data_survey_config_id', true), null);
        $params['user_name'] = $this->validate->string($this->input->get_post('user_name', true), null);
        $params['user_phone'] = $this->validate->string($this->input->get_post('user_phone', true), null);
        $params['user_email'] = $this->validate->string($this->input->get_post('user_email', true), null);
        $params['bank_name'] = $this->validate->string($this->input->get_post('bank_name', true), null);
        $params['bank_account'] = $this->validate->string($this->input->get_post('bank_account', true), null);
        $params['bank_owner'] = $this->validate->string($this->input->get_post('bank_owner', true), null);
        $params['user_address'] = $this->validate->string($this->input->get_post('user_address', true), null);
        $params['user_comment'] = $this->validate->string($this->input->get_post('user_comment', true), null);
        

        $data['survey_config'] = $this->m_survey->get_config($params['data_survey_config_id']);

        if (! $data['survey_config']) {
            alert_close('등록되지 않은 이벤트입니다.');
            return;
        }

        if ($data['survey_config']['yn_login'] && ! $data['is_login']) {
            alert_close('로그인 후 이용가능합니다.');
            return;
        }

        $data['data_local'] = array(
            'data_survey_config_id' => $data['survey_config']['data_survey_config_id']
            ,'user_id' => $data['ss_mb_id']
        );
        if ($params['user_comment']) $data['data_local']['comment1'] = $params['user_comment'];
        // 파일 입력
        // $data['data_local']['upload_path'] = $this->data_file->upload_file('user_file');
        $data['data_local']['upload_path'] = $this->data_file->upload_aws_file_to_user_survey('user_file');

        if ($data['ss_mb_id'] && $data['survey_config']['gather_id']) {
           $data['data_secure'] = array(
                'gather_id' => $data['survey_config']['gather_id']
                ,'dangi_code' => 'engdangi'
                ,'client_ip' => $this->input->ip_address()
                ,'user_id' => $data['ss_mb_id']
            );
            if ($params['user_name']) $data['data_secure']['name'] = $params['user_name'];
            if ($params['user_phone']) $data['data_secure']['phone'] = $params['user_phone'];
            if ($params['user_email']) $data['data_secure']['email'] = $params['user_email'];
            if ($params['user_address']) $data['data_secure']['address'] = $params['user_address'];
            if ($params['bank_name']) $data['data_secure']['bankname'] = $params['bank_name'];
            if ($params['bank_account']) $data['data_secure']['bankaccount'] = $params['bank_account'];
            if ($params['bank_owner']) $data['data_secure']['bankowner'] = $params['bank_owner'];

            $this->load->model('member/m_userdata');
            $data['data_secure_result'] = $this->m_userdata->api_user_gather_reg($data['data_secure']);

        }
        
        $data['data_local_result'] = $this->m_survey->insert_data($data['data_local']);
        // 입력수 증가
        $data['increment_config'] = $this->m_survey->increment_config($data['survey_config']['data_survey_config_id']);

        // $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

        $data['alert_msg'] = $this->validate->string($data['survey_config']['msg_onsubmit'], '입력 완료되었습니다.\\n감사합니다.');
        if($this->input->get_post('redirect_url', true)){
            alert($data['alert_msg'], $this->input->get_post('redirect_url', true));
        }else{
            alert_close($data['alert_msg']);
        }
    }

}