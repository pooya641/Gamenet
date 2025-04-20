<?php
include 'db.php';

$system_id = $_GET['system_id'] ?? 0;

// گرفتن اطلاعات سیستم برای نمایش نام
$system = $conn->query("SELECT * FROM systems WHERE id = $system_id")->fetch_assoc();

// گرفتن لیست جلسات مربوط به این سیستم
$stmt = $conn->prepare("SELECT * FROM sessions WHERE system_id = ? ORDER BY start_time DESC");
$stmt->bind_param("i", $system_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>جزئیات سیستم</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <h1>جزئیات استفاده از سیستم: <?= htmlspecialchars($system['name']) ?></h1>
    <a href="index.php">بازگشت به صفحه اصلی</a>

    <table border="1" cellpadding="10">
        <tr>
            <th>تاریخ</th>
            <th>زمان شروع</th>
            <th>زمان پایان</th>
            <th>مبلغ دریافتی (تومان)</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= date("Y-m-d", strtotime($row['start_time'])) ?></td>
            <td><?= date("H:i:s", strtotime($row['start_time'])) ?></td>
            <td>
                <?= $row['end_time'] ? date("H:i:s", strtotime($row['end_time'])) : "در حال اجرا..." ?>
            </td>
            <td>
                <?= $row['total_amount'] ? number_format($row['total_amount'], 2) : "محاسبه نشده" ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
