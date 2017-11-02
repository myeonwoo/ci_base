<?php
class Teacher extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('instructor/m_teacher');

        $this->data = array();
		
        //선생님 리스트
        $this->data['teachers'] = $this->m_teacher->get_list();
    }

    public function index()
    {
        $this->teacher_list();
    }

    //선생님 관리 화면
    public function teacher_list(){
        //리스트 조회
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['category_selection_1'] = $this->validate->int($this->input->get_post('category_selection_1', true), false);
        $params['sub_category_id'] = $this->validate->int($this->input->get_post('sub_category_id', true), false);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), 'pc');

        // $data['teachers'] = $this->m_teacher->get_list();

        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);

            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/instructor/teacher_list', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }

        return;
    }

    //선생님 등록 form
    public function write(){
        $data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
        $params['teacher_id'] = $this->validate->int($this->input->get_post('teacher_id', true), false);

        $data['teacher'] = array();
        if($params['teacher_id']){
            $data['teacher'] =  $this->m_teacher->get_info($params['teacher_id']);
        }

        // $data['nomenu'] = true;
        if ($params['render_type']=='json') {
            $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        } else {
            $data = $this->commondata->get_adm_header_data($data);

            $this->load->view(ADM_F.'/head', $data);
            $this->load->view(ADM_F.'/instructor/teacher_write', $data);
            $this->load->view(ADM_F.'/tail', $data);
        }
    }

    //선생님 변경
    public function submit_teacher(){
        $data = array();
        $data['_post'] = $_POST;
        $params = array();
        $data['params'] = &$params;

        $data['id'] = $this->validate->int($this->input->get_post('id', true), null);

        $params['teacher_id'] = $this->validate->string($this->input->get_post('teacher_id', false), '');
        $params['teacher_name'] = $this->validate->string($this->input->get_post('teacher_name', false), '무명');
        $params['img1_url'] = $this->validate->string($this->input->get_post('img1_url', false), '');
        $params['img1_link'] = $this->validate->string($this->input->get_post('img1_link', false), '');
        $params['img1_link_type'] = $this->validate->string($this->input->get_post('img1_link_type', false), '');
        $params['img1_link_html'] = $this->validate->string($this->input->get_post('img1_link_html', false), '');
        $params['img1_link_target'] = $this->validate->string($this->input->get_post('img1_link_target', false), '');
        $params['img2_url'] = $this->validate->string($this->input->get_post('img2_url', false), '');
        $params['img2_link'] = $this->validate->string($this->input->get_post('img2_link', false), '');
        $params['img2_link_type'] = $this->validate->string($this->input->get_post('img2_link_type', false), '');
        $params['img2_link_html'] = $this->validate->string($this->input->get_post('img2_link_html', false), '');
        $params['img2_link_target'] = $this->validate->string($this->input->get_post('img2_link_target', false), '');
        $params['img3_url'] = $this->validate->string($this->input->get_post('img3_url', false), '');
        $params['img3_link'] = $this->validate->string($this->input->get_post('img3_link', false), '');
        $params['img3_link_type'] = $this->validate->string($this->input->get_post('img3_link_type', false), '');
        $params['img3_link_html'] = $this->validate->string($this->input->get_post('img3_link_html', false), '');
        $params['img3_link_target'] = $this->validate->string($this->input->get_post('img3_link_target', false), '');
        $params['movie1_url'] = $this->validate->string($this->input->get_post('movie1_url', false), '');
        $params['movie2_url'] = $this->validate->string($this->input->get_post('movie2_url', false), '');
        $params['desc_main'] = $this->validate->string($this->input->get_post('desc_main', false), '');
        $params['desc_motto'] = $this->validate->string($this->input->get_post('desc_motto', false), '');
        $params['desc_spcialty'] = $this->validate->string($this->input->get_post('desc_spcialty', false), '');
        $params['desc_history'] = $this->validate->string($this->input->get_post('desc_history', false), '');
        $params['yn_used'] = $this->validate->string($this->input->get_post('yn_used', false), 0);
        $params['order'] = $this->validate->string($this->input->get_post('order', false), 100);
        $params['yn_deleted'] = $this->validate->string($this->input->get_post('yn_deleted', false), null);

        // $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
        if ($data['id']) {
            $data['result'] = $this->m_teacher->update($data['id'], $params);
            alert("수정되었습니다.","/adm/instructor/teacher");
        } else {
            $this->m_teacher->insert($params);
            alert("입력되었습니다.","/adm/instructor/teacher");
        }
        return;
    }
}

