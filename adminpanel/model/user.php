
<?php
require_once 'connection.php';




class User extends Database {
 public function createUser($shop_id, $name, $email, $phone, $password, $role = 'customer') {
        $data = [
            'shop_id' => $shop_id,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role
        ];
        return $this->insert('users', $data);
    }



    public function updateUser($data, $where) {
        return $this->update('users', $data, $where);
    }

    public function deleteUser($where) {
        return $this->delete('users', $where);
    }

    public function getUsers($where = []) {
        return $this->select('users', '*', $where);
    }
}


?>