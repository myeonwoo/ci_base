<?php
class Main extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index()
    {
        $data = array('js'=>array('/js/common/underscore.js'));
        $data = $this->commondata->setHeaderData($data);
        $params = array();
        $data['params'] = &$params;
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

        if ($params['render_type'] == 'json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->setHeaderData($data);
            $this->load->view('france/common/header', $data);
            $this->load->view('france/promotion/teaser/index', $data);
            $this->load->view('france/common/footer', $data);
        }
    }

    public function submit_application()
    {
        $this->load->model('comment/m_comment');

        $data = $this->commondata->setHeaderData($data);
        $params = array();
        $data['params'] = &$params;

        $data['total'] = $this->m_comment->get_list_total(array('comment_config_id'=>1, 'author_id'=>$data['user']['user_id']));

        if (!$data['user']['is_login']) {
            $data['result_code'] = 3000;
            $data['msg'] = '로그인 후 이용 가능합니다. 확인을 누르시면 로그인 페이지로 이동 합니다.';
        }
        else if ($data['total'] == 0) {
            $data['result_code'] = 2000;
            $data['msg'] = '아래 프단기 기대평을 작성해 주셔야 구매하실 수 있어요.';
        }
        else {
            $data['result_code'] = 1000;
            $data['msg'] = '';
        }



        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
}


