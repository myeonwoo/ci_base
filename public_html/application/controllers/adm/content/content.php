<?php 
class Content extends CI_Controller {
  function __construct() {
        parent::__construct();
        $this->load->library('validate');
        $this->load->library('datastructure/hierarchy');

        $this->load->model('content/m_content');
    }

    //배너관리자
    function index() {
        $this->banner_list();
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
        // $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'banner';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정
        $data['categories'] = $this->hierarchy->get_all_childs($params['super_content_category_id']);
        $data['ids_concerned'] = array_map(create_function('$o', 'return $o->content_category_id;'), $data['categories']);

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
        $params['category_id'] = $this->validate->int($this->input->get_post('category_id', true), false);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), 'pc');


        $data['content_categories'] = $this->m_content->get_category_list_all(array('use_yn'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['category']['hierarchy_tree'] = $data['category']['lookup'][$params['super_content_category_id']]->children; // 해당 카테고리 구조 설정

        $data['banner'] = array();
        if($params['content_id']){
            $data['banner'] = $this->m_content->get($params['content_id']);  //배너정보
        }
        $data['banner_category_path'] = $this->hierarchy->find_path_on_parent_id_wo_super_parent($data['banner']['content_category_id']);

        
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
    public function free_lecture_list_()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'free_lecture';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');
        $data['categories'] = $this->hierarchy->get_all_childs(1);
        $data['ids_concerned'] = array_map(create_function('$o', 'return $o->content_category_id;'), $data['categories']);

        $data['free_lectures'] = $this->m_content->get_list(array(
            'like_subject'=>'환급반', 'yn_deleted'=>0
            ,'in_content_category_id'=> $data['ids_concerned']
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
    public function event_list_()
    {
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = 'event';


        $data['content_categories'] = $this->m_content->get_category_list_all(array('yn_used'=>1));    //카테고리 리스트 전체 
        $data['category'] = $this->hierarchy->load_data($data['content_categories'], 'content_category_id', 'parent_id');

        $data['banners'] = $this->m_content->get_list(array(
            'like_subject'=>'환급반', 'yn_deleted'=>0
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

    /**
     * Form Submit
     */
    //배너 등록&수정
    public function submit_banner() {
        $now = date('YmdH');

        $data['content_id'] = $this->validate->int($this->input->get_post('content_id', true), null);
        $data['content_category_id'] = $this->validate->int($this->input->get_post('content_category_id', true), null);
        $data['subject'] = $this->validate->string($this->input->get_post('subject', false), null);
        $data['desc_main'] = $this->validate->string($this->input->get_post('desc_main', false), null);
        $data['dt_start'] = $this->validate->string($this->input->get_post('dt_start', true), null);
        $data['dt_end'] = $this->validate->string($this->input->get_post('dt_end', true), null);
        $data['dt_dday'] = $this->validate->string($this->input->get_post('dt_dday', true), null);
        $data['img_width'] = $this->validate->int($this->input->get_post('img_width', true), null);
        $data['banner_type'] = $this->validate->string($this->input->get_post('banner_type', true), null);
        $data['img_link_target'] = $this->validate->string($this->input->get_post('img_link_target', true), null);
        $data['img_link'] = $this->validate->string($this->input->get_post('img_link', true), null);
        $data['img_url'] = $this->validate->string($this->input->get_post('img_url', true), null);
        $data['img_html'] = $this->validate->string($this->input->get_post('img_html', true), null);
        $data['order'] = $this->validate->int($this->input->get_post('order', true), null);
        $data['yn_use'] = $this->validate->int($this->input->get_post('yn_use', true), 1);


        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

        $data['target'] = $this->input->post('target', false);  //새창열기
        $data['display_order'] = $this->input->post('display_order', true);
        $data['use_yn'] = $this->input->post('use_yn', true);
        $data['band_height'] = $this->input->post('band_height', true);
        
        // D-Day
        $data['d_day_yn'] = $this->input->post('d_day_yn', true);
        if($data['d_day_yn'] == 'Y'){
            $data['d_day_color'] = '#'.$this->input->post('d_day_color1', false).'_#'.$this->input->post('d_day_color2', false).'_#'.$this->input->post('d_day_color3', false).'_'.$this->input->post('d_day_color4', false).'_'.$this->input->post('d_day_opacity', false);
            $data['d_day_position'] = $this->input->post('d_day_left', false).'_'.$this->input->post('d_day_top', false);
        }
        
        $type = $this->input->post('type', true);
     
        //등록일 경우
        if($this->input->post('mode', true) == 'ins'){
            $data['created_time'] = date('Y-m-d H:i:s');
            $return = $this->m_content->banner_Ins($data);
             
            alert("등록되었습니다.","/adm/banner/banner?category_id=".$data['category_id']."&sub_category_id=".$data['sub_category_id']."&type=".$type);
            
        //삭제일 경우    
        }else if($this->input->post('mode', true) == 'del'){
            
            unset($data);
            $data['deleted_time'] = date('Y-m-d H:i:s');
            $content_id = $this->input->post('content_id', true);
            $return = $this->m_content->banner_mod($data, $content_id);
            
            //alert("삭제되었습니다.","/adm/banner/banner");
            /* echo json_encode(array("result" => "1")); */
            $this->output->set_content_type("application/json")->set_output(json_encode(array("result" => "1")));return;
            exit;
        
        //수정일경우  
        }else{
            $data['updated_time'] = date('Y-m-d H:i:s');
            $content_id = $this->input->post('content_id', true);
            $return = $this->m_content->banner_mod($data, $content_id);
             
            alert("수정되었습니다.","/adm/banner/banner?category_id=".$data['category_id']."&sub_category_id=".$data['sub_category_id']."&type=".$type);
             
        }
    }
}