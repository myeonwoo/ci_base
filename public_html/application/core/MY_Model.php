<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// DB Replication Model
class MY_model extends CI_Model {
	protected $mdb;

	public function __construct(){
		parent::__construct();

		$this->load->database();
		$this->mdb = $this->load->database('default', TRUE);
	
	}
}
?>