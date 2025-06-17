<?php
    ob_start();
    session_start();

    $pageTitle = 'Thông tin Website';

    // Khởi tạo biến $form_flag
    $form_flag = 0;

    if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
    {
        include 'connect.php';
          include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';
        include 'Includes/templates/navbar.php';

        // Thông báo kết quả xử lý
        $message = '';

        // Xử lý cập nhật cài đặt
        if(isset($_POST['save_settings']) && $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $form_flag = 0;
            
            // Kiểm tra dữ liệu đầu vào
            $stmt = $con->prepare("SELECT * FROM website_settings");
            $stmt->execute();
            $options = $stmt->fetchAll();
            
            foreach($options as $option) {
                if(empty($_POST[$option['option_name']])) {
                    $form_flag = 1;
                }
            }
            
            // Nếu dữ liệu hợp lệ
            if($form_flag == 0) {
                foreach($options as $option) {
                    $option_name = $option['option_name'];
                    $option_value = $_POST[$option_name];
                    
                    $stmt = $con->prepare("UPDATE website_settings SET option_value = ? WHERE option_name = ?");
                    $stmt->execute([$option_value, $option_name]);
                }
                
                $message = "<div class='alert alert-success'>Cập nhật thông tin website thành công!</div>";
            }
        }

        // Lấy thông tin cài đặt hiện tại
        $stmt = $con->prepare("SELECT * FROM website_settings");
        $stmt->execute();
        $options = $stmt->fetchAll();

        ?>
        <!-- Định dạng menu active -->
        <script type="text/javascript">
            var vertical_menu = document.getElementById("vertical-menu");
            var current = vertical_menu.getElementsByClassName("active_link");
            if(current.length > 0) {
                current[0].classList.remove("active_link");   
            }
            vertical_menu.getElementsByClassName('settings_link')[0].className += " active_link";
        </script>

        <style type="text/css">
            .website_settings_form {
                max-width: 800px;
                margin: 0 auto;
            }
            .panel-X {
                border: 0;
                -webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                border-radius: .25rem;
                margin-bottom: 20px;
            }
            .panel-header-X {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 20px;
                border-bottom: 1px solid #e2e2e2;
            }
            .main-title {
                font-size: 18px;
                font-weight: 600;
                color: #313e54;
            }
            .save-header-X {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 20px;
                background-color: #f1fafd;
            }
            .panel-body-X {
                padding: 20px;
            }
            .option-label {
                text-transform: capitalize;
                font-weight: 500;
            }
        </style>

            <div class="card">
                <div class="card-header">
                    Cập nhật thông tin website
                   </div>
                <div class="card-body">
                <!-- Hiển thị thông báo nếu có -->
                <?php echo $message; ?>
                
                    <form method="POST" class="website_settings_form" action="website-settings.php">
                           <div class="panel-X">
                            <div class="panel-header-X">
                                <div class="main-title">
                                    Cài đặt
                                </div>
                            </div>
                            <div class="save-header-X">
                                <div style="display:flex">
                                    <div class="icon">
                                        <i class="fa fa-sliders-h"></i>
                                    </div>
                                    <div class="title-container">Chi tiết website</div>
                                </div>
                                <div class="button-controls">
                                    <button type="submit" name="save_settings" class="btn btn-primary">Lưu</button>
                                </div>
                            </div>
                            <div class="panel-body-X">
                            <?php
                                $translations = [
                                    'restaurant_name' => 'Tên nhà hàng',
                                    'restaurant_email' => 'Email nhà hàng',
                                    'restaurant_address' => 'Địa chỉ nhà hàng',
                                    'restaurant_phone' => 'Số điện thoại nhà hàng',
                                    'restaurant_description' => 'Mô tả nhà hàng'
                                ];
                                
                                foreach ($options as $option)
                                {
                                    $label = isset($translations[$option['option_name']]) ? $translations[$option['option_name']] : $option['option_name'];
                                    ?>
                                    <div class="form-group">
                                        <label for="<?php echo $option['option_name'] ?>" class="option-label">
                                        	<?php echo $label; ?>
                                        </label>
                                        <input type="text" value="<?php echo (isset($_POST[$option['option_name']]))?$_POST[$option['option_name']]:$option['option_value']; ?>" name="<?php echo $option['option_name']; ?>" class="form-control" required>
                                        <?php
                                            if(isset($_POST['save_settings']) && empty($_POST[$option['option_name']]))
                                                {
                                                echo "<div class='invalid-feedback' style='display:block'>";
                                                    echo $label . " không được để trống!";
                                                    echo "</div>";
                                            }
                                    	?>
                                    </div>
                                    <?php
                                }
                            ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        <?php
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: index.php');
        exit();
    }
?>