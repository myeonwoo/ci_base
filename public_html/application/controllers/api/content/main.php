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
    /**
     * 유의사항 : html injecting
     * 분류값 : content_category_id = 4
     * @return [type] [description]
     */
    public function html_injector()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '/france/promotion/teaser/main');

        $data['content_category_id'] = 4;
        $data['now'] = date('Y-m-d H:i:s');
        $data['config'] = array(
            'yn_used'=>1, 'content_category_id'=> $data['content_category_id']
            ,'url_main_page'=>$params['url_main_page']
        );
        $data['contents'] = $this->m_content->get_list($data['config']);

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    /**
     * 페이지 플로팅 배너 : html injecting
     * 분류값 : content_category_id = 3
     * @return [type] [description]
     */
    public function html_injector_floating_banner()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        // 분류값 설정
        $params['content_category_id'] = 3;
        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '/france/promotion/teaser/main');

        $data['now'] = date('Y-m-d H:i:s');
        $data['contents'] = $this->m_content->get_list(array('content_category_id'=>$params['content_category_id']
            , 'yn_used' => 1, 'dt_start'=>$data['now'], 'dt_end'=>$data['now']
            , 'url_main_page'=>$params['url_main_page']));
        foreach ($data['contents'] as $key => &$item) {
            $tmp['content'] = $item;
            $item['html'] = $this->load->view('api/content/html_injector_floating_banner', $tmp, true);
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    /**
     * 페이지 딥팝업 : html injecting
     * 분류값 : content_category_id = 5
     * @return [type] [description]
     */
    public function html_injector_page_dimpopup()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        // 분류값 설정
        $params['content_category_id'] = 5;
        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '/france/promotion/teaser/main');

        $data['now'] = date('Y-m-d H:i:s');
        $data['contents'] = $this->m_content->get_list(array('content_category_id'=>$params['content_category_id']
            , 'yn_used' => 1, 'dt_start'=>$data['now'], 'dt_end'=>$data['now']
            , 'url_main_page'=>$params['url_main_page']));

        $data['tag_id'] = '';
        $data['html'] = '';
        if (sizeof($data['contents'])>0) {
            $data['content'] = $data['contents'][0];
            $data['tag_id'] = 'content_' . $data['contents'][0]['content_id'];
            $data['html'] = $this->load->view('api/content/html_injector_page_dimpopup', $data, true);
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
    /**
     * 페이지 띠배너 : html injecting
     * 분류값 : content_category_id = 6
     * @return [type] [description]
     */
    public function html_injector_page_linebanner()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        // 분류값 설정
        $params['content_category_id'] = 6;
        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '/france/promotion/teaser/main');

        $data['now'] = date('Y-m-d H:i:s');
        $data['contents'] = $this->m_content->get_list(array('content_category_id'=>$params['content_category_id']
            , 'yn_used' => 1, 'dt_start'=>$data['now'], 'dt_end'=>$data['now']
            , 'url_main_page'=>$params['url_main_page']));

        $data['tag_id'] = '';
        $data['html'] = '';
        if (sizeof($data['contents'])>0) {
            $data['content'] = $data['contents'][0];
            $data['tag_id'] = 'content_' . $data['contents'][0]['content_id'];
            $data['html'] = $this->load->view('api/content/html_injector_page_linebanner', $data, true);
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
}