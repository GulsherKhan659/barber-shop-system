<?php 
require_once 'connection.php';

class BookingService extends Database {
     public function create($booking_id, $service_id) {
        return $this->insert('booking_services', compact('booking_id', 'service_id'));
    }

    public function updateBookingService($data, $where) {
        return $this->update('booking_services', $data, $where);
    }

    public function deleteBookingService($where) {
        return $this->delete('booking_services', $where);
    }

    public function getBookingService($where = []) {
        return $this->select('booking_services', '*', $where);
    }
}

?>