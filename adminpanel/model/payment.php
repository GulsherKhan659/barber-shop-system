<?php 
require_once 'connection.php';

class Payment extends Database {
    public function create($booking_id, $user_id, $amount, $currency, $stripe_payment_intent_id, $payment_method, $status = 'pending', $paid_at = null) {
        return $this->insert('payments', compact('booking_id', 'user_id', 'amount', 'currency', 'stripe_payment_intent_id', 'payment_method', 'status', 'paid_at'));
    }

    public function updatePayment($data, $where) {
        return $this->update('payments', $data, $where);
    }

    public function deletePayment($where) {
        return $this->delete('payments', $where);
    }

    public function getPayments($where = []) {
        return $this->select('payments', '*', $where);
    }
}


?>