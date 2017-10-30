<?php
class Teacher_code extends CI_Controller {
    
    function __construct() {
        parent::__construct();

        define('WIDGET_SKIN', 'admin');
        define('CSS_SKIN', 'admin');
        header("Content-Type: text/html; charset=UTF-8");

        $this->load->model('teacher/m_teacher_code_detail');
        $this->load->model('api/m_teacher'); 

        $this->dataset = array();
		$this->dataset['head'] =  array(
        		'css' => array(
        			'adm/dataTables.bootstrap',
        		),
        		'js' => array(
        			'admin/jquery.dataTables.js',
        			'admin/dataTables.bootstrap.js',
        		),
        );
    }

    //선생님 관리 화면
    function index(){
        //리스트 조회
        $list = $this->m_teacher_code_detail->get_list();

        $data = array(
                "list" => $list
                , "teacher_list" => $this->teacher_list
            );

        widget::run('adm/head', $this->dataset['head']);
        $this->load->view(ADM_F.'/teacher/teacher_code_list', $data);
        widget::run('adm/tail');
    }

    //선생님 등록 form
    function write_form(){        
        $list = $this->m_teacher_code_detail->get_list();
        $cd = $this->input->get('cd', true);

        $maxCodeNum = 30; //최대로 사용할 코드번호
        $emptyCodeList=Array(); //사용하지 않은 코드 리스트 생성(사용된 코드에 이름 add)
        for($i=1;$i<=$maxCodeNum;$i++){
            $emptyCodeList['TCR'.str_pad($i,"3","0",STR_PAD_LEFT)]='TCR'.str_pad($i,"3","0",STR_PAD_LEFT);
        }
        foreach($list as $v){
            $tmpCd = $v['cd'];
            $tmpNm = $v['cd_nm'];
            unset($emptyCodeList[$tmpCd]);
        }

/*        $emptySortList=Array(); //사용하지 않은 순서 리스트 생성(사용된 코드에 이름 add)
        for($i=1;$i<=$maxCodeNum;$i++){
            $emptySortList[$i]=$i;
        }
        foreach($list as $v){
            $tmpSort = $v['sort'];
            $tmpNm = $v['cd_nm'];
            $emptySortList[$tmpSort]=$emptySortList[$tmpSort].'['.$tmpNm.']';
        }*/

        //수정일 경우
        if($cd){
            $info = $this->m_teacher_code_detail->get_info($cd);
            unset($emptyCodeList);            
            $emptyCodeList[$cd]=$cd;
        }

        $data = array(
                "emptyCodeList"=>$emptyCodeList
                //,"emptySortList"=>$emptySortList
                ,"list" => $list
                , "info" => $info
            );

        widget::run('adm/head', $dataset['head']);
        $this->load->view(ADM_F.'/teacher/teacher_code_write', $data);
    }

    //선생님 변경
    function write_proc(){
        //등록전 선생님 등록한 적 있는지 체크
        $data['cd'] = $this->input->post('cd', true);
        $info =  $this->m_teacher_code_detail->get_info($cd);

        if($info && $this->input->post('mode', true) == 'ins' && $data['new_cd'] == $info[0]['new_cd']){
            alert("이미 등록된 선생님입니다.",ADM_F.'/teacher/teacher_code/write_form');
        }

        $old_cd = $this->input->post('old_cd', true);
        $data['cd_nm'] = $this->input->post('cd_nm', true);
        $data['sort'] = $this->input->post('sort', true);
        $data['comment'] = $this->input->post('comment', true);
        $imgdata['cd'] = $this->input->post('cd', true);
        $imgdata['img_url'] = $this->input->post('img_circle', true);
        
        var_dump($this->input->post('special_reason', true));
        
        //등록
        if($this->input->post('mode', true) == 'ins'){
            $data['reg_id'] = $this->session->userdata("adm_mb_id");    
            $data['wdate'] = date('Y-m-d H:i:s');
            $data['upcode'] = 'TCR';
            $data['del_yn']  = 'N';
            $result = $this->m_teacher_code_detail->teacher_detail_ins($data);
            if($result==1){
                $imgdata['type'] = 'CIR';
                $imgResult = $this->m_teacher_code_detail->teacher_img_ins($imgdata);
                $msg = "등록 되었습니다.";
            }else{
                $msg = "정상적으로 등록되지 않았습니다.";
            }

            
        //수정
        }else if($this->input->post('mode', true) == 'mod'){
            $data['reg_id'] = $this->session->userdata("adm_mb_id");    
            $data['wdate'] = date('Y-m-d H:i:s');

            $result = $this->m_teacher_code_detail->teacher_detail_mod($data, $old_cd);
            if($result==1){
                $imgResult = $this->m_teacher_code_detail->teacher_img_mod($imgdata, $old_cd);
                $msg = "수정 되었습니다.";
            }else{
                $msg = "정상적으로 수정되지 않았습니다.";
            }

        }

       alert($msg,ADM_F.'/teacher/teacher_code');
    }

    function del_proc(){
        $data['cd'] = $this->input->get('cd', true);
        $img_id = $this->input->get('img_id', true);

        $data['del_yn'] = 'Y';
        $result = $this->m_teacher_code_detail->teacher_detail_mod($data, $data['cd']);
        if($result==1){
            $imgResult = $this->m_teacher_code_detail->teacher_img_del($img_id,$data['cd']);
            $msg = "삭제 되었습니다.";
        }else{
            $msg = "정상적으로 삭제 되지 않았습니다.";
        }

        alert($msg,ADM_F.'/teacher/teacher_code');
    }
}

