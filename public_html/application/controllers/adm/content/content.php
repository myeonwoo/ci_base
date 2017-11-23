<?php 
class Content extends CI_Controller {
  function __construct() {
        parent::__construct();
        $this->load->library('validate');
        $this->load->library('datastructure/hierarchy');

        $this->load->model('content/m_content');

        $this->data = array(
            'info' => array(
                'banner'=> array(
                    'super_content_category_id'=>1
                )
                ,'floating_banner'=> array(
                    'super_content_category_id'=>2
                )
                ,'html_injector'=> array(
                    'super_content_category_id'=>3
                )
                ,'free_lecture'=> array(
                    'super_content_category_id'=>5
                )
                ,'event'=> array(
                    'super_content_category_id'=>6
                )
            )
        );
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
        $data['category']['hierarchy_tree'] = $data['category']['lookup']; // 해당 카테고리 구조 설정

        $data['banner'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id($data['banner']['content_category_id']);
        
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

        $params['super_content_category_id'] = $data['info']['banner']['super_content_category_id'];
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
        $data['categories'] = $this->hierarchy->get_childs_grandchild($params['super_content_category_id']);
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

        $params['super_content_category_id'] = $data['info']['banner']['super_content_category_id'];
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

        $params['super_content_category_id'] = $data['info']['free_lecture']['super_content_category_id'];
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_childs_grandchild($params['super_content_category_id']);
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
    //무료 자료 등록 화면
    public function free_lecture_write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = $data['info']['free_lecture']['super_content_category_id'];
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
        $data['banner']['content_category_id'] = $params['super_content_category_id'];
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

        $params['super_content_category_id'] = $data['info']['event']['super_content_category_id'];
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_childs_grandchild($params['super_content_category_id']);
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
    //이벤트 등록 화면
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
        
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/event_write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }

    // HTML Injector 리스트
    public function html_injector_list()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = $data['info']['html_injector']['super_content_category_id'];
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_childs_grandchild($params['super_content_category_id']);
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
            $this->load->view(ADM_F.'/content/html_injector_list', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    //HTML Injector 등록 화면
    public function html_injector_write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $params['category_id'] = $this->validate->int($this->input->get_post('category_id', true), false);

        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');

        $data['banner'] = array('position_el_selector'=>'.note_txt');
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner']['super_content_category_id'] = $params['super_content_category_id'];

        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/html_injector_write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }

    // HTML Injector 리스트
    public function floating_banner_list()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['super_content_category_id'] = $data['info']['floating_banner']['super_content_category_id'];
        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_childs_grandchild($params['super_content_category_id']);
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
            $this->load->view(ADM_F.'/content/floating_banner_list', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }
    //HTML Injector 등록 화면
    public function flating_banner_write() {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $params['category_id'] = $this->validate->int($this->input->get_post('category_id', true), false);

        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');

        $data['banner'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner']['super_content_category_id'] = $params['super_content_category_id'];
        // $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id($data['banner']['content_category_id']);
        
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);
            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/content/flating_banner_write', $data);
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
    public function copy_content()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['content_id'] = $this->validate->int($this->input->get_post('content_id', true), false);
        $data['content'] = $this->m_content->get_content($params['content_id']);

        unset($data['content']['content_id']);
        $data['content']['subject'] = 'copy: ' . $data['content']['subject'];
        $data['content']['yn_used'] = 0;

        $data['result'] = $this->m_content->insert( $data['content']);

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
                    // $data['result'][$key] = $this->data_file->upload_file($key);
                    $data['result'][$key] = $this->data_file->upload_aws_file_to_admin($key);
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

        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['hierarchy']; // 해당 카테고리 구조 설정

        $data['categories'] = $this->hierarchy->get_childs($params['parent_id']);
        $data['ids_concerned'] = array_map(create_function('$o', 'return $o->content_category_id;'), $data['categories']);
        // $data['ids_concerned'][] = $params['parent_id'];

        $data['check'] = $this->m_content->get_category($params);
        $data['result'] = null;
        if (!$data['check']) {
            $data['result'] = $this->m_content->add_category($params);
        }

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }
    /**
     * API: 카테노이드 미디어 키로 플레이 가능한 미디어 키값 제생
     * @return [type] [description]
     */
    public function convert_playable_url()
    {
        $this->load->library('jwt/JWT');
        $this->load->library('validate');

        $data = array();

        $params = array();
        $data['params'] = &$params;
        $params['type'] = $this->validate->in_array($this->input->get_post('type', true), array('live','live'), 'live');
        $params['media_content_key'] = $this->validate->string($this->input->get_post('media_content_key', true), null);

        $params['KOLLUS_SECURITY_KEY'] = CATENOID_KOLLUS_SECURITY_KEY;
        $params['custom_key'] = CATENOID_CUSTOM_KEY;   // live 사용자 키(kollus CMS 설정 페이지에서 확인 할 수 있습니다.)
        $params['media_profile_key'] = '';                      // media_profile 선택 (ex : catenoid-pc1-high, catenoid-tablet2-high, catenoid-mobile1-normal ...)
        $params['client_user_id'] = '';    // 사이트 USER ID
        $params['expire_time'] = time() + 10*365*24*60*60;                   // media_token 만료 시간 초단위

        // set paylaod
        $data['payload_a'] = array(
            'cuid'  => $params['client_user_id'],   // 옵션: 회원아이디
            'expt'  => $params['expire_time'],      // 필수 : 
            'awtc'  => $awt_code,                   // 옵션
            'mc'    => array(
                array(
                    'mckey' => $params['media_content_key'], // 미디어 키: 필수
                    'mcpf'  => $params['media_profile_key'] // 화질: 옵션
                )
            )
        );

        // create jwt
        $data['JWTstr'] = $this->jwt->encode($data['payload_a'], $params['KOLLUS_SECURITY_KEY'], 'HS256');

        $data['url_play_flash'] = "http://v.kr.kollus.com/sr/media.mp4?jwt={$data['JWTstr']}&custom_key={$params['custom_key']}";
        $data['url_play_native'] = "http://v.kr.kollus.com/s?jwt={$data['JWTstr']}&custom_key={$params['custom_key']}";

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
    }

    /**
     * Form Submit
     */
    //배너 등록&수정
    public function submit_banner() {
        $data = &$this->data;
        $data['_post'] = $_POST;
        $params = array();
        $data['params'] = &$params;

        $data['content_id'] = $this->validate->int($this->input->get_post('content_id', true), '');

        $params['content_category_id'] = $this->validate->int($this->input->get_post('content_category_id', true), null);
        $params['teacher_id'] = $this->validate->string($this->input->get_post('teacher_id', true), '');
        $params['subject'] = $this->validate->string($this->input->get_post('subject', false), null);
        $params['position_x'] = $this->validate->string($this->input->get_post('position_x', true), 0);
        $params['position_y'] = $this->validate->string($this->input->get_post('position_y', true), 0);
        $params['position_el_selector'] = $this->validate->string($this->input->get_post('position_el_selector', true), '');
        $params['desc_main'] = $this->validate->string($this->input->get_post('desc_main', false), null);
        $params['desc_intro'] = $this->validate->string($this->input->get_post('desc_intro', true), '');
        $params['url_main_page'] = $this->validate->string($this->input->get_post('url_main_page', true), '');
        $params['img1_url'] = $this->validate->string($this->input->get_post('img1_url', true), null);
        $params['img1_width'] = $this->validate->string($this->input->get_post('img1_width', true), null);
        $params['img1_link'] = $this->validate->string($this->input->get_post('img1_link', true), null);
        $params['img1_link_html'] = $this->validate->string($this->input->get_post('img1_link_html', false), '');
        $params['img1_link_target'] = $this->validate->string($this->input->get_post('img1_link_target', true), '');
        $params['img1_link_type'] = $this->validate->string($this->input->get_post('img1_link_type', true), '');
        $params['img2_url'] = $this->validate->string($this->input->get_post('img2_url', true), null);
        $params['img2_width'] = $this->validate->string($this->input->get_post('img2_width', true), null);
        $params['img2_link'] = $this->validate->string($this->input->get_post('img2_link', true), null);
        $params['img2_link_html'] = $this->validate->string($this->input->get_post('img2_link_html', false), '');
        $params['img2_link_target'] = $this->validate->string($this->input->get_post('img2_link_target', true), '');
        $params['img2_link_type'] = $this->validate->string($this->input->get_post('img2_link_type', true), '');
        $params['img3_url'] = $this->validate->string($this->input->get_post('img3_url', true), null);
        $params['img3_width'] = $this->validate->string($this->input->get_post('img3_width', true), null);
        $params['img3_link'] = $this->validate->string($this->input->get_post('img3_link', true), null);
        $params['img3_link_html'] = $this->validate->string($this->input->get_post('img3_link_html', false), '');
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

        // 리턴 경로 설정
        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        foreach ($data['info'] as $key => &$item) {
            $tmp = $this->hierarchy->get_childs_grandchild($item['super_content_category_id']);
            $item['children_ids'] = array_map(create_function('$o', 'return $o->content_category_id;'), $tmp);
        }
        if ($params['content_category_id'] == $data['info']['banner']['super_content_category_id'] || in_array($params['content_category_id'], $data['info']['banner']['children_ids'])){
            $data['return_url'] = "/adm/content/content/banner_list?content_category_id={$params['content_category_id']}";
        }
        else if ($params['content_category_id'] == $data['info']['free_lecture']['super_content_category_id'] || in_array($params['content_category_id'], $data['info']['free_lecture']['children_ids'])){
            $data['return_url'] = "/adm/content/content/free_lecture_list?content_category_id={$params['content_category_id']}";
        }
        else if ($params['content_category_id'] == $data['info']['event']['super_content_category_id'] || in_array($params['content_category_id'], $data['info']['event']['children_ids'])){
            $data['return_url'] = "/adm/content/content/event_list?content_category_id={$params['content_category_id']}";
        }
        else if ($params['content_category_id'] == $data['info']['html_injector']['super_content_category_id'] || in_array($params['content_category_id'], $data['info']['html_injector']['children_ids'])){
            $data['return_url'] = "/adm/content/content/html_injector_list?content_category_id={$params['content_category_id']}";
        }
        else if ($params['content_category_id'] == $data['info']['floating_banner']['super_content_category_id'] || in_array($params['content_category_id'], $data['info']['floating_banner']['children_ids'])){
            $data['return_url'] = "/adm/content/content/floating_banner_list?content_category_id={$params['content_category_id']}";
        }
        // $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

        if ($data['content_id']) {
            $this->m_content->update($data['content_id'], $params);
            alert("수정되었습니다.",$data['return_url']);
        } else {
            $this->m_content->insert($params);
            alert("입력되었습니다.",$data['return_url']);
        }


        return;
    }

    
}