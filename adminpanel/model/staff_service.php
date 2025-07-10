<?php
require_once 'connection.php';


class StaffService extends Database {
    public function create($staff_id, $service_id) {
        return $this->insert('staff_services', compact('staff_id', 'service_id'));
    }


     public function deleteStaffService($where) {
        return $this->delete('staff_services', $where);
    }


    // public function delete($id) {
    //     return $this->delete('staff_services', ['id' => $id]);
    // }

    public function get($id = null) {
        return $this->select('staff_services', '*', $id ? ['id' => $id] : []);
    }
}

?>