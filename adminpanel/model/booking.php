<?php 
require_once 'connection.php';

class Booking extends Database {
     public function create($shop_id, $user_id, $staff_id, $appointment_date, $appointment_time, $total_price, $total_duration, $notes = null) {
        return $this->insert('bookings', compact('shop_id', 'user_id', 'staff_id', 'appointment_date', 'appointment_time', 'total_price', 'total_duration', 'notes'));
    }

    public function updateBooking($data, $where) {
        return $this->update('bookings', $data, $where);
    }

    public function deleteBooking($where) {
        return $this->delete('bookings', $where);
    }

    public function getBookings($where = []) {
        return $this->select('bookings', '*', $where);
    }
}

?>