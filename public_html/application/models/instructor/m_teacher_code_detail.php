<?php
class M_teacher_code_detail extends CI_Model{
    function __construct(){
        parent::__construct();
        $this->china_db = $this->load->database("new_china", TRUE);
    }

    //선생님 코드 리스트
    function get_list($upcode="TCR"){
        $this->china_db->where('del_yn', 'N');
        $this->china_db->where('upcode', $upcode);
        $this->china_db->join('SUB_IMG','COMMON_CODE_DETAIL.cd=SUB_IMG.cd');

        $this->china_db->order_by('sort', 'DESC');
        $cursor = $this->china_db->get('COMMON_CODE_DETAIL');
        $result = $cursor->result_array();
        
        $cursor->free_result();

        return $result;
    }

    //선생님 상세 정보
    function get_info($cd, $upcode="TCR"){        
        $this->china_db->where('del_yn', 'N');
        $this->china_db->where('upcode', $upcode);
        $this->china_db->where('COMMON_CODE_DETAIL.cd', $cd);
        $this->china_db->join('SUB_IMG','COMMON_CODE_DETAIL.cd=SUB_IMG.cd','left');

        $this->china_db->order_by('sort', 'DESC');
        $cursor = $this->china_db->get('COMMON_CODE_DETAIL');
        $result = $cursor->result_array();
        
        $cursor->free_result();

        return $result;
    }

    //선생님 등록
    function teacher_detail_ins($data){
        $result = $this->china_db->insert('COMMON_CODE_DETAIL', $data);
        return $result;
    }

    //선생님 수정
    function teacher_detail_mod($data, $cd){
        $this->china_db->where('del_yn', 'N');
        $this->china_db->where('COMMON_CODE_DETAIL.cd', $cd);
        $result = $this->china_db->update('COMMON_CODE_DETAIL', $data);
        
        return $result;
    }

    //선생님 이미지 등록
    function teacher_img_ins($data){
        $result = $this->china_db->insert('SUB_IMG', $data);
        return $result;
    }

    //선생님 이미지 수정
    function teacher_img_mod($data, $cd){
        $this->china_db->where('SUB_IMG.cd', $cd);
        $result = $this->china_db->update('SUB_IMG', $data);
        
        return $result;
    }    
    function teacher_img_del($img_id,$cd){
        $data = array(
            'cd'=>$cd
            ,'img_id'=>$img_id
            );
        $result = $this->china_db->delete('SUB_IMG', $data);
        
        return $result;
    }
}