<?php

include("./database/config.php");

// التحقق من وجود بيانات مرسلة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    // الحصول على البيانات المرسلة
    $content = $_POST['comment'];
    $jobPostingId = $_POST['job_id']; // تأكد من استبدال "job_id" بالاسم الصحيح لحقل الـ ID في نموذج الصفحة
    $userId = 1; // يجب استبدال هذا بمعرف المستخدم الفعلي

    // استعداد الاستعلام لإدخال التعليق إلى قاعدة البيانات
    $sql = "INSERT INTO comments (content, job_posting_id, user_id) VALUES (?, ?, ?)";
    
    // تحضير الاستعلام
    $stmt = $conn->prepare($sql);

    // فحص إذا كان الاستعلام قد تم تجهيزه بنجاح
    if ($stmt) {
        // ربط القيم بالمعلمات في الاستعلام
        $stmt->bind_param("sii", $content, $jobPostingId, $userId);
        
        // تنفيذ الاستعلام
        if ($stmt->execute()) {
            // في حال نجاح إدخال التعليق، يمكنك إرجاع بيانات التعليق كـ JSON
            $response = array(
                'status' => 'success',
                'message' => 'Comment added successfully.'
            );
            echo json_encode($response);
        } else {
            // في حالة فشل إدخال التعليق، يمكنك إرجاع رسالة خطأ كـ JSON
            $response = array(
                'status' => 'error',
                'message' => 'Failed to add comment.'
            );
            echo json_encode($response);
        }
    } else {
        // في حالة فشل تحضير الاستعلام، يمكنك إرجاع رسالة خطأ كـ JSON
        $response = array(
            'status' => 'error',
            'message' => 'Failed to prepare statement.'
        );
        echo json_encode($response);
    }

    // إغلاق الاستعلام
    $stmt->close();
}

// إغلاق اتصال قاعدة البيانات
$conn->close();
?>
