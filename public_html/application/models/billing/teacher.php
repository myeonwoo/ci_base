<?php

class Teacher extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->library('STRestClient', array('encode'=>'UTF-8') );
	}

	public function lectures($teacher_id){
		
        $config = array(
            'url' => BILLING_API_HOST . "/api/teacher/{$teacher_id}/course/saleinfos",
            'method' => 'GET',
            'data' => array(
                'teacher_id'=> $teacher_id,
                'saleinfo_types'=>'ONE',
                'page' => 1,
                'offset' => 20
            )
        );
        $data['config'] = $config;

        $this->strestclient->setData($config['url'], $config['method'], $config['data']);
        $data['list'] =  $this->strestclient->execute();
        $tmp = array();
        foreach ($data['list']['result']['list'] as $key => $item) {
            $tmp[] = $item;
        }
        
        return $tmp;
	}

}



