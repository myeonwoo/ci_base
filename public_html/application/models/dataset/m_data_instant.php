<?php
class M_data_instant extends CI_Model{

    function __construct(){
        parent::__construct();
        $this->new_china = $this->load->database('new_china', TRUE);
    }
    public function insert_data($data)
    {
        $this->new_china->insert('DATA_INSTANT', $data);
        return $this->new_china->insert_id();
    }
    public function get_data_on_type($category)
    {
        $this->new_china->where("category", $category);
        $this->new_china->order_by("order", "ASC");
        $query = $this->new_china->get('DATA_INSTANT');

        return $query->result_array();
    }

    public function save($data_instant_id, $data)
    {
        $this->new_china->where("data_instant_id", $data_instant_id);
        return $this->new_china->update('DATA_INSTANT', $data);
    }
}