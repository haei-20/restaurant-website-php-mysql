<?php
    session_start();
    $pageTitle = 'Quên mật khẩu';

    include 'connect.php';
    include 'Includes/functions/functions.php';
    include 'Includes/templates/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header">
                    <h4 class="text-center">Quên mật khẩu</h4>
                </div>
                <div class="card-body">
                    <?php
                    if(isset($_POST['reset_password'])) {
                        $username = test_input($_POST['username']);
                        $email = test_input($_POST['email']);
                        $errors = [];

                        // Kiểm tra username và email có tồn tại không
                        $stmt = $con->prepare("SELECT user_id FROM users WHERE username = ? AND email = ?");
                        $stmt->execute(array($username, $email));
                        $row = $stmt->fetch();

                        if(!$row) {
                            $errors[] = "Tên đăng nhập hoặc email không đúng!";
                        }

                        if(empty($errors)) {
                            // Tạo mật khẩu mới ngẫu nhiên
                            $new_password = substr(md5(rand()), 0, 8);
                            $hashed_password = sha1($new_password);

                            // Cập nhật mật khẩu mới
                            $stmt = $con->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                            if($stmt->execute(array($hashed_password, $row['user_id']))) {
                                echo '<div class="alert alert-success">Mật khẩu mới của bạn là: <strong>' . $new_password . '</strong><br>Vui lòng đăng nhập và thay đổi mật khẩu!</div>';
                            } else {
                                echo '<div class="alert alert-danger">Có lỗi xảy ra khi đặt lại mật khẩu!</div>';
                            }
                        } else {
                            foreach($errors as $error) {
                                echo '<div class="alert alert-danger">' . $error . '</div>';
                            }
                        }
                    }
                    ?>

                    <form method="POST" action="forgot_password.php">
                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" name="reset_password" class="btn btn-primary">Đặt lại mật khẩu</button>
                            <a href="index.php" class="btn btn-secondary">Quay lại đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'Includes/templates/footer.php'; ?> 