<?php
include("./database/connection.php");
include("./partial/header.php");

$db = new Database('localhost', 'barberbookingsystem', 'root', '');

$staffList = $db->select(
    "staff s JOIN users u ON s.user_id = u.id",
    "s.id as staff_id, u.name as staff_name"
);

$availability = new Availibity('localhost', 'barberbookingsystem', 'root', '');


$availabilityData = $db->select(
    "availability a 
     JOIN staff s ON a.staff_id = s.id 
     JOIN users u ON s.user_id = u.id",
    "a.staff_id, u.name as staff_name, a.weekday, a.start_time, a.end_time"
);
?>

?>