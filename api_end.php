<?php
include 'db.php';

$system_id = $_POST['system_id'];

$system = $conn->query("SELECT * FROM systems WHERE id = $system_id")->fetch_assoc();
$price = $system['price_per_second'];

$session = $conn->query("SELECT * FROM sessions WHERE system_id = $system_id AND end_time IS NULL ORDER BY start_time DESC LIMIT 1")->fetch_assoc();

$start = strtotime($session['start_time']);
$end = time();
$duration = $end - $start;
$amount = $duration * $price;
$now = date('Y-m-d H:i:s');

$stmt = $conn->prepare("UPDATE sessions SET end_time = ?, total_amount = ? WHERE id = ?");
$stmt->bind_param("sdi", $now, $amount, $session['id']);
$stmt->execute();

echo json_encode([
    "duration" => gmdate("H:i:s", $duration),
    "amount" => number_format($amount, 2)
]);
