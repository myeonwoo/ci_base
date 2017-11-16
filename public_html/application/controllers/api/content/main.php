<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main: Content 관련
 *
*/

class Main extends CI_Controller {
    function __construct(){
        parent::__construct();

        $this->load->model('content/m_content');

        $this->data = array();
        $this->data['user_id'] = $this->session->userdata('ss_mb_id');
        $this->data['user_name'] = $this->session->userdata('ss_mb_name');
        $this->data['user_level'] = $this->session->userdata('ss_mb_level');
        $this->data['is_login'] = $this->data['user_level'];
    }
    // html injecting 데이타 조회
    public function html_injector()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['pagepath'] = $this->validate->string($this->input->get_post('pagepath', true), false);

        $data['content_category_id'] = 5;
        $data['config'] = array(
            'yn_used'=>1, 'content_category_id'=> $data['content_category_id']
            ,'url_main_page'=>$params['pagepath']
        );
        $data['contents'] = $this->m_content->get_list($data['config']);


        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    // 플로팅 배너 
    public function html_injector_floating_banner()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        // 분류값 설정
        $params['content_category_id'] = 11;
        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '/france/promotion/teaser/main');

        $data['now'] = date('Y-m-d H:i:s');
        $data['contents'] = $this->m_content->get_list(array('content_category_id'=>$params['content_category_id']
            , 'yn_used' => 1, 'dt_start'=>$data['now'], 'dt_end'=>$data['now']
            , 'url_main_page'=>$params['url_main_page']));
        foreach ($data['contents'] as $key => &$item) {
            $tmp['content'] = $item;
            $item['html'] = $this->load->view('api/content/html_injector_floating_banner', $tmp, true);
        }
        // $data['content'] = $this->m_content->get($params['content_id']);

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
}