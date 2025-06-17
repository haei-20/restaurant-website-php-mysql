<?php
    session_start();
    $pageTitle = 'Thay đổi mật khẩu';

    // Kiểm tra đăng nhập
    if(!isset($_SESSION['username_restaurant_qRewacvAqzA']) || !isset($_SESSION['password_restaurant_qRewacvAqzA']))
    {
        header('Location: index.php');
        exit();
    }

    include 'connect.php';
    include 'Includes/functions/functions.php';
    include 'Includes/templates/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="text-center">Thay đổi mật khẩu</h4>
                </div>
                <div class="card-body">
                    <?php
                    if(isset($_POST['change_password'])) {
                        $current_password = test_input($_POST['current_password']);
                        $new_password = test_input($_POST['new_password']);
                        $confirm_password = test_input($_POST['confirm_password']);
                        $errors = [];

                        // Kiểm tra mật khẩu hiện tại
                        $stmt = $con->prepare("SELECT password FROM users WHERE user_id = ?");
                        $stmt->execute(array($_SESSION['userid_restaurant_qRewacvAqzA']));
                        $row = $stmt->fetch();
                        
                        if(sha1($current_password) !== $row['password']) {
                            $errors[] = "Mật khẩu hiện tại không đúng!";
                        }

                        // Kiểm tra mật khẩu mới
                        if(strlen($new_password) < 6) {
                            $errors[] = "Mật khẩu mới phải có ít nhất 6 ký tự!";
                        }

                        // Kiểm tra xác nhận mật khẩu
                        if($new_password !== $confirm_password) {
                            $errors[] = "Mật khẩu xác nhận không khớp!";
                        }

                        if(empty($errors)) {
                            // Cập nhật mật khẩu mới
                            $hashed_password = sha1($new_password);
                            $stmt = $con->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                            if($stmt->execute(array($hashed_password, $_SESSION['userid_restaurant_qRewacvAqzA']))) {
                                echo '<div class="alert alert-success">Mật khẩu đã được thay đổi thành công!</div>';
                                // Cập nhật session
                                $_SESSION['password_restaurant_qRewacvAqzA'] = $new_password;
                            } else {
                                echo '<div class="alert alert-danger">Có lỗi xảy ra khi thay đổi mật khẩu!</div>';
                            }
                        } else {
                            foreach($errors as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                    }
                    ?>

                    <form method="POST" action="change_password.php">
                        <div class="form-group">
                            <label>Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" name="change_password" class="btn btn-primary">Thay đổi mật khẩu</button>
                            <a href="dashboard.php" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Includes/templates/footer.php'; ?> 