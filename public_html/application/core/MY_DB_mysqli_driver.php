<?php
class MY_DB_mysqli_driver extends CI_DB_mysqli_driver {

	final public function __construct($params)
	{
		parent::__construct($params);
	}

	/**
	* Insert_On_Duplicate_Update_Batch
	*
	* Compiles batch insert strings and runs the queries
	* MODIFIED to do a MySQL 'ON DUPLICATE KEY UPDATE'
	*
	* @access public
	* @param string the table to retrieve the results from
	* @param array an associative array of insert values
	* @return object
	*/
	function insert_on_duplicate_update_batch($table = '', $set = NULL, $on_update_field = NULL)
	{
		if ( ! is_null($set))
		{
			$this->set_insert_batch($set);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				//No valid data array.  Folds in cases where keys and values did not match up
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}

		if ($table == '')
		{
			if ( ! isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		// Batch this baby
		for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
		{

			$sql = $this->_insert_on_duplicate_update_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_keys, array_slice($this->ar_set, $i, 100), $on_update_field);
			$this->query($sql);
		}

		$this->_reset_write();


		return TRUE;
	}

	/**
	* Insert_on_duplicate_update_batch statement
	*
	* Generates a platform-specific insert string from the supplied data
	* MODIFIED to include ON DUPLICATE UPDATE
	*
	* @access public
	* @param string the table name
	* @param array the insert keys
	* @param array the insert values
	* @return string
	*/
	private function _insert_on_duplicate_update_batch($table, $keys, $values, $on_update_field)
	{
		if(isset($on_update_field) && is_array($on_update_field)){
			foreach($on_update_field as $update_field){
				$update_fields[] = $update_field.'=VALUES('.$update_field.')';
			}
		}
		else{
			foreach($keys as $key)
				$update_fields[] = $key.'=VALUES('.$key.')';
		}

		return "INSERT INTO ".$table." (".implode(', ', $keys).") VALUES ".implode(', ', $values)." ON DUPLICATE KEY UPDATE ".implode(', ', $update_fields);
	}

	/**
	 * Execute the query
	 *
	 * @access	private called by the base class
	 * @param	string	an SQL query
	 * @return	resource
	 */
	function _execute($sql)
	{
		@mysqli_free_result($this->result_id);

		$sql = $this->_prep_query($sql);
		$retval = @mysqli_multi_query($this->conn_id, $sql);
		$firstResult = @mysqli_store_result($this->conn_id);

		while(@mysqli_more_results($this->conn_id)){   
			if (@mysqli_next_result($this->conn_id))
			{
				$result = @mysqli_use_result($this->conn_id);
				@mysqli_free_result($result);
			}
		}

		if( !$firstResult && !@mysqli_errno($this->conn_id)){
			return true;
		}

		return $firstResult;
	}

}
?>