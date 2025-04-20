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

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت سیستم‌ها</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>مدیریت سیستم‌ها</h1>
    <a href="index.php">بازگشت به صفحه اصلی</a>

    <h2>افزودن سیستم جدید</h2>
    <form method="POST">
        <label>نام سیستم: <input type="text" name="name" required></label><br>
        <label>تاریخ آخرین سرویس: <input type="date" name="service_date" required></label><br>
        <label>هزینه هر ثانیه (تومان): <input type="number" step="0.01" name="price" required></label><br>
        <button type="submit" name="add">افزودن</button>
    </form>

    <h2>لیست سیستم‌ها</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>نام</th>
            <th>تاریخ سرویس</th>
            <th>هزینه (تومان/ثانیه)</th>
            <th>عملیات</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM systems");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['last_service_date'] ?></td>
            <td><?= $row['price_per_second'] ?></td>
            <td>
                <a href="edit_system.php?id=<?= $row['id'] ?>">ویرایش</a> |
                <a href="manage.php?delete=<?= $row['id'] ?>">حذف</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
