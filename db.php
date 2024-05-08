<?php
$servername = "localhost";
$username = "root"; //  هنا اسم المستخدم الخاص بقاعدة البيانات
$password = ""; //  هنا كلمة المرور الخاصة بقاعدة البيانات
$dbname = "student_management"; // اسم قاعدة البيانات

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("The failure to communicate with the database : " . $conn->connect_error);
}
?>
