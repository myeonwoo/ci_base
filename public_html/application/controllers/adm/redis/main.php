<?php
class main extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->data = array();
	}

	public function index()
	{
		$this->search();
	}

	public function search()
	{
		$data = $this->commondata->get_adm_header_data($this->data);

		$params = array();
		$data['params'] = &$params;

		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

		$data['redis_keys'] = array(
			array('name'=>'테스트 array - IDC', 'key'=>'engdangi:winter-vacation-lc-201612:id', 'type'=>'array')
			, array('name'=>'테스트 value - IDC', 'key'=>'engdangi:1000-event-201609:counter:2016-08-25', 'type'=>'value')
			, array('name'=>'테스트 array - AWS', 'key'=>'engdangi:winter-vacation-lc-201612:id', 'type'=>'array')
			, array('name'=>'테스트 value - AWS', 'key'=>'engdangi:1000-event-201609:counter:2016-08-25', 'type'=>'value')
		);

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/redis/main/search', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
	}

	public function get_data()
	{
		$callback = $this->input->get('callback', TRUE);

		$data = &$this->data;
        $params = array();
        $data['params'] = &$params;

        $params['key'] = $this->validate->string($this->input->get_post('key', true), null);
        $params['type'] = $this->validate->string($this->input->get_post('type', true), null);


        $tmp = array('callback'=>$callback, 'dataset'=>$data);
        $this->load->view('/common/json', $tmp);return;
        
        // $this->output->set_content_type("application/json")->set_output(json_encode($data));return;
	}
}