<?php 

include '../connect.php'; 
include '../Includes/functions/functions.php';

// Kiểm tra kết nối cơ sở dữ liệu
if (!$con) {
    die("<div class='alert alert-danger'>Không thể kết nối đến cơ sở dữ liệu!</div>");
}  

// Xử lý thêm ảnh
if (isset($_POST['do']) && $_POST['do'] == "Add") {
    $image_name = htmlspecialchars($_POST['image_name'], ENT_QUOTES, 'UTF-8');

    // Kiểm tra xem tệp ảnh có được tải lên không
    if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] == 0) {
        $image = $_FILES['gallery_image'];

        // Đường dẫn lưu tệp
        $target_dir = "../Uploads/images/";
        $target_file = $target_dir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra định dạng tệp
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "<div class='alert alert-warning'>Chỉ chấp nhận các định dạng JPG, JPEG, PNG!</div>";
            exit();
        }

        // Di chuyển tệp vào thư mục đích
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            // Lưu thông tin vào cơ sở dữ liệu
            $stmt = $con->prepare("INSERT INTO image_gallery (image_name, image) VALUES (?, ?)");
            if ($stmt->execute([$image_name, basename($image["name"])])) {
                echo "<div class='alert alert-success'>Ảnh đã được thêm thành công!</div>";
                echo "<script>setTimeout(function() { window.location.reload(); }, 2000);</script>";
            } else {
                // Hiển thị lỗi nếu không lưu được vào cơ sở dữ liệu
                $errorInfo = $stmt->errorInfo();
                echo "<div class='alert alert-danger'>Không thể lưu thông tin ảnh vào cơ sở dữ liệu: " . $errorInfo[2] . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Đã xảy ra lỗi khi tải ảnh lên!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Vui lòng chọn tệp ảnh để tải lên!</div>";
    }
}

// Xử lý xóa ảnh
if (isset($_POST['do']) && $_POST['do'] == "Delete") {
    $image_id = $_POST['image_id'];

    // Lấy tên file ảnh từ cơ sở dữ liệu
    $stmt = $con->prepare("SELECT image FROM image_gallery WHERE image_id = ?");
    $stmt->execute([$image_id]);
    $image = $stmt->fetchColumn();

    if ($image) {
        // Xóa file ảnh khỏi thư mục
        $file_path = "../Uploads/images/" . $image;
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Xóa ảnh khỏi cơ sở dữ liệu
        $stmt = $con->prepare("DELETE FROM image_gallery WHERE image_id = ?");
        if ($stmt->execute([$image_id])) {
            echo "<div class='alert alert-success'>Ảnh đã được xóa thành công!</div>";
        } else {
            echo "<div class='alert alert-danger'>Không thể xóa ảnh khỏi cơ sở dữ liệu!</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Không tìm thấy ảnh để xóa!</div>";
    }
}
?>