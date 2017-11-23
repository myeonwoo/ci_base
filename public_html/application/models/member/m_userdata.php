<?php

class M_userdata extends CI_Model{
	function __construct(){
        parent::__construct();
        $this->member_host = (_IS_DEV_QA) ? "https://qa-member.dangi.co.kr" : "https://member.dangi.co.kr";

		$this->load->library('member/MapiRestClient', array('encode' => 'UTF-8') );
    }
    public function api_user_gather_reg($dataset)
    {
    	// $client_ip = get_client_ipaddress();
        // 필수값
    	$gather_id = (isset($dataset['gather_id'])) ? $dataset['gather_id'] : null;
        $user_id = (isset($dataset['user_id'])) ? $dataset['user_id'] : null;
        $client_ip = (isset($dataset['client_ip'])) ? $dataset['client_ip'] : null;
        if (!$gather_id|| !$user_id || !$client_ip) {
            return null;
        }

    	$config = array(
            'url' => $this->member_host . "/api/gather/user_gather_reg"
            ,'method' => 'PUT'
            ,'data' => array(
                'gather_id' => $gather_id
                ,'dangi_code' => 'engdangi'
                ,'client_ip' => $client_ip
            )
            ,'param' => array(
                'gather_id' => $gather_id
                ,'dangi_code' => 'engdangi'
                ,'client_ip' => $client_ip
                ,'user_id' => $dataset['user_id']
            )
        );

        if (isset($dataset['name'])) $config['param']['name'] = $dataset['name'];
        if (isset($dataset['phone'])) $config['param']['phone'] = $dataset['phone'];
        if (isset($dataset['email'])) $config['param']['email'] = $dataset['email'];
        if (isset($dataset['address'])) $config['param']['address'] = $dataset['address'];
        if (isset($dataset['bankname'])) $config['param']['bankname'] = $dataset['bankname'];
        if (isset($dataset['bankaccount'])) $config['param']['bankaccount'] = $dataset['bankaccount'];
        if (isset($dataset['bankowner'])) $config['param']['bankowner'] = $dataset['bankowner'];
        // return $config;

        $this->mapirestclient->setFingerData($config['url'], $config['method'], $config['param'], $config['data']);
        $data['result'] = $this->mapirestclient->execute();

        return $data['result'];
    }
}