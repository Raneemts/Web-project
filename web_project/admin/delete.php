<?php
require_once 'auth_check.php';
require_once '../includes/db.php';

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    // جلب أسماء الصور قبل الحذف
    $stmt = $conn->prepare("SELECT main_image, gallery_image1, gallery_image2, gallery_image3 FROM regions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        // حذف السجل من قاعدة البيانات
        $delStmt = $conn->prepare("DELETE FROM regions WHERE id = ?");
        $delStmt->bind_param("i", $id);

        if ($delStmt->execute()) {
            // حذف الصور من المجلد
            $images = [$row['main_image'], $row['gallery_image1'], $row['gallery_image2'], $row['gallery_image3']];
            foreach ($images as $img) {
                if (!empty($img) && file_exists('../uploads/images/' . $img)) {
                    unlink('../uploads/images/' . $img);
                }
            }
            $_SESSION['flash_message'] = 'تم حذف السجل بنجاح ✓';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash_message'] = 'حدث خطأ أثناء الحذف.';
            $_SESSION['flash_type'] = 'error';
        }
    } else {
        $_SESSION['flash_message'] = 'السجل غير موجود.';
        $_SESSION['flash_type'] = 'error';
    }
} else {
    $_SESSION['flash_message'] = 'طلب غير صالح.';
    $_SESSION['flash_type'] = 'error';
}

header('Location: dashboard.php');
exit;
?>
