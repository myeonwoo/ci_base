<?php
//카테고리 조회
class M_category extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('billing/ST_category'); //선생님 관련 API

        $this->load->driver('cache');
        $this->cache_enabled = (_IS_DEV_QA) ? false : true;
	}
    /****
        * @Desc    분류값 조회 
        * @param    
        
            category: /api/store_categories
                biz_code: SPEAK
    *****/
    public function store_categories(){

        $cacheKey = $this->cache->memcached->get_cache_key(__CLASS__, __FUNCTION__, func_get_args());
        if ($this->cache_enabled) $result = $this->cache->memcached->get($cacheKey);

        if (!$result) {
            $config = array(
                'url'    => BILLING_API_HOST."/api/store_categories"
                ,'method' => 'GET'
                ,'data'   => array(
                    'biz_code'      => BIZ_CODE
                )
            );

            $this->strestclient->setData($config['url'], $config['method'], $config['data']);
            $data = $this->strestclient->execute();

            //정상일 경우.
            $tmp = array();
            if($data["status"] == "200"){
                if(count($data["result"])> 0){
                    $tmp = $data["result"];
                }
            }

            $result = array();
            foreach ($tmp as $key => $item) {
                $result[$item['store_category_id']] = $item;
            }

            $this->cache->memcached->save($cacheKey, $result, 60);
        }


        return $result;
    }

	//상품진열 카테고리 리스트 조회
	function getStoreCategories_by_bizcode($biz_code, $appi=null){
		$excu_list = $this->st_category->getStoreCategories_by_bizcode($biz_code, $appi);	//공통 billing api 호출   
		$result = null;
		
		 //정상일 경우.
        if($excu_list["status"] == "200"){
        	//primary key를 array key로  
        	foreach ($excu_list["result"] as $key => $value) {
        		$result[$value["store_category_id"]] = $value;
        	}
        }

		return $result;
	}	
	
	//상품진열 카테고리 리스트 조회 - 자식 카테고리
	function getStoreCategories_by_bizcode_children($biz_code, $appi=null, $parent_category_id){
		$excu_list = $this->st_category->getStoreCategories_by_bizcode($biz_code, $appi);	//공통 billing api 호출   
		$result = null;

		//정상일 경우.
        if($excu_list["status"] == "200"){
        	//primary key를 array key로  
        	$result = array();
			$i = 0;

        	foreach ($excu_list["result"] as $key => $value) {
        		//해당 상품진열 카테고리의 하위 카테고리들만 조회
        		 if($value['parent_category_id']==$parent_category_id){
                    $result["list"][$value["store_category_id"]] = $value;

                    if($i==0){
                        $store_category_ids = $value['store_category_id'];
                    }else{
                        $store_category_ids .= ",".$value['store_category_id'];
                    }
                    $i++;
                }
        	}
        }
        $result["store_category_ids"] = $store_category_ids;

		return $result;
	}	
}
