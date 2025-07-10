<?php
require_once 'connection.php';

class Staff extends Database {
    public function createStaff($user_id, $shop_id, $bio) {
        return $this->insert('staff', [
            'user_id' => $user_id,
            'shop_id' => $shop_id,
            'bio' => $bio
        ]);
    }

    public function updateStaff($data, $where) {
        return $this->update('staff', $data, $where);
    }

    public function deleteStaff($where) {
        return $this->delete('staff', $where);
    }

    public function getStaff($where = []) {
        return $this->select('staff', '*', $where);
    }
}


?>