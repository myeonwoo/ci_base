<?php
//쿠폰
class M_inventory extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('billing/ST_inventory'); //선생님 관련 API
	}

    /****
        * @Desc     사용자 상품 소유 상태 조회
        * @param    
    *****/
    public function getUserContentsOwn($user_id, $saleinfo_ids = null, $own_status = null, $page=1, $offset=100){
        $excu_list = $this->st_inventory->user_contents_own_saleinfo($user_id, $saleinfo_ids, $own_status, $page, $offset);	
		$result = null;
		
		//정상일 경우.
		if($excu_list["status"] == "200"){
			$result["totalCount"] = $excu_list["result"]["totalCount"];
			if(count($excu_list["result"]["list"])> 0){
				//primary key를 array key로
				foreach ($excu_list["result"]["list"] as $key => $value) {
					$result['list'][$value["saleinfo_id"]] =  $value;
				}
			}
		}
		return $result;
    }
}

