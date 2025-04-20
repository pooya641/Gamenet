<?php
include 'db.php';

$system_id = $_POST['system_id'];
$now = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO sessions (system_id, start_time) VALUES (?, ?)");
$stmt->bind_param("is", $system_id, $now);
$stmt->execute();
