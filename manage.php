<?php
include 'db.php';

// افزودن سیستم جدید
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $service_date = $_POST['service_date'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO systems (name, last_service_date, price_per_second) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $service_date, $price);
    $stmt->execute();
    header("Location: manage.php");
    exit;
}

// حذف سیستم
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM systems WHERE id = $id");
    header("Location: manage.php");
    exit;
}
?>

