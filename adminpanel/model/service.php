<?php 
require_once 'connection.php';

class Service extends Database {
    public function createService($shop_id, $name, $description, $price, $duration_minutes) {
        return $this->insert('services', [
            'shop_id' => $shop_id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'duration_minutes' => $duration_minutes
        ]);
    }

    public function updateService($data, $where) {
        return $this->update('services', $data, $where);
    }

    public function deleteService($where) {
        return $this->delete('services', $where);
    }

    public function getServices($where = []) {
        return $this->select('services', '*', $where);
    }
}

?>