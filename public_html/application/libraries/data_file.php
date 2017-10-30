<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/****
	* @Desc 	파일 업로드
	* @param 	DATA_FILE
	* @Table	
	* @Author	mw.lim
*****/
class Data_file {

	function Data_file( )
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('file');

		$data = array(
			'allowed_types' => 'jpg|jpeg|gif|pdf|png|JPG|JPEG|GIF|PNG'
		);
		if(_IS_DEV){
			$data['path_prefix'] = $_SERVER["DOCUMENT_ROOT"];
		} else if(_IS_QA) {
			$data['path_prefix'] = '/var/www/'.DANGI.'/public_html';
        }
        else{
			// $data['path_prefix'] = 'data/NFS/e
			$data['path_prefix'] = '/data/NFS/'.DANGI;
        }

        $data['access_path'] = "/data/data_file/".date('Y-m-d')."/";
        $data['upload_path'] = $data['path_prefix'] . $data['access_path'];
        $data['config']['upload_path'] = $data['upload_path'];
    	$data['config']['allowed_types'] = $data['allowed_types'];
    	$data['config']['max_size'] = '3000';
    	// $data['config']['max_width']  = '30000';
    	// $data['config']['max_height']  = '30000';
    	$data['config']['encrypt_name']  = 'TRUE';

    	$this->data = $data;
	}
	/****
		* @Desc 	form에서 제출된 field_name 으로 파일 업로드
		* @param 	
		* @Table	
		* @Author	mw.lim
	*****/
	public function upload_file($field_name = null)
	{
		$data = &$this->data;
		$data['update_data_file'] = null;

		if (!is_dir($data['config']['upload_path'])) {
    		mkdir($data['config']['upload_path'], 0777, TRUE);
    	}
		if ($_FILES[$field_name]['name']) {
			$data['field_name'] = $field_name;

	    	// TODO: 파일 업로드 && 업데이트 data_file
			$this->CI->load->library('upload', $data['config']);
			if ( ! $this->CI->upload->do_upload($field_name))
	    	{
	    		$data['upload_return'] = $this->CI->upload->display_errors('','');
	    	}
	    	else
	    	{
	    		$data['upload_return'] = $this->CI->upload->data();

	    		$data['update_data_file']['type'] = $data['upload_return']['image_type'];
	    		$data['update_data_file']['file_name'] = $data['upload_return']['file_name'];
	    		$data['update_data_file']['full_url'] = $data['access_path'] . $data['upload_return']['file_name'];

	    		return $data['update_data_file']['full_url'];
	    	}
		}

		return null;
	}
	/****
		* @Desc 	INSERT / UPDATE
		* @param 	
		* @Table	
		* @Author	mw.lim
	*****/
	public function update($data_file_id, $url, $field_name = null)
	{
		$this->CI->load->model('data/m_data_file');

		$data = &$this->data;
		$data['update_data_file'] = null;
		$data['data_file_id'] = $data_file_id;

		if (!is_dir($data['config']['upload_path'])) {
    		mkdir($data['config']['upload_path'], 0777, TRUE);
    	}
		
		if ($_FILES[$field_name]['name']) {
			$data['field_name'] = $field_name;

	    	// TODO: 파일 업로드 && 업데이트 data_file
			$this->CI->load->library('upload', $data['config']);
			if ( ! $this->CI->upload->do_upload($field_name))
	    	{
	    		$data['upload_return'] = $this->CI->upload->display_errors('','');
	    	}
	    	else
	    	{
	    		$data['upload_return'] = $this->CI->upload->data();

	    		$data['update_data_file']['type'] = $data['upload_return']['image_type'];
	    		$data['update_data_file']['file_name'] = $data['upload_return']['file_name'];
	    		$data['update_data_file']['full_url'] = $data['access_path'] . $data['upload_return']['file_name'];
	    		if ($data_file_id) {
	    			$data['msg'] = 'file copy_update';
	    			// $this->CI->m_data_file->update($data_file_id, $data['update_data_file']);
		    		$data['copy_update1'] = $this->CI->m_data_file->copy_update(1, $data['update_data_file']);
		    		$data['copy_update2'] = $this->CI->m_data_file->copy_update(100, $data['update_data_file']);
	    		} else {
	    			$data['msg'] = 'insert(insert) new';
	    			$data['data_file_id'] = $this->CI->m_data_file->insert($data['update_data_file']);
	    		}

	    	}
		}
		else if ($url) {
			if ($data_file_id) {
				$data['msg'] = 'update(url)';
			} else {
				$data['msg'] = 'insert(url)';
			}
		}
		else if (!$data_file_id && $url) {
			$data['msg'] = 'insert(url)';
		}

		return $data;
	}
}