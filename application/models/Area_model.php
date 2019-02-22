<?php
class Area_model extends CI_Model {

    public function form_insert($data){
        $this->db->insert('areas', array(
            'name' => $data['name'],
        ));
        return $this->db->affected_rows();
    }

    public function form_update($data) {
        $update_data = array(
            'name' => $data['name']
        );

        $this->db->update('areas',$update_data,array('id' => $data['id']));
        return $this->db->affected_rows();
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('areas');

        return $this->db->affected_rows();
    }

    public function getAll() {
        $this->db->select('id,name');
        $this->db->from('areas');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
}