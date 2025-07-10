<?php
require_once 'connection.php';

class Shop extends Database {
     public function createShop($name, $email, $phone, $address, $subdomain) {
        return $this->insert('shops', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'subdomain' => $subdomain,
        ]);
    }

    public function updateShop($data, $where) {
        return $this->update('shops', $data, $where);
    }

    public function deleteShop($where) {
        return $this->delete('shops', $where);
    }

    public function getShops($where = []) {
        return $this->select('shops', '*', $where);
    }
}

?>