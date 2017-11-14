<?php
class Survey extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('userdata/m_survey');
		$this->data = array();
	}

	public function index()
	{
		$this->survey_list();
	}
	// config 리스트
	public function survey_list()
	{
		$data = &$this->data;
		$params = array();
		$data['params'] = &$params;
		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');

		$data['surveys'] = $this->m_survey->get_config_list();

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$data = $this->commondata->get_adm_header_data($data);
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/content/survey_list', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
	}

	public function survey_write()
	{
		$data = &$this->data;
		$params = array();
		$data['params'] = &$params;
		$params['render_type'] = $this->validate->string($this->input->get_post('render_type', true), 'html');
		$params['data_survey_config_id'] = $this->validate->int($this->input->get_post('data_survey_config_id', true), null);

		$data['data_survey_config'] = $this->m_survey->get_config($params['data_survey_config_id']);

		if ($params['render_type']=='json') {
			$this->output->set_content_type("application/json")->set_output(json_encode($data));return;
		} else {
			$data = $this->commondata->get_adm_header_data($data);
			$this->load->view(ADM_F.'/head', $data);
			$this->load->view(ADM_F.'/content/survey_write', $data);
			$this->load->view(ADM_F.'/tail', $data);
		}
	}

    /**
     * Form Submit
     */
    //배너 등록&수정
    public function submit_survey_config() {
        $data = &$this->data;
        $data['_post'] = $_POST;
        $params = array();
        $data['params'] = &$params;

        $data['content_id'] = $this->validate->int($this->input->get_post('content_id', true), '');

        $params['data_survey_config_id'] = $this->validate->int($this->input->get_post('data_survey_config_id', true), null);
        $params['title'] = $this->validate->string($this->input->get_post('title', true), '');
        $params['gather_id'] = $this->validate->string($this->input->get_post('gather_id', true), null);
        $params['yn_phone'] = $this->validate->string($this->input->get_post('yn_phone', true), null);
        $params['yn_name'] = $this->validate->string($this->input->get_post('yn_name', true), '');
        $params['yn_address'] = $this->validate->string($this->input->get_post('yn_address', true), '');
        $params['yn_email'] = $this->validate->string($this->input->get_post('yn_email', true), null);
        $params['yn_login'] = $this->validate->string($this->input->get_post('yn_login', true), null);
        $params['yn_bank_name'] = $this->validate->string($this->input->get_post('yn_bank_name', true), null);
        $params['yn_bank_account'] = $this->validate->string($this->input->get_post('yn_bank_account', true), '');
        $params['yn_bank_owner'] = $this->validate->string($this->input->get_post('yn_bank_owner', true), '');
        $params['yn_user_comment'] = $this->validate->string($this->input->get_post('yn_user_comment', true), '');
        $params['yn_use'] = $this->validate->string($this->input->get_post('yn_use', true), null);
        $params['yn_file'] = $this->validate->string($this->input->get_post('yn_file', true), null);
        $params['cnt_limit'] = $this->validate->string($this->input->get_post('cnt_limit', true), null);
        $params['cnt_inserted'] = $this->validate->string($this->input->get_post('cnt_inserted', true), '');
        $params['cnt_like'] = $this->validate->string($this->input->get_post('cnt_like', true), '');
        $params['dt_start'] = $this->validate->string($this->input->get_post('dt_start', true), '');
        $params['dt_end'] = $this->validate->string($this->input->get_post('dt_end', true), null);
        $params['dt_created'] = $this->validate->string($this->input->get_post('dt_created', true), null);
        $params['desc'] = $this->validate->string($this->input->get_post('desc', false), '');
        $params['note'] = $this->validate->string($this->input->get_post('note', false), '');


        // 리턴 경로 설정
        $data['return_url'] = "/adm/content/content/html_injector_list?content_category_id={$params['content_category_id']}";

        $this->output->set_content_type("application/json")->set_output(json_encode($data));return;

        if ($data['content_id']) {
            $this->m_content->update($data['content_id'], $params);
            alert("수정되었습니다.",$data['return_url']);
        } else {
            $this->m_content->insert($params);
            alert("입력되었습니다.",$data['return_url']);
        }


        return;
    }
}