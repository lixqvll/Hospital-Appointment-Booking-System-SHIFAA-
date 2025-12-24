<?php
session_start();
require_once "db_connect.php";

// تأكد أن المريض مسجل دخول
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

// جلب مواعيد المريض مع اسم الطبيب
$query = "
    SELECT 
        a.appointmentID, 
        a.appointmentDate, 
        a.appointmentTime, 
        a.status, 
        d.doctorName
    FROM appointment a
    LEFT JOIN doctor d ON a.doctorID = d.doctorID
    WHERE a.userID = ?
    ORDER BY a.appointmentDate, a.appointmentTime
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID); 

$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مواعيدي</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

<div class="Main_patient-container">
    <h1>مواعيدي</h1>
  <a href="hospital.php" class="book-btn">لحجز الموعد انقر هنا</a>
    <br><br>

    <?php if (!empty($appointments)): ?>
        <?php foreach ($appointments as $app): ?>
            <div class="Appointment-container">
       <p>
 اسم الطبيب: <?= htmlspecialchars($app['doctorName'] ?? 'غير متوفر') ?><br>
    التاريخ: <?= htmlspecialchars($app['appointmentDate']) ?><br>
    الوقت: <?= htmlspecialchars($app['appointmentTime']) ?><br>
    الحالة: <?= htmlspecialchars($app['status']) ?>
</p>

                <div class="actions">
                    <a class="editBtn" 
                       href="edit_appointment.php?id=<?= $app['appointmentID'] ?>">تعديل</a>

                    <a class="cancelBtn" 
                       href="cancel_appointment.php?id=<?= $app['appointmentID'] ?>"
                       onclick="return confirm('هل أنت متأكد أنك تريد إلغاء هذا الموعد؟');">
                       إلغاء
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>لا توجد مواعيد محفوظة حالياً.</p>
    <?php endif; ?>
</div>

<nav class="bottom-navbar">
        <a href="settings.html" class="nav-item">
            <span>الإعدادات</span>
        </a>
        
        <a href="profile_patient.php" class="nav-item">
            <span>الملف الشخصي</span>
        </a>
        
        <a href="Main_patient.php" class="nav-item">
            <span>الصفحة الرئيسية</span>
</nav>
</body>
</html>

