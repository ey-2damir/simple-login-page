<?php
session_start();
require 'db.php';

// التحقق من أن المستخدم مسجل الدخول
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// استرداد بيانات المستخدم
$username = htmlspecialchars($_SESSION['username']);
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// بيانات المستخدم
$student_name = htmlspecialchars($user['student_name']);
$specialization = htmlspecialchars($user['specialization']);
$academic_year = htmlspecialchars($user['academic_year']);
$email = htmlspecialchars($user['email']);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
    <style>
        /* نمط البطاقة */
        .card {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            text-align: center;
        }

        .card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .card h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            margin: 5px 0;
        }
    </style>
</head>
<body>

<div class="card">
    <img src="img/student.png" alt="صورة شخصية"> <!-- صورة افتراضية -->
    <h2>مرحبًا، <?php echo $username; ?>!</h2>
    <p><b>Name </b>: <?php echo $student_name; ?></p>
    <p><b>Major </b>: <?php echo $specialization; ?></p>
    <p><b> Schuljahr </b>: <?php echo $academic_year; ?></p>
    <p><b>Email </b>: <?php echo $email; ?></p>
</div>
<d
<!-- زر تسجيل الخروج -->
<form method="post" action="logout.php" style="text-align: center;">
    <button type="submit">تسجيل الخروج</button>
</form>

</body>
</html>
