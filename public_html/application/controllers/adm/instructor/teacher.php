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

        
        //등록전 선생님 등록한 적 있는지 체크
        $data['teacher_id'] = $this->input->post('teacher_id', true);
        $info =  $this->m_teacher->get_info(null, $data['teacher_id']);

        if($info && $this->input->post('mode', true) == 'ins' && $data['teacher_id'] == $info['teacher_id']){
            alert_close("이미 등록된 선생님입니다.");
        }

        if($_FILES['main_img_url'][name] ){ // 이미지 업로드 시작 - 업로드할 파일을 첨부할 때에만 동작 //찬샘
            $field_name = "main_img_url";
            $upload_path = "/updata/teacher/";
            $real_path = UPDATE_ROOT_PATH.$upload_path;
            
            //$config['upload_path'] = '/data/NFS/engdangi'.$upload_path;
            $config['upload_path'] = $real_path;
            $config['allowed_types'] = 'jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG';
            $config['max_size'] = '200';
            $config['max_width']  = '3000';
            $config['max_height']  = '3000';
            $config['encrypt_name']  = 'TRUE';
           
            if (!is_dir($real_path)) {
                mkdir($real_path, 0744, TRUE);
            }

            $this->load->library('upload', $config);
        
            if ( ! $this->upload->do_upload($field_name))
            {
                $upload_return = $this->upload->display_errors('','');
                echo "<script>alert('".$upload_return."');history.back();</script>";
                exit();
            }
            else
            {
                $upload_return = $this->upload->data();
            }
            
            $data['main_img_url'] = $upload_path.$upload_return[file_name];
        }

        if($_FILES['img_url'][name] ){ // 이미지 업로드 시작 - 업로드할 파일을 첨부할 때에만 동작 //찬샘
            $field_name = "img_url";
            $upload_path = "/updata/teacher/";
            $real_path = UPDATE_ROOT_PATH.$upload_path;
            
            //$config['upload_path'] = '/data/NFS/engdangi'.$upload_path;
            $config['upload_path'] = $real_path;
            $config['allowed_types'] = 'jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG';
            $config['max_size'] = '200';
            $config['max_width']  = '3000';
            $config['max_height']  = '3000';
            $config['encrypt_name']  = 'TRUE';
           
            if (!is_dir($real_path)) {
                mkdir($real_path, 0744, TRUE);
            }

            $this->load->library('upload', $config);
        
            if ( ! $this->upload->do_upload($field_name))
            {
                $upload_return = $this->upload->display_errors('','');
                echo "<script>alert('".$upload_return."');history.back();</script>";
                exit();
            }
            else
            {
                $upload_return = $this->upload->data();
            }
            
            $data['img_url'] = $upload_path.$upload_return[file_name];
            
        }else{
            $data['img_url'] = $this->input->post('img_url_link', true);
        } //이미지 업로드 끝

        $data['teacher_name'] = $this->teacher_list[$data['teacher_id']]['teacher_name'];
        $data['display_order'] = $this->input->post('display_order', true);
        $data['intro_movie_url'] = $this->input->post('intro_movie_url', true);
        $data['main_movie_url']  = $this->input->post('main_movie_url', true);
        
        $data['history'] = $this->input->post('history', true);
        $data['active_status'] = $this->input->post('active_status', true);
        $data['contents_type'] = $this->input->post('contents_type', true);
        
        $data['img_reg'] = $this->input->post('img_reg', true);
        $data['msg_reg'] = $this->input->post('msg_reg', true);
        
        //특별이유
        foreach($this->input->post('special_reason', true) as $row){
            if($row != null && $row != ""){
                echo $row . "<br/>";
                $special_reason_str .= $row.",";    
            }
        }
        echo substr($special_reason_str, 0, strlen($special_reason_str)-1);

        $special_reason_str = substr($special_reason_str, 0, strlen($special_reason_str)-1);
        $data['special_reason']  = $special_reason_str;

        //등록
        if($this->input->post('mode', true) == 'ins'){
            $data['created_admin_id'] = $this->session->userdata("adm_mb_id");    
            $data['created_time'] = date('Y-m-d H:i:s');    

            //선생님 상세 등록
            $result = $this->m_teacher->teacher_detail_ins($data);   

            $msg = "등록되었습니다.";
        
        //수정
        }else if($this->input->post('mode', true) == 'mod'){
            $data['updated_admin_id'] = $this->session->userdata("adm_mb_id");    
            $data['updated_time'] = date('Y-m-d H:i:s');    

            $teacher_detail_id =  $this->input->post('teacher_detail_id', true);
            $result = $this->m_teacher->teacher_detail_mod($data, $teacher_detail_id);

            $msg = "수정되었습니다.";
        }

       alert_opener($msg);
    }
}

