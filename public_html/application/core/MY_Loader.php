<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include("../../codeigniter/st/core/ST_loader_lan.php");
/* CI 2.1.0
SKIN 폴더 위치 변경으로 _ci_view_paths 수정
*/
/*class MY_Loader extends CI_Loader {
	function __construct() {
		parent::__construct();
		$this->_ci_view_paths = array(SKIN_PATH => TRUE);
	}

	function view($view, $vars = array(), $return = FALSE) {
		return $this->_ci_load(array('_ci_view' => $view.'.html', '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
	}

	public function database($params = '', $return = FALSE, $active_record = NULL)
	{
		// Grab the super object
		$CI =& get_instance();

		// Do we even need to load the database class?
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
		{
			return FALSE;
		}

		require_once(BASEPATH.'database/DB.php');

		$db = DB($params, $active_record);

		// Load extended DB driver
		$custom_db_driver = config_item('subclass_prefix').'DB_'.$db->dbdriver.'_driver';
		$custom_db_driver_file = APPPATH.'core/'.$custom_db_driver.'.php';

		if (file_exists($custom_db_driver_file))
		{
			require_once($custom_db_driver_file);

			$db = new $custom_db_driver(get_object_vars($db));
		}

		// Return DB instance
		if ($return === TRUE)
		{
			return $db;
		}

		// Initialize the db variable. Needed to prevent reference errors with some configurations
		$CI->db = '';
		$CI->db =& $db;
	}
} */
?>
