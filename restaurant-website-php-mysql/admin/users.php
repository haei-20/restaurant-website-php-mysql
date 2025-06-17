<?php
    ob_start();
    session_start();

    $pageTitle = 'Thông tin tài khoản';

    if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
    {
        include 'connect.php';
          include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';
        include 'Includes/templates/navbar.php';

        // Thông báo kết quả xử lý
        $message = '';

        ?>
            <script type="text/javascript">
                var vertical_menu = document.getElementById("vertical-menu");
                var current = vertical_menu.getElementsByClassName("active_link");
                if(current.length > 0) {
                    current[0].classList.remove("active_link");
                }
                vertical_menu.getElementsByClassName('users_link')[0].className += " active_link";
            </script>

        <?php
            $do = '';

            if(isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add','Edit')))
                $do = $_GET['do'];
            else
                $do = 'Manage';

            if($do == "Manage")
            {
                $stmt = $con->prepare("SELECT * FROM users");
                $stmt->execute();
                $users = $stmt->fetchAll();

            ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $pageTitle; ?>
                    </div>
                    <div class="card-body">
                        <!-- Hiển thị thông báo nếu có -->
                        <?php if(!empty($message)) { echo $message; } ?>

                        <!-- BẢNG NGƯỜI DÙNG -->

                        <table class="table table-bordered users-table">
                            <thead>
                                <tr>
                                    <th scope="col">Tên tài khoản</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Họ và tên</th>
                                    <th scope="col">Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($users as $user)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $user['username'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $user['email'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $user['full_name'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo "<button class='btn btn-success btn-sm rounded-0'>";
                                                    echo "<a href='users.php?do=Edit&user_id=".$user['user_id']."' style='color: white;'>";
                                                    echo "<i class='fa fa-edit'></i>";
                                                    echo "</a>";
                                                echo "</button>";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>  
                    </div>
                </div>
            <?php
            }
            # Chỉnh sửa thông tin người dùng
            elseif($do == 'Edit')
            {
                $user_id = (isset($_GET['user_id']) && is_numeric($_GET['user_id']))?intval($_GET['user_id']):0;
                
                if($user_id)
                {
                    $stmt = $con->prepare("Select * from users where user_id = ?");
                    $stmt->execute(array($user_id));
                    $user = $stmt->fetch();
                    $count = $stmt->rowCount();
                    if($count > 0)
                    {
                        // Xử lý form cập nhật
                        if(isset($_POST['edit_user_sbmt']))
                        {
                            $flag_edit_user_form = 0;
                            
                            // Kiểm tra tên tài khoản
                            if(empty(test_input($_POST['user_name']))) {
                                $flag_edit_user_form = 1;
                            }
                            
                            // Kiểm tra họ và tên
                            if(empty(test_input($_POST['full_name']))) {
                                $flag_edit_user_form = 1;
                            }
                            
                            // Kiểm tra email
                            if(empty(test_input($_POST['user_email']))) {
                                $flag_edit_user_form = 1;
                            }
                            
                            // Nếu không có lỗi, cập nhật thông tin
                            if($flag_edit_user_form == 0)
                            {
                                $username = test_input($_POST['user_name']);
                                $full_name = test_input($_POST['full_name']);
                                $user_email = test_input($_POST['user_email']);
                                $user_id = $_POST['user_id'];
                                
                                // Kiểm tra password mới
                                $password = '';
                                $password_query = '';
                                
                                if(!empty($_POST['user_password']) && $_POST['user_password'] != '')
                                {
                                    $password = sha1($_POST['user_password']);
                                    $password_query = ", password = '$password'";
                                }
                                
                                // Cập nhật thông tin trong database
                                $stmt = $con->prepare("UPDATE users SET username = ?, full_name = ?, email = ? $password_query WHERE user_id = ?");
                                $stmt->execute(array($username, $full_name, $user_email, $user_id));
                                
                                // Hiển thị thông báo thành công
                                $message = '<div class="alert alert-success">Thông tin người dùng đã được cập nhật thành công!</div>';
                                
                                // Chuyển hướng về trang quản lý
                                header("Location: users.php?message=updated");
                                exit();
                            }
                        }
                        ?>

                        <div class="card">
                            <div class="card-header">
                            Chỉnh sửa người dùng
                            </div>
                            <div class="card-body">
                                <!-- Hiển thị thông báo nếu có -->
                                <?php if(!empty($message)) { echo $message; } ?>
                                
                                <form method="POST" class="menu_form" action="users.php?do=Edit&user_id=<?php echo $user['user_id'] ?>">
                                    <div class="panel-X">
                                        <div class="panel-header-X">
                                            <div class="main-title">
                                                <?php echo $user['full_name']; ?>
                                            </div>
                                        </div>
                                        <div class="save-header-X">
                                            <div style="display:flex">
                                                <div class="icon">
                                                    <i class="fa fa-sliders-h"></i>
                                                </div>
                                                <div class="title-container">Chi tiết người dùng</div>
                                            </div>
                                            <div class="button-controls">
                                                <button type="submit" name="edit_user_sbmt" class="btn btn-primary">Lưu</button>
                                            </div>
                                        </div>
                                        <div class="panel-body-X">
                                                
                                            <!-- ID Người dùng -->

                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id'];?>" >

                                            <!-- Tên tài khoản -->

                                            <div class="form-group">
                                                <label for="user_name">Tên tài khoản</label>
                                                <input type="text" class="form-control" value="<?php echo $user['username'] ?>" placeholder="Tên tài khoản" name="user_name" required>
                                                <?php
                                                    if(isset($_POST['edit_user_sbmt']) && empty(test_input($_POST['user_name'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Tên tài khoản không được để trống.
                                                                </div>
                                                            <?php
                                                    }
                                                ?>
                                            </div>
                                        
                                            <!-- Họ và tên -->

                                            <div class="form-group">
                                                <label for="full_name">Họ và tên</label>
                                                <input type="text" class="form-control" value="<?php echo $user['full_name'] ?>" placeholder="Họ và tên" name="full_name" required>
                                                <?php
                                                    if(isset($_POST['edit_user_sbmt']) && empty(test_input($_POST['full_name'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    Họ và tên không được để trống.
                                                                </div>
                                                            <?php
                                                    }
                                                ?>
                                            </div>
                                            
                                            <!-- E-mail -->

                                            <div class="form-group">
                                                <label for="user_email">E-mail</label>
                                                <input type="email" class="form-control" value="<?php echo $user['email'] ?>" placeholder="E-mail" name="user_email" required>
                                                <?php
                                                    if(isset($_POST['edit_user_sbmt']) && empty(test_input($_POST['user_email'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                    E-mail không được để trống.
                                                                </div>
                                                            <?php
                                                    }
                                                ?>
                                            </div>

                                            <!-- Password -->

                                            <div class="form-group">
                                                <label for="user_password">Mật khẩu mới</label>
                                                <input type="password" class="form-control" placeholder="Mật khẩu mới" name="user_password">
                                                <div class="form-text text-muted">
                                                    Để trống nếu không muốn thay đổi mật khẩu.
                                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php
                    }
                    else
                    {
                        header('Location: users.php');
                        exit();
                    }
                }
                else
                {
                    header('Location: users.php');
                    exit();
                }
            }

            // Xử lý thông báo từ URL
            if(isset($_GET['message']) && $_GET['message'] == 'updated') {
                echo '<div class="alert alert-success">Thông tin người dùng đã được cập nhật thành công!</div>';
            }

        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: index.php');
        exit();
    }
?>