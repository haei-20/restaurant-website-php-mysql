<?php
    ob_start();
    session_start();
    header('Content-Type: text/html; charset=utf-8');
    $pageTitle = 'Danh mục thực đơn';

    if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
    {
        include 'connect.php';
        include 'Includes/functions/functions.php'; 
        include 'Includes/templates/header.php';
        include 'Includes/templates/navbar.php';

        // Thông báo kết quả xử lý
        $message = '';
        
        // Xử lý thêm danh mục mới
        if(isset($_POST['add_category'])) {
            $category_name = test_input($_POST['category_name']);
            
            if(empty($category_name)) {
                $message = '<div class="alert alert-warning">Tên danh mục không được để trống!</div>';
            } else {
                // Kiểm tra xem danh mục đã tồn tại chưa
                $checkItem = checkItem("category_name", "menu_categories", $category_name);
                
                if($checkItem != 0) {
                    $message = '<div class="alert alert-warning">Danh mục này đã tồn tại!</div>';
                } else {
                    // Thêm vào cơ sở dữ liệu
                    $stmt = $con->prepare("INSERT INTO menu_categories(category_name) VALUES(?)");
                    $stmt->execute(array($category_name));
                    
                    if($stmt->rowCount() > 0) {
                        $message = '<div class="alert alert-success">Danh mục mới đã được thêm thành công!</div>';
                        // Chuyển hướng để tránh gửi lại form khi làm mới trang
                        header("Location: menu_categories.php");
                        exit();
                    }
                }
            }
        }
        
        // Xử lý xóa danh mục
        if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $category_id = intval($_GET['id']);
            
            // Xóa khỏi cơ sở dữ liệu
            $stmt = $con->prepare("DELETE FROM menu_categories WHERE category_id = ?");
            if($stmt->execute(array($category_id))) {
                $message = '<div class="alert alert-success">Danh mục đã được xóa thành công!</div>';
                // Chuyển hướng để tránh gửi lại lệnh xóa khi làm mới trang
                header("Location: menu_categories.php");
                exit();
            } else {
                $message = '<div class="alert alert-danger">Không thể xóa danh mục!</div>';
            }
        }

        ?>

            <script type="text/javascript">
                var vertical_menu = document.getElementById("vertical-menu");
                var current = vertical_menu.getElementsByClassName("active_link");
                if(current.length > 0) {
                    current[0].classList.remove("active_link");   
                }
                vertical_menu.getElementsByClassName('menu_categories_link')[0].className += " active_link";
            </script>

            <style type="text/css">
                .categories-table {
                    -webkit-box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    text-align: center;
                    vertical-align: middle;
                }
            </style>

        <?php
            echo $message;
            
            // Lấy danh sách danh mục từ cơ sở dữ liệu
            $stmt = $con->prepare("SELECT * FROM menu_categories");
            $stmt->execute();
            $menu_categories = $stmt->fetchAll();
        ?>
            <div class="card">
                <div class="card-header">
                    <?php echo $pageTitle; ?>
                </div>
                <div class="card-body">

                    <!-- NÚT THÊM DANH MỤC MỚI -->

                    <button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_category" data-placement="top">
                        <i class="fa fa-plus"></i> 
                        Thêm danh mục
                    </button>

                    <!-- Modal Thêm Danh Mục Mới -->

                    <div class="modal fade" id="add_new_category" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thêm danh mục mới</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="POST" action="menu_categories.php">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="category_name">Tên danh mục</label>
                                            <input type="text" id="category_name_input" class="form-control" placeholder="Tên danh mục" name="category_name" required>
                                            <div class="invalid-feedback">
                                                <div>Tên danh mục không được để trống!</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-info" name="add_category">Thêm danh mục</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- BẢNG DANH MỤC -->

                    <table class="table table-bordered categories-table">
                        <thead>
                            <tr>
                                <th scope="col">ID Danh mục</th>
                                <th scope="col">Tên danh mục</th>
                                <th scope="col">Quản lý</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($menu_categories as $category) {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $category['category_id'];
                                        echo "</td>";
                                        echo "<td style = 'text-transform:capitalize'>";
                                            echo $category['category_name'];
                                        echo "</td>";
                                        echo "<td>";
                                            ?>
                                                <ul class="list-inline m-0">
                                                    <!-- NÚT XÓA -->
                                                    <li class="list-inline-item" data-toggle="tooltip" title="Xóa">
                                                        <a href="menu_categories.php?action=delete&id=<?php echo $category['category_id']; ?>" class="btn btn-danger btn-sm rounded-0" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục \"<?php echo strtoupper($category['category_name']); ?>\"?');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            <?php
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>  
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