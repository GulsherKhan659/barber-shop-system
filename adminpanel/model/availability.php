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
            return $this->insert('staff_available_slots', $data);
        }
    }

    public function updateAvalibity($data, $where) {
        return $this->update('staff_available_slots', $data, $where);
    }

    public function deleteAvalibity($where) {
        return $this->delete('staff_available_slots', $where);
    }

    public function getAvalibities($where = []) {
        return $this->select('staff_available_slots', '*', $where);
    }
}

?>