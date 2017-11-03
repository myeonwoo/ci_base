<?php 
class Content extends CI_Controller {
  function __construct() {
        parent::__construct();
        $this->load->library('validate');
        $this->load->library('datastructure/hierarchy');

        $this->load->model('content/m_content');
    }

    //배너관리자
    public function index() {
        $this->banner_list();
    }
    public function setting_category()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = 1;
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['hierarchy']; // 해당 카테고리 구조 설정
        $data['banner_category_path'] = array();

        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/setting_category', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    //배너 등록 화면
    public function write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $params['category_id'] = $this->validate->int($this->input->get_post('category_id', true), false);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), 'pc');


        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');

        $data['banner'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id($data['banner']['content_category_id']);
        // $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id(102121212);

        
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    // 배너 관리
    // 최상위 분류 번호 : 1
    public function banner_list()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = 1;
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['content_category_id'] = $this->validate->int($this->input->get_post('content_category_id', true), false);
        $params['type'] = 'banner';

        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정

        $data['banner_category_path'] = array();
        if ($params['content_category_id']) {
            $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id_wo_super_parent($params['content_category_id']);
        }
        $data['categories'] = $this->hierarchy->get_all_childs($params['super_content_category_id']);
        $data['ids_concerned'] = array_map(create_function('$o', 'return $o->content_category_id;'), $data['categories']);
        $data['ids_concerned'][] = $params['super_content_category_id'];

        $data['banners'] = array();
        $data['banners'] = $this->m_content->get_list(array(
            'yn_deleted'=>0
            , 'in_content_category_id'=> $data['ids_concerned']
        ));

        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/banner_list', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    //배너 등록 화면
    public function banner_write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = 1;
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $params['content_category_id'] = $this->validate->int($this->input->get_post('content_category_id', true), false);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), 'pc');


        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정

        $data['banner'] = array();
        $data['banner_category_path'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
            $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id_wo_super_parent($data['banner']['content_category_id']);
        } 
        if ($params['content_category_id']) {
            $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id_wo_super_parent($params['content_category_id']);
        }
        
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/banner_write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    // 무료강의 관리
    public function free_lecture_list()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = 2;
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_all_childs($params['super_content_category_id']);
        $data['ids_concerned'] = array_map(create_function('$o', 'return $o->content_category_id;'), $data['categories']);
        $data['ids_concerned'][] = $params['super_content_category_id'];

        $data['banner_category_path'] = array();
        $data['banners'] = array();
        $data['banners'] = $this->m_content->get_list(array(
            'yn_deleted'=>0
            , 'in_content_category_id'=> $data['ids_concerned']
        ));

        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/free_lecture_list', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    //배너 등록 화면
    public function free_lecture_write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $params['category_id'] = $this->validate->int($this->input->get_post('category_id', true), false);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), 'pc');


        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');

        $data['banner'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id($data['banner']['content_category_id']);
        // $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id(102121212);

        
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/free_lecture_write', $data);
            // $this->load->view(ADM_F.'/content/write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    public function event_list()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = 10;
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_all_childs($params['super_content_category_id']);
        $data['ids_concerned'] = array_map(create_function('$o', 'return $o->content_category_id;'), $data['categories']);
        $data['ids_concerned'][] = $params['super_content_category_id'];

        $data['banner_category_path'] = array();
        $data['banners'] = array();
        $data['banners'] = $this->m_content->get_list(array(
            'yn_deleted'=>0
            , 'in_content_category_id'=> $data['ids_concerned']
        ));

        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/event_list', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    //배너 등록 화면
    public function event_write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $params['category_id'] = $this->validate->int($this->input->get_post('category_id', true), false);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), 'pc');


        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');

        $data['banner'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id($data['banner']['content_category_id']);
        // $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id(102121212);

        
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/event_write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }

    /**
     * API
     */
    public function onoff_content()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $data['content'] = $this->m_content->get($params['content_id']);

        if ($data['content']['yn_used']=='1') {
            $data['update']['yn_used'] = 0;
        } else {
            $data['update']['yn_used'] = 1;
        }
        $data['result'] = $this->m_content->update($params['content_id'], $data['update']);
        $data['content'] = $this->m_content->get($params['content_id']);

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
    public function delete_content()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $data['content'] = $this->m_content->get($params['content_id']);

        $data['update']['yn_deleted'] = 1;

        $data['result'] = $this->m_content->update($params['content_id'], $data['update']);

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }    
    public function upload_file()
    {
        $data = array('result'=>array());
        $data['_FILES'] = $_FILES;
        $data['_POST'] = $_POST;

        $this->load->library('data_file');
        if($_FILES)
        {
            foreach($_FILES as $key => $file)
            {
                if(! empty($file['name']))
                {
                    $data['result'][$key] = $this->data_file->upload_file($key);
                }
            }
        }
        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
    // 분류 추가
    public function add_category()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['parent_id'] = $this->validate->int($this->input->get_post('parent_id', true), false);
        $params['subject'] = $this->validate->string($this->input->get_post('subject', true), false);
        $params['order'] = $this->validate->int($this->input->get_post('order', true), false);
        $params['yn_used'] = $this->validate->int($this->input->get_post('yn_used', true), false);

        $data['check'] = $this->m_content->get_category($params);
        $data['result'] = null;
        if (!$data['check']) {
            $data['result'] = $this->m_content->add_category($params);
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    /**
     * Form Submit
     */
    //배너 등록&수정
    public function submit_banner() {
        $data = array();
        $data['_post'] = $_POST;
        $params = array();
        $data['params'] = &$params;

        $data['content_id'] = $this->validate->int($this->input->get_post('content_id', true), '');

        $params['content_category_id'] = $this->validate->int($this->input->get_post('content_category_id', true), null);
        $params['teacher_id'] = $this->validate->string($this->input->get_post('teacher_id', true), '');
        $params['subject'] = $this->validate->string($this->input->get_post('subject', false), null);
        $params['desc_main'] = $this->validate->string($this->input->get_post('desc_main', false), null);
        $params['desc_intro'] = $this->validate->string($this->input->get_post('desc_intro', true), '');
        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '');
        $params['img1_url'] = $this->validate->string($this->input->get_post('img1_url', true), null);
        $params['img1_width'] = $this->validate->string($this->input->get_post('img1_width', true), null);
        $params['img1_link'] = $this->validate->string($this->input->get_post('img1_link', true), null);
        $params['img1_link_html'] = $this->validate->string($this->input->get_post('img1_link_html', true), '');
        $params['img1_link_target'] = $this->validate->string($this->input->get_post('img1_link_target', true), '');
        $params['img1_link_type'] = $this->validate->string($this->input->get_post('img1_link_type', true), '');
        $params['img2_url'] = $this->validate->string($this->input->get_post('img2_url', true), null);
        $params['img2_width'] = $this->validate->string($this->input->get_post('img2_width', true), null);
        $params['img2_link'] = $this->validate->string($this->input->get_post('img2_link', true), null);
        $params['img2_link_html'] = $this->validate->string($this->input->get_post('img2_link_html', true), '');
        $params['img2_link_target'] = $this->validate->string($this->input->get_post('img2_link_target', true), '');
        $params['img2_link_type'] = $this->validate->string($this->input->get_post('img2_link_type', true), '');
        $params['img3_url'] = $this->validate->string($this->input->get_post('img3_url', true), null);
        $params['img3_width'] = $this->validate->string($this->input->get_post('img3_width', true), null);
        $params['img3_link'] = $this->validate->string($this->input->get_post('img3_link', true), null);
        $params['img3_link_html'] = $this->validate->string($this->input->get_post('img3_link_html', true), '');
        $params['img3_link_target'] = $this->validate->string($this->input->get_post('img3_link_target', true), '');
        $params['img3_link_type'] = $this->validate->string($this->input->get_post('img3_link_type', true), '');
        $params['movie1_key'] = $this->validate->string($this->input->get_post('movie1_key', true), '');
        $params['movie1_url'] = $this->validate->string($this->input->get_post('movie1_url', true), '');
        $params['cnt_view'] = $this->validate->string($this->input->get_post('cnt_view', true), null);
        $params['order'] = $this->validate->int($this->input->get_post('order', true), 100);
        $params['yn_used'] = $this->validate->string($this->input->get_post('yn_used', true), '');
        $params['yn_deleted'] = $this->validate->string($this->input->get_post('yn_deleted', true), '');
        $params['dt_dday'] = $this->validate->string($this->input->get_post('dt_dday', true), null);
        $params['dt_start'] = $this->validate->string($this->input->get_post('dt_start', true), null);
        $params['dt_end'] = $this->validate->string($this->input->get_post('dt_end', true), null);

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

        if ($data['content_id']) {
            $this->m_content->update($data['content_id'], $params);
            alert("수정되었습니다.","/adm/content/content/banner_list?content_category_id={$params['content_category_id']}");
        } else {
            $this->m_content->insert($params);
            alert("입력되었습니다.","/adm/content/content/banner_list?content_category_id={$params['content_category_id']}");
        }
        return;
    }
}