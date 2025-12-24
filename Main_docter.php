<?php
session_start();
require_once "db_connect.php";

// تأكد أن الطبيب مسجل دخول
if (!isset($_SESSION['doctor_id'])) {
    die("هذا المستخدم ليس طبيبًا.");
}

$doctorID = $_SESSION['doctor_id'];


$doctorID = $_SESSION['doctor_id'];

// جلب المواعيد للطبيب الحالي مع اسم المريض بحروف صغيرة
$query = "SELECT 
            a.appointmentID, 
            a.appointmentDate, 
            a.appointmentTime, 
            a.status, 
            u.username AS patientName
          FROM appointment a
          JOIN userweb u ON a.userID = u.userID
          WHERE a.doctorID = ?
          ORDER BY a.appointmentDate, a.appointmentTime";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctorID);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مواعيدي</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
<div class="Main_docter">
    <h1>مواعيدي</h1>

    <?php if (count($appointments) === 0): ?>
        <p>لا توجد مواعيد حتى الآن.</p>
    <?php else: ?>
        <?php foreach ($appointments as $app): ?>
            <div class="appointment-card">
                <p>
                      اسم المريض: <?= htmlspecialchars($app['patientName'] ?? 'غير متوفر') ?><br>
                    التاريخ: <?= htmlspecialchars($app['appointmentDate']) ?><br>
                    الوقت: <?= htmlspecialchars($app['appointmentTime']) ?><br>
                    الحالة: <?= htmlspecialchars($app['status']) ?>
                </p>
                <form method="post" action="update_status.php">
                    <input type="hidden" name="appointmentID" value="<?= $app['appointmentID'] ?>">
                    <select name="status">
                        <option value="حضر">حضر</option>
                        <option value="لم يحضر">لم يحضر</option>
                    </select>
                    <button type="submit">تحديث</button>
                </form>
            </div>
            <br>
        <?php endforeach; ?>
    <?php endif; ?>


    <form method="post" action="Doctor_appointments.php">
        <input type="hidden" name="doctorID" value="<?= $doctorID ?>">
        <button type="submit" style="padding:10px 20px; font-size:16px; margin-top:20px;">إضافة أوقات  </button>
   </form>

</div>
<nav class="bottom-navbar">
        <a href="settings_doctor.html" class="nav-item">
            <span>الإعدادات</span>
        </a>
        
        
        <a href="Main_patient.php" class="nav-item">
            <span>الصفحة الرئيسية</span>
</nav>
</body>
</html>
