<?php 
require_once __DIR__ . '/../database/connection.php';

class Availibity extends Database {
    public function create($staff_id, $weekday, $start_time, $end_time) {
        $existing = $this->getAvalibities([
            'staff_id' => $staff_id,
            'weekday' => $weekday
        ]);

        $data = compact('staff_id', 'weekday', 'start_time', 'end_time');

        if (!empty($existing)) {
            $where = ['staff_id' => $staff_id, 'weekday' => $weekday];
            return $this->updateAvalibity($data, $where);
        } else {
            return $this->insert('availability', $data);
        }
    }

    public function updateAvalibity($data, $where) {
        return $this->update('availability', $data, $where);
    }

    public function deleteAvalibity($where) {
        return $this->delete('availability', $where);
    }

    public function getAvalibities($where = []) {
        return $this->select('availability', '*', $where);
    }
}

?>