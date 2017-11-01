<?
class Hierarchy {
	private static $hierarchy;
	private static $lookup;
	// Nodes can be any objects that have IDs (id) and a parent IDs (parent)
	public static function buildTree($nodes){
		// Initialize hierarchy and lookup arrays
		self::$hierarchy = array();
		self::$lookup = array();
		// Walk through nodes
		array_walk($nodes, 'hierarchy::addNode');
		// Return the hierarchical (tree) hierarchy
		return self::$hierarchy;
	}
	private static function addNode($array) {
		$obj = (object) $array;
		
		// Convert string ids returned by PDO to integers
		$nid = (int)$obj->id;
		$pid = (int)$obj->parent;
		// Initialize child array
		$obj->children = array();
		// If top level node, set parent child array to a reference to whole hierarchy
		if($pid === 0){
			// If top level node, set parent child array to whole hierarchy
			$parent = &self::$hierarchy;
			$obj->depth = 1;

		} else {
			// Else, set it to reference to the parent child array
			$parent = &self::$lookup[$pid]->children;
			$tmp = &self::$lookup[$pid]->depth;
			$obj->depth = $tmp + 1;
		}
		// Get ID of new node it child array
		$id = count($parent);
		// Add node to child array
		$parent[] = $obj;
		// Add reference to node in parent child array to the lookup table
		self::$lookup[$nid] = &$parent[$id];
	}
	/* ... */

	public function find_by_name($name)
	{
		foreach (self::$lookup as $key => $item) {
			if ($item->name == $name) {
				return $item;
			}	
		}
		return null;
	}
	public function find_by_names($names)
	{
		if ($names[0] != self::$hierarchy[0]->name) {
			return null;
		}

		$item = self::$hierarchy[0];

		for ($i=1; $i < count($names); $i++) {
			$name = $names[$i];

			$item = $this->find_child($name, $item);

			if (!$item) return $item;

		}
		return $item;
	}
	public function find_child($name, $item)
	{
		foreach ($item->children as $key => $item) {
			if ($name == $item->name) return $item;
		}
		return null;
	}

	public function get_data()
	{
		$data = array();
		$data['hierarchy'] = &self::$hierarchy;
		$data['lookup'] = &self::$lookup;

		return $data;
	}

	public function load_data($rawdata_to_build=array(), $target_id_name, $target_parent_id_name)
	{
		foreach ($rawdata_to_build as $key => &$item) {
			$item['id'] = (int) $item[$target_id_name];

			if ($item[$target_parent_id_name]) {
				$item['parent'] = (int) $item[$target_parent_id_name];
			} else {
				$item['parent'] = 0;
			}
		}

		$this->buildTree($rawdata_to_build);

		return $this->get_data();

		return $data;
	}
	// 경로 노드 찾기
	public function find_path_on_parent_id($target_id)
	{
		$loop_limit = 10;
		$cnt = 0;
		$path = array();

		while ($cnt < $loop_limit) {
			if (!self::$lookup[$target_id]) return array();
			
			$item = self::$lookup[$target_id];
			// return $item;
			array_unshift($path, $item);
			$target_id = $item->parent_id;

			if (!$target_id) return $path;
			$cnt++;
		}

		return array();
	}
	public function find_path_on_parent_id_wo_super_parent($target_id)
	{
		$loop_limit = 10;
		$cnt = 0;
		$path = array();

		while ($cnt < $loop_limit) {
			if (!self::$lookup[$target_id]) return array();
			
			$item = self::$lookup[$target_id];
			
			if ($item->parent_id != 0){
				array_unshift($path, $item);
			}
			$target_id = $item->parent_id;

			if (!$target_id) return $path;
			$cnt++;
		}

		return array();
	}
	// 모든 자식 노드 찾기
	public function get_all_childs($target_id)
	{
		$data = array();

		$data = array_merge($data, self::$lookup[$target_id]->children);
		// return $data;
		foreach (self::$lookup[$target_id]->children as $key => $item) {
			$data = array_merge($data, $item->children);
		}
		return $data;
	}

	public function test()
	{
		$data = array();
		$data['rawdata_to_build'] = array(
			array('id'=>1, 'parent'=>0, 'name'=> 'aaa', 'desc'=> 'sample')
			, array('id'=>2, 'parent'=>1, 'name'=> 'bbb', 'desc'=> 'sample')
			, array('id'=>3, 'parent'=>1, 'name'=> 'ccc', 'desc'=> 'sample')
			, array('id'=>4, 'parent'=>2, 'name'=> 'aaa', 'desc'=> 'sample')
			, array('id'=>5, 'parent'=>4, 'name'=> 'eee', 'desc'=> 'sample')
			, array('id'=>6, 'parent'=>4, 'name'=> 'fff', 'desc'=> 'sample')
			, array('id'=>7, 'parent'=>2, 'name'=> 'ggg', 'desc'=> 'sample')
			, array('id'=>8, 'parent'=>2, 'name'=> 'hhh', 'desc'=> 'sample')
		);

		$this->buildTree($data['rawdata_to_build']);

		$data['get_data'] = $this->get_data();

		$data['item'] = $this->find_by_name('aaa');
		$data['item1'] = $this->find_by_names(array('aaa','bbb','aaa'));

		return $data;
	}
}
