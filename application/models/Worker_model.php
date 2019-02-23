<?php
class Worker_model extends CI_Model {

    public function form_insert($data){
        $this->db->insert('workers', array(
            'name' => $data['name'],
            'admission_date' => $data['admission_date'],
            'position_id' => $data['position_id'],
            'area_id' => $data['area_id']
        ));
        return $this->db->affected_rows();
    }

    public function form_update($data) {
        $update_data = array(
            'name' => $data['name'],
            'admission_date' => $data['admission_date'],
            'position_id' => $data['position_id'],
            'area_id' => $data['area_id']
        );

        $this->db->update('workers',$update_data,array('id' => $data['id']));
        return $this->db->affected_rows();
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('workers');

        return $this->db->affected_rows();
    }

    public function getAll() {
        $this->db->select('workers.id,workers.name,workers.admission_date,workers.area_id,workers.position_id,areas.name as area,positions.name as position');
        $this->db->from('workers');
        $this->db->join('areas','workers.area_id = areas.id');
        $this->db->join('positions','workers.position_id = positions.id');
        $query = $this->db->get();
        $result = $query->result();

        return $result;
    }
}