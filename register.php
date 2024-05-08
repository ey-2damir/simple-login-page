<?php
require 'db.php';

$registration_success = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $student_name = isset($_POST['student_name']) ? $_POST['student_name'] : '';
    $specialization = isset($_POST['specialization']) ? $_POST['specialization'] : '';
    $academic_year = isset($_POST['academic_year']) ? $_POST['academic_year'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username && $student_name && $specialization && $academic_year && $email && $password) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        if ($stmt) {
            $stmt->bind_param('ss', $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "اسم المستخدم أو البريد الإلكتروني موجود بالفعل.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (username, student_name, specialization, academic_year, email, password) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param('ssssss', $username, $student_name, $specialization, $academic_year, $email, $hashed_password);
                    $stmt->execute();
                    $registration_success = true;

                    header("Location: login.php?message=تم التسجيل بنجاح");
                    exit; // مهم إنهاء التنفيذ بعد إعادة التوجيه
                } else {
                    $error_message = "حدث خطأ أثناء تحضير استعلام التسجيل.";
                }
            }
        } else {
            $error_message = "حدث خطأ أثناء تحضير استعلام التحقق.";
        }
    } else {
        $error_message = "يرجى ملء جميع الحقول المطلوبة.";
    }
}

?>

<!-- HTML و CSS لتنسيق صفحة التسجيل -->
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل جديد</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #68e085, #4464e1); /* تدرج لوني */
            height: 100vh;
            margin: 0;
            display: flex;
            background-image: url(img/bac1.jpg);
            
            background-repeat: no-repeat;
            background-size: cover;

            justify-content: center;
            align-items: center; /* توسيط عمودي وأفقي */
        }

        .register-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* ظل للصندوق */
            text-align: center; /* توسيط النص */
            width: 350px;
            font-size: large;
        }

        input, select {
            padding: 10px 0px 10px 0px; /* مسافة داخلية */
            margin: 10px 0; /* مسافة بين الحقول */
            width: 100%; /* عرض 100% */
            border-radius: 5px; /* زوايا مستديرة */
            border: 1px solid #ccc; /* حدود خفيفة */
        }

        button {
            cursor: pointer;
            border-radius: 4px;
            padding: 10px;
            box-shadow: 0 0 20px rgba(104, 85, 224, 0.2); /* ظل للأزرار */
            background: rgb(236 207 135 / 66%); /* لون الزر */
            color: white; /* لون النص */
            width: 100%; /* عرض الزر بالكامل */
            transition: 0.4s; /* تأثير انتقال */
            font-size: large;
        }

        button:hover {
            background: rgb(215 195 145); /* تغيير اللون عند التحويم */
            box-shadow: 0 0 20px rgba(104, 85, 224, 0.6); /* ظل أكبر */
        }

        a {
            color: rgba(104, 85, 224, 1); /* لون الروابط */
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: rgba(104, 85, 224, 0.8); /* تغيير اللون عند التحويم */
        }

        .error-message {
            color: red; /* لون الخطأ */
            margin-top: 10px; /* مسافة علوية */
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>تسجيل جديد</h2>
        <form method="post">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="text" name="student_name" placeholder="اسم الطالب" required>
            <select name="specialization" required>
                <option value="" disabled selected>اختر التخصص</option>
                <option value="IT">IT</option>
                <option value="ميكاترونكس">ميكاترونكس</option>
                <option value="أوتوترونكس">أوتوترونكس</option>
                <option value="طاقه متجددة">طاقه متجددة</option>
                <option value="اطراف صناعية">اطراف صناعية</option>
            </select>
            <select name="academic_year" required>
                <option value="" disabled selected>اختر السنة الدراسية</option>
                <option value="الأولى">الأولى</option>
                <option value="الثانية">الثانية</option>
                <option value="الثالثة">الثالثة</option>
                <option value="الرابعة">الرابعة</option>
            </select>
            <input type="email" name="email" placeholder="البريد الإلكتروني" required>
            <input type="password" name="password" required placeholder="كلمة المرور">
            <button type="submit">تسجيل</button> <!-- زر التسجيل -->
        </form>

        <p>هل لديك حساب؟ <a href="login.php">تسجيل الدخول هنا</a></p> <!-- رابط تسجيل الدخول -->

        <!-- عرض رسالة الخطأ -->
        <div class="error-message">
            <?php if ($error_message) { echo $error_message; } ?>
        </div>
    </div>
</body>
</html>
