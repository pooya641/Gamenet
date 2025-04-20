<?php
include 'db.php';

$id = $_GET['id'] ?? 0;

// گرفتن اطلاعات سیستم
$stmt = $conn->prepare("SELECT * FROM systems WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$system = $result->fetch_assoc();

if (!$system) {
    die("سیستم مورد نظر پیدا نشد!");
}

// ذخیره ویرایش
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $service_date = $_POST['service_date'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE systems SET name = ?, last_service_date = ?, price_per_second = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $name, $service_date, $price, $id);
    $stmt->execute();

    header("Location: manage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش سیستم</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>ویرایش سیستم <?= htmlspecialchars($system['name']) ?></h1>
    <form method="POST">
        <label>نام سیستم
            <input type="text" name="name" value="<?= htmlspecialchars($system['name']) ?>" required>
        </label><br>
        <label>تاریخ آخرین سرویس
            <input type="date" name="service_date" value="<?= $system['last_service_date'] ?>" required>
        </label><br>
        <label>هزینه هر ثانیه (تومان)
            <input type="number" step="0.01" name="price" value="<?= $system['price_per_second'] ?>" required>
        </label><br>
        <button type="submit">ذخیره</button>
    </form>

    <a href="manage.php">بازگشت</a>
</body>
</html>
