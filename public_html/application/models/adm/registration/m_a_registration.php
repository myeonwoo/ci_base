<?php

class M_a_registration extends CI_Model{
     
    function __construct(){
        parent::__construct();
		$this->new_china = $this-> load -> database('new_china', TRUE); 
        $this->billing = $this->load->database('billing', TRUE);
    }

//     /*
//      * 
//      * param $gift_id  지급된 컨텐츠 id 
//      *		 $gift_member 발급 받은 전체 인원
//      *		 $gift_content 어떤 경로로 지급받았는지
//      */
//     function free_registration_log_insert($type, $registration_id, $user_id, $api_return_message, $memo){
//     	$sql = "
// 				INSERT INTO `FREE_REGISTRATION_LOG`
//                     (
//                      `type`,
//                      `create_time`,
//                      `registration_id`,
//                      `user_id`,
//                      `admin_id`,
//                      `api_return_message`,
//                      `memo`)
// 				VALUES ( ? , NOW(), ? , ? , ? , ? , ?);";
// 		$res = $this->new_china->query($sql, array($type, $registration_id, $user_id, $this->session->userdata('adm_mb_id'),  $api_return_message, $memo));
//        /* echo $this->new_china->last_query();
//         exit;*/
// 		return $res;
//     }

//        function free_point_registration_log_insert($type, $point, $user_id, $api_return_message, $memo){
//         $sql = "
 
//                 INSERT INTO `FREE_POINT_REGISTRATION_LOG`
//                             (
//                              `type`,
//                              `point`,
//                              `user_id`,
//                              `admin_id`,
//                              `api_return_message`,
//                              `memo`,
//                              `create_time`)
//                 VALUES ( ? , ? , ? , ? , ?, ?, NOW());";
//         $res = $this->new_china->query($sql, array($type, $point, $user_id, $this->session->userdata('adm_mb_id'),  $api_return_message, $memo));
//        /* echo $this->new_china->last_query();
//         exit;*/
//         return $res;
//     }

    function get_lecture_info($val1){
        
        
        $sql = "SELECT *
                FROM PRODUCT AS prod INNER JOIN SALEINFO AS sale ON sale.`product_id` = prod.`product_id`
                WHERE sale.saleinfo_id= ? 
                AND sale.`online_yn` = 'Y'
                AND sale.`sale_started_time` <= NOW()
                AND sale.`sale_end_time` >= NOW()
                order by display_order desc";
                
        $result = $this->billing->query($sql, array($val1) );
        $return = $result->result_array();
        
        return $return;
        
    }

}