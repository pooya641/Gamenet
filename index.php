<?php
include 'db.php';
$systems = $conn->query("SELECT * FROM systems");
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>مدیریت گیم‌نت</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <h1>لیست سیستم‌ها</h1>
    <a href="manage.php">مدیریت سیستم‌ها</a>
    <div class="system-container">
        <?php while ($row = $systems->fetch_assoc()): ?>
            <form class="system-box" data-system-id="<?= $row['id'] ?>">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p>هزینه هر ثانیه: <?= $row['price_per_second'] ?> تومان</p>

                <label>مدت (دقیقه):
                    <input type="number" class="duration-input" min="1">
                </label>

                <button type="button" class="start-btn">شروع</button>
                <button type="button" class="end-btn" style="display: none;">پایان</button>

                <div class="countdown">--:--</div>
                <a href="details.php?system_id=<?= $row['id'] ?>">جزئیات</a>
            </form>
        <?php endwhile; ?>

        <script>
            document.querySelectorAll('.system-box').forEach(box => {
                const startBtn = box.querySelector('.start-btn');
                const endBtn = box.querySelector('.end-btn');
                const countdownEl = box.querySelector('.countdown');
                const resultEl = box.querySelector('.result');
                const input = box.querySelector('.duration-input');
                const systemId = box.dataset.systemId;
                let timer;
                let secondsLeft;

                startBtn.addEventListener('click', () => {
                    const duration = parseInt(input.value);
                    if (!duration || duration <= 0) {
                        alert("لطفاً مدت زمان معتبر وارد کنید.");
                        return;
                    }

                    // ذخیره زمان شروع با AJAX
                    fetch('api_start.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `system_id=${systemId}`
                    });

                    secondsLeft = duration * 60;
                    startBtn.style.display = 'none';
                    endBtn.style.display = 'inline-block';

                    timer = setInterval(() => {
                        const min = Math.floor(secondsLeft / 60);
                        const sec = secondsLeft % 60;
                        countdownEl.textContent = `${min}:${sec < 10 ? '0' + sec : sec}`;
                        if (--secondsLeft < 0) {
                            clearInterval(timer);
                            countdownEl.textContent = 'زمان تمام شد!';
                        }
                    }, 1000);
                });

                endBtn.addEventListener('click', () => {
                    clearInterval(timer);

                    fetch('api_end.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `system_id=${systemId}`
                    });

                    endBtn.style.display = 'none';
                    startBtn.style.display = 'inline-block'; // دکمه شروع دوباره نمایش داده شود
                    countdownEl.textContent = '--:--'; // تایمر ریست شود
                    input.value = ''; // ورودی مدت‌زمان پاک شود (اختیاری)
                });

            });
        </script>
    </div>
</body>

</html>