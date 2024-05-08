<?php
session_start();
require 'db.php';

// متغير لرسالة الخطأ، يبدأ فارغًا
$error_message = '';

// التحقق مما إذا كانت هناك رسالة في URL (للرسائل الناجحة)
$success_message = isset($_GET['message']) ? $_GET['message'] : '';

// إذا كان المستخدم يحاول تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // تحضير الاستعلام للتحقق من المستخدم
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    if ($stmt) {
        $stmt->bind_param('ss', $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // تسجيل الدخول الناجح
                $_SESSION['username'] = $user['username'];
                header("Location: home.php");
                exit;
            } else {
                // إذا كانت كلمة المرور غير صحيحة
                $error_message = "كلمة المرور غير صحيحة.";
            }
        } else {
            // إذا كان المستخدم أو البريد الإلكتروني غير موجود
            $error_message = "اسم المستخدم أو البريد الإلكتروني غير موجود.";
        }
    } else {
        // إذا كان هناك خطأ في تحضير الاستعلام
        $error_message = "حدث خطأ أثناء تحضير الاستعلام.";
    }
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <!-- أنماط CSS لتنسيق الصفحة -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom right, #68e085, #4464e1);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            background-image: url(img/back2.jpg);
            
            background-repeat: no-repeat;
            background-size: cover;

            align-items: center; /* توسيط الصفحة */
        }

        .login-container {
            background: white;
            padding: 20px;
            border-radius: 10px; /* زوايا مستديرة */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* ظل خفيف */
            text-align: center;
            width: 350px;
        }

        input {
            padding: 10px 0px 10px 0px;
            margin: 10px 0;
            width: 100%; /* عرض 100% */
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: medium;
        }

        button {
            cursor: pointer;
            border-radius: 4px;
            padding: 10px;
            box-shadow: 0 0 20px rgba(104, 85, 224, 0.2);
            background: rgb(255 219 112);
            color: white;
            font-size: large;
            width: 100%;
            transition: 0.4s;
        }

        button:hover {
            background: rgb(226 179 37);
            box-shadow: 0 0 20px rgba(104, 85, 224, 0.6);
        }

        a {
            color: rgba(104, 85, 224, 1);
            text-decoration: none;
            transition: color 0.3s;
        }

        a:hover {
            color: rgba(104, 85, 224, 0.8); /* لون الروابط عند التحويم */
        }

        .error-message {
            color: red; /* لون الخطأ */
            background: rgba(255, 0, 0, 0.1); /* خلفية حمراء خفيفة */
            padding: 10px; 
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>تسجيل الدخول</h2>
        
        <!-- عرض رسالة النجاح إذا كانت موجودة -->
        <?php if ($success_message) { echo "<p class='success-message'>{$success_message}</p>"; } ?>
        
        <!-- عرض رسالة الخطأ إذا كانت موجودة -->
        <?php if ($error_message) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>
        
        <form method="post">
            <input type="text" name="username_or_email" placeholder="اسم المستخدم أو البريد الإلكتروني" required>
            <input type="password" name="password" required placeholder="كلمة المرور">
            <button type="submit">تسجيل الدخول</button>
        </form>

        <p>ليس لديك حساب؟ <a href="register.php">أنشئ حساب جديد</a></p>
    </div>
</body>
</html>
