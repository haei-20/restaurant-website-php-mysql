<?php
    ob_start();
	session_start();

	$pageTitle = 'Thực đơn - Món ăn';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        // Thông báo kết quả xử lý
        $success_message = '';
        $error_message = '';

        // Xử lý các hành động
        $do = '';

        if(isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add','Edit','Delete')))
            $do = $_GET['do'];
        else
            $do = 'Manage';

        // Xử lý xóa menu
        if($do == 'Delete' && isset($_GET['menu_id']) && is_numeric($_GET['menu_id']))
        {
            $menu_id = intval($_GET['menu_id']);
            
            // Xóa khỏi cơ sở dữ liệu
            $stmt = $con->prepare("DELETE from menus where menu_id = ?");
            $result = $stmt->execute(array($menu_id));
            
            if($result)
            {
                $success_message = "Thực đơn đã được xóa thành công!";
            }
            else
            {
                $error_message = "Có lỗi xảy ra khi xóa thực đơn!";
            }
            
            // Chuyển về trang quản lý
            header('Location: menus.php?deleted=1');
            exit();
        }
        
        // Hiển thị thông báo
        if(isset($_GET['deleted']) && $_GET['deleted'] == 1)
        {
            $success_message = "Thực đơn đã được xóa thành công!";
        }
        ?>

            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

            <script type="text/javascript">

                var vertical_menu = document.getElementById("vertical-menu");
                var current = vertical_menu.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_menu.getElementsByClassName('menus_link')[0].className += " active_link";

            </script>

            <style type="text/css">

                .menus-table
                {
                    -webkit-box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                }

                .thumbnail>img 
                {
                    width: 100%;
                    object-fit: cover;
                    height: 300px;
                }

                .thumbnail .caption 
                {
                    padding: 9px;
                    color: #333;
                }

                .menu_form
                {
                    max-width: 750px;
                    margin:auto;
                }

                .panel-X
                {
                    border: 0;
                    -webkit-box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                    box-shadow: 0 1px 3px 0 rgba(0,0,0,.25);
                    border-radius: .25rem;
                    position: relative;
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-orient: vertical;
                    -webkit-box-direction: normal;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    min-width: 0;
                    word-wrap: break-word;
                    background-color: #fff;
                    background-clip: border-box;
                    margin: auto;
                    width: 600px;
                }

                .panel-header-X 
                {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    padding-left: 1.25rem;
                    padding-right: 1.25rem;
                    border-bottom: 1px solid rgb(226, 226, 226);
                }

                .save-header-X 
                {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-align: center;
                    -ms-flex-align: center;
                    align-items: center;
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                    min-height: 65px;
                    padding: 0 1.25rem;
                    background-color: #f1fafd;
                }

                .panel-header-X>.main-title 
                {
                    font-size: 18px;
                    font-weight: 600;
                    color: #313e54;
                    padding: 15px 0;
                }

                .panel-body-X
                {
                    padding: 1rem 1.25rem;
                }

                .save-header-X .icon
                {
                    width: 20px;
                    text-align: center;
                    font-size: 20px;
                    color: #5b6e84;
                    margin-right: 1.25rem;
                }

                /* Drop Zone Styles */
                .drop-zone {
                    width: 100%;
                    height: 300px;
                    padding: 25px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                    font-family: "Quicksand", sans-serif;
                    font-size: 20px;
                    cursor: pointer;
                    color: #cccccc;
                    border: 4px dashed #9e8a78;
                    border-radius: 10px;
                    background-size: cover;
                    background-position: center;
                    background-repeat: no-repeat;
                }

                .drop-zone--over {
                    border-style: solid;
                }

                .drop-zone__prompt {
                    color: #9e8a78;
                }

                .drop-zone__input {
                    display: none;
                }

                .drop-zone__thumb {
                    width: 100%;
                    height: 100%;
                    border-radius: 10px;
                    overflow: hidden;
                    background-color: #cccccc;
                    background-size: cover;
                    position: relative;
                }

                .drop-zone__thumb::after {
                    content: attr(data-label);
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    padding: 5px 0;
                    color: #ffffff;
                    background: rgba(0, 0, 0, 0.75);
                    font-size: 14px;
                    text-align: center;
                }
            </style>

        <?php
            // Hiển thị thông báo thành công hoặc lỗi
            if(!empty($success_message)) {
                echo '<div class="alert alert-success">' . $success_message . '</div>';
            }
            if(!empty($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            }

            if($do == "Manage")
            {
                // Lấy danh mục đã chọn từ form nếu có
                $selected_category = '';
                if(isset($_GET['category'])) {
                    $selected_category = $_GET['category'];
                }
                
                // Truy vấn dựa trên bộ lọc danh mục
                if(!empty($selected_category) && is_numeric($selected_category)) {
                    $stmt = $con->prepare("SELECT * FROM menus m, menu_categories mc 
                                         WHERE mc.category_id = m.category_id 
                                         AND m.category_id = ?");
                    $stmt->execute(array($selected_category));
                } else {
                    $stmt = $con->prepare("SELECT * FROM menus m, menu_categories mc 
                                         WHERE mc.category_id = m.category_id");
                $stmt->execute();
                }
                $menus = $stmt->fetchAll();

            ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $pageTitle; ?>
                    </div>
                    <div class="card-body">

                        <!-- NÚT THÊM THỰC ĐƠN MỚI -->

                        <div class="above-table" style="margin-bottom: 1rem!important;">
                            <div class="row">
                                <div class="col-md-6">
                            <a href="menus.php?do=Add" class="btn btn-success">
                                <i class="fa fa-plus"></i> 
                                <span>Thêm thực đơn mới</span>
                            </a>
                                </div>
                                <div class="col-md-6">
                                    <form method="GET" action="menus.php">
                                        <input type="hidden" name="do" value="Manage">
                                    <div class="form-group" style="margin-bottom: 0;">
                                            <select class="form-control" id="category_filter" name="category" onchange="this.form.submit()">
                                            <option value="">Tất cả danh mục</option>
                                            <?php
                                                $stmt = $con->prepare("SELECT * FROM menu_categories");
                                                $stmt->execute();
                                                $categories = $stmt->fetchAll();
                                                foreach($categories as $category) {
                                                        $selected = ($selected_category == $category['category_id']) ? 'selected' : '';
                                                        echo "<option value='".$category['category_id']."' ".$selected.">".$category['category_name']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- BẢNG THỰC ĐƠN -->

                        <table class="table table-bordered menus-table">
                            <thead>
                                <tr>
                                    <th scope="col">Tên thực đơn</th>
                                    <th scope="col">Loại thực đơn</th>
                                    <th scope="col">Giới thiệu</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Quản lý</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($menus as $menu)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $menu['menu_name'];
                                            echo "</td>";
                                            echo "<td style = 'text-transform:capitalize'>";
                                                echo $menu['category_name'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $menu['menu_description'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo number_format(intval($menu['menu_price']), 0, ',', '.') . ".000đ";
                                            echo "</td>";
                                            echo "<td>";
                                                /****/
                                                    $delete_data = "delete_".$menu["menu_id"];
                                                    $view_data = "view_".$menu["menu_id"];
                                                    ?>
                                                        <ul class="list-inline m-0">

                                                              <!-- NÚT XEM -->

                                                              <li class="list-inline-item" data-toggle="tooltip" title="Xem">
                                                                <button class="btn btn-primary btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $view_data; ?>" data-placement="top" >
                                                                    <i class="fa fa-eye"></i>
                                                                </button>

                                                                <!-- MODAL XEM -->

                                                                <div class="modal fade" id="<?php echo $view_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $view_data; ?>" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-body">
                                                                                
                                                                                <div class="thumbnail" style="cursor:pointer">
                                                                                    <?php $source = "Uploads/images/".$menu['menu_image']; ?>
                                                                                    <img src="<?php echo $source; ?>" >
                                                                                    <div class="caption">
                                                                                        <h3>
                                                                                            <span style="float: right;"><?php echo number_format(intval($menu['menu_price']), 0, ',', '.'); ?>.000đ</span>
                                                                                            <?php echo $menu['menu_name'];?>
                                                                                        </h3>
                                                                                        <p>
                                                                                            <?php echo $menu['menu_description']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <!-- NÚT SỬA -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Chỉnh sửa">
                                                                <button class="btn btn-success btn-sm rounded-0">
                                                                    <a href="menus.php?do=Edit&menu_id=<?php echo $menu['menu_id']; ?>" style="color: white;">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                </button>
                                                            </li>

                                                            <!-- NÚT XÓA -->

                                                            <li class="list-inline-item" data-toggle="tooltip" title="Xóa">
                                                                <a href="menus.php?do=Delete&menu_id=<?php echo $menu['menu_id']; ?>" class="btn btn-danger btn-sm rounded-0" onclick="return confirm('Bạn có chắc chắn muốn xóa thực đơn này?');">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    <?php
                                                /****/
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

            /*** ADD NEW MENU SCRIPT ***/

            elseif($do == 'Add')
            {
                ?>

                    <div class="card">
                        <div class="card-header">
                           Thêm thực đơn mới
                        </div>
                        <div class="card-body">
                            <form method="POST" class="menu_form" action="menus.php?do=Add" enctype="multipart/form-data">
                                <div class="panel-X">
                                    <div class="panel-header-X">
                                        <div class="main-title">
                                        Thêm thực đơn mới
                                        </div>
                                    </div>
                                    <div class="save-header-X">
                                        <div style="display:flex">
                                            <div class="icon">
                                                <i class="fa fa-sliders-h"></i>
                                            </div>
                                            <div class="title-container">Chi tiết</div>
                                        </div>
                                        <div class="button-controls">
                                            <button type="submit" name="add_new_menu" class="btn btn-primary">Lưu</button>
                                        </div>
                                    </div>
                                    <div class="panel-body-X">

                                        <!-- MENU NAME INPUT -->

                                        <div class="form-group">
                                            <label for="menu_name">Tên thực đơn </label></label>
                                            <input type="text" class="form-control" value="<?php echo (isset($_POST['menu_name']))?htmlspecialchars($_POST['menu_name']):'' ?>" placeholder="Tên thực đơn" name="menu_name">
                                            <?php
                                                $flag_add_menu_form = 0;

                                                if(isset($_POST['add_new_menu']))
                                                {
                                                    if(empty(test_input($_POST['menu_name'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Không được để trống.
                                                            </div>
                                                        <?php

                                                        $flag_add_menu_form = 1;
                                                    }
                                                    
                                                }
                                            ?>
                                        </div>
                                        
                                        <!-- MENU CATEGORY INPUT -->

                                        <div class="form-group">
                                            <?php
                                                $stmt = $con->prepare("SELECT * FROM menu_categories");
                                                $stmt->execute();
                                                $rows_categories = $stmt->fetchAll();
                                            ?>
                                            <label for="menu_category">Danh mục</label>
                                            <select class="custom-select" name="menu_category">
                                                <?php
                                                    foreach($rows_categories as $category)
                                                    {
                                                        echo "<option value = '".$category['category_id']."'>";
                                                            echo ucfirst($category['category_name']);
                                                        echo "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- MENU DESCRIPTION INPUT -->

                                        <div class="form-group">
                                            <label for="menu_description">Mô tả</label>
                                            <textarea class="form-control" name="menu_description" id="menu_description" style="resize: none;" onkeyup="countChar(this)"><?php echo (isset($_POST['menu_description']))?htmlspecialchars($_POST['menu_description']):''; ?></textarea>
                                            <div class="text-right" style="margin-top: 5px;">
                                                <span id="charCount">0</span>/200 ký tự
                                            </div>
                                            <?php

                                                if(isset($_POST['add_new_menu']))
                                                {
                                                    if(empty(test_input($_POST['menu_description'])))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                            Cần phải có mô tả.
                                                            </div>
                                                        <?php

                                                        $flag_add_menu_form = 1;
                                                    }
                                                    elseif(strlen(test_input($_POST['menu_description'])) > 200)
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                            Độ dài của phần mô tả phải dưới 200 ký tự.
                                                            </div>
                                                        <?php

                                                        $flag_add_menu_form = 1;
                                                    }
                                                }
                                            ?>
                                        </div>

                                                                <!-- MENU PRICE INPUT -->

                        <div class="form-group">
                            <label for="menu_price">Giá (nghìn đồng)</label>
                            <input type="number" class="form-control" value="<?php echo (isset($_POST['menu_price']))?htmlspecialchars($_POST['menu_price']):'' ?>" placeholder="Ví dụ: 50 = 50.000đ" name="menu_price" min="0" step="1">
                        </div>

                                        <!--MENU IMAGE INPUT -->

                                        <div class="form-group">
                                            <label for="menu_image">Ảnh minh họa</label>
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' name="menu_image" id="add_menu_imageUpload" accept=".png, .jpg, .jpeg" />
                                                    <label for="add_menu_imageUpload"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="add_menu_imagePreview" class="drop-zone">
                                                        <div class="drop-zone__prompt">Kéo thả ảnh vào đây hoặc click để chọn</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php

                                                if(isset($_POST['add_new_menu']) && $_SERVER['REQUEST_METHOD'] == 'POST')
                                                {
                                                    $image_Name = $_FILES['menu_image']['name'];
                                                    $image_allowed_extension = array("jpeg","jpg","png");
                                                    $image_split = explode('.',$image_Name);
                                                    $extesnion = end($image_split);
                                                    $image_extension = strtolower($extesnion);
                                                    
                                                    if(empty($_FILES['menu_image']['name']))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                                Ảnh không được để trống!
                                                            </div>
                                                        <?php

                                                        $flag_add_menu_form = 1;
                                                        
                                                    }
                                                    if(!empty($_FILES['menu_image']['name']) && !in_array($image_extension,$image_allowed_extension))
                                                    {
                                                        ?>
                                                            <div class="invalid-feedback" style="display: block;">
                                                            Định dạng ảnh không hợp lệ. Chỉ chấp nhận JPEG, JPG và PNG!
                                                            </div>
                                                        <?php

                                                        $flag_add_menu_form = 1;
                                                    }
                                                }

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                <?php

                /*** ADD NEW menu ***/

                if(isset($_POST['add_new_menu']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_menu_form == 0)
                {
                    $menu_name = test_input($_POST['menu_name']);
                    $menu_category = $_POST['menu_category'];
                    $menu_price = floatval(test_input($_POST['menu_price']));
                    $menu_description = test_input($_POST['menu_description']);
                    $image = rand(0,100000).'_'.$_FILES['menu_image']['name'];
                    move_uploaded_file($_FILES['menu_image']['tmp_name'],"Uploads/images//".$image);

                    try
                    {
                        $stmt = $con->prepare("insert into menus(menu_name,menu_description,menu_price,menu_image,category_id) values(?,?,?,?,?) ");
                        $stmt->execute(array($menu_name,$menu_description,$menu_price,$image,$menu_category));
                        
                        // Chuyển hướng với thông báo thành công
                        header('Location: menus.php?action=added');
                        exit();
                    }
                    catch(Exception $e)
                    {
                        echo 'Error occurred: ' .$e->getMessage();
                    }
                    
                }
            }

            elseif($do == 'Edit')
            {
                $menu_id = (isset($_GET['menu_id']) && is_numeric($_GET['menu_id']))?intval($_GET['menu_id']):0;

                if($menu_id)
                {
                    $stmt = $con->prepare("Select * from menus where menu_id = ?");
                    $stmt->execute(array($menu_id));
                    $menu = $stmt->fetch();
                    $count = $stmt->rowCount();

                    if($count > 0)
                    {
                        ?>

                        <div class="card">
                            <div class="card-header">
                            Sửa thực đơn
                            </div>
                            <div class="card-body">
                                <form method="POST" class="menu_form" action="menus.php?do=Edit&menu_id=<?php echo $menu['menu_id'] ?>" enctype="multipart/form-data">
                                    <div class="panel-X">
                                        <div class="panel-header-X">
                                            <div class="main-title">
                                                <?php echo $menu['menu_name']; ?>
                                            </div>
                                        </div>
                                        <div class="save-header-X">
                                            <div style="display:flex">
                                                <div class="icon">
                                                    <i class="fa fa-sliders-h"></i>
                                                </div>
                                                <div class="title-container">Chi tiết thực đơn</div>
                                            </div>
                                            <div class="button-controls">
                                                <button type="submit" name="edit_menu_sbmt" class="btn btn-primary">Lưu</button>
                                            </div>
                                        </div>
                                        <div class="panel-body-X">
                                                
                                            <!-- MENU ID -->

                                            <input type="hidden" name="menu_id" value="<?php echo $menu['menu_id'];?>" >

                                            <!-- MENU NAME INPUT -->

                                            <div class="form-group">
                                                <label for="menu_name">Tên thực đơn</label>
                                                <input type="text" class="form-control" value="<?php echo $menu['menu_name'] ?>" placeholder="Tên thực đơn" name="menu_name">
                                                <?php
                                                    $flag_edit_menu_form = 0;

                                                    if(isset($_POST['edit_menu_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['menu_name'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                Tên thực đơn không được để trống.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        
                                            <!-- MENU CATEGORY INPUT -->

                                            <div class="form-group">
                                                <?php
                                                    $stmt = $con->prepare("SELECT * FROM menu_categories");
                                                    $stmt->execute();
                                                    $rows_categories = $stmt->fetchAll();
                                                ?>
                                                <label for="menu_category">Danh mục thực đơn</label>
                                                <select class="custom-select" name="menu_category">
                                                    <?php
                                                        foreach($rows_categories as $category)
                                                        {
                                                            if($category['category_id'] == $menu['category_id'])
                                                            {
                                                                echo "<option value = '".$category['category_id']."' selected>";
                                                                    echo ucfirst($category['category_name']);
                                                                echo "</option>";
                                                            }
                                                            else
                                                            {
                                                                echo "<option value = '".$category['category_id']."'>";
                                                                    echo ucfirst($category['category_name']);
                                                                echo "</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                            </div>

                                            <!-- MENU DESCRIPTION INPUT -->

                                            <div class="form-group">
                                                <label for="menu_description">Mô tả thực đơn</label>
                                                <textarea class="form-control" name="menu_description" id="menu_description_edit" style="resize: none;" onkeyup="countChar(this)" placeholder="Mô tả thực đơn"><?php echo $menu['menu_description']; ?></textarea>
                                                <div class="text-right" style="margin-top: 5px;">
                                                    <span id="charCount_edit">0</span>/200 ký tự
                                                </div>
                                                <?php

                                                    if(isset($_POST['edit_menu_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['menu_description'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                Mô tả thực đơn không được để trống.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                        elseif(strlen(test_input($_POST['menu_description'])) > 200)
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                Độ dài của mô tả phải dưới 200 ký tự.
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>

                                                                        <!-- MENU PRICE INPUT -->

                            <div class="form-group">
                                <label for="menu_price">Giá (nghìn đồng)</label>
                                <input type="number" class="form-control" value="<?php echo floatval($menu['menu_price']); ?>" placeholder="Ví dụ: 50 = 50.000đ" name="menu_price" min="0" step="1">
                                                <?php
                                                    if(isset($_POST['edit_menu_sbmt']))
                                                    {
                                                        if(empty(test_input($_POST['menu_price'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                Giá thực đơn không được để trống.
                                                                </div>
                                                            <?php
                                                            $flag_edit_menu_form = 1;
                                                        }
                                                        elseif(!is_numeric(test_input($_POST['menu_price'])))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                Giá không hợp lệ.
                                                                </div>
                                                            <?php
                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }
                                                ?>
                                            </div>

                                            <!--MENU IMAGE INPUT -->

                                            <div class="form-group">
                                                <label for="menu_image">Ảnh thực đơn</label>
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' name="menu_image" id="edit_menu_imageUpload" accept=".png, .jpg, .jpeg" />
                                                        <label for="edit_menu_imageUpload"></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <?php $source = "Uploads/images/".$menu['menu_image']; ?>
                                                        <div style="background-image: url('<?php echo $source; ?>');" id="edit_menu_imagePreview">
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php

                                                    if(isset($_POST['edit_menu_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST')
                                                    {
                                                        $image_Name = $_FILES['menu_image']['name'];
                                                        $image_allowed_extension = array("jpeg","jpg","png");
                                                        $image_split = explode('.',$image_Name);
                                                        $extesnion = end($image_split);
                                                        $image_extension = strtolower($extesnion);
                                                        
                                                        if(!empty($_FILES['menu_image']['name']) && !in_array($image_extension,$image_allowed_extension))
                                                        {
                                                            ?>
                                                                <div class="invalid-feedback" style="display: block;">
                                                                Định dạng ảnh không hợp lệ. Chỉ chấp nhận JPEG, JPG và PNG!
                                                                </div>
                                                            <?php

                                                            $flag_edit_menu_form = 1;
                                                        }
                                                    }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php

                        /*** EDIT MENU ***/

                        if(isset($_POST['edit_menu_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_menu_form == 0)
                        {
                            $menu_id = test_input($_POST['menu_id']);
                            $menu_name = test_input($_POST['menu_name']);
                            $menu_category = $_POST['menu_category'];
                            $menu_price = floatval(test_input($_POST['menu_price']));
                            $menu_description = test_input($_POST['menu_description']);

                            if(empty($_FILES['menu_image']['name']))
                            {
                                try
                                {
                                    $stmt = $con->prepare("update menus  set menu_name = ?, menu_description = ?, menu_price = ?, category_id = ? where menu_id = ? ");
                                    $stmt->execute(array($menu_name,$menu_description,$menu_price,$menu_category,$menu_id));
                                    
                                    // Chuyển hướng với thông báo thành công
                                    header('Location: menus.php?action=edited');
                                    exit();
                                }
                                catch(Exception $e)
                                {
                                    echo 'Error occurred: ' .$e->getMessage();
                                }
                            }
                            else
                            {
                                $image = rand(0,100000).'_'.$_FILES['menu_image']['name'];
                                move_uploaded_file($_FILES['menu_image']['tmp_name'],"Uploads/images//".$image);
                                try
                                {
                                    $stmt = $con->prepare("update menus  set menu_name = ?, menu_description = ?, menu_price = ?, category_id = ?, menu_image = ? where menu_id = ? ");
                                    $stmt->execute(array($menu_name,$menu_description,$menu_price,$menu_category,$image,$menu_id));
                                    
                                    // Chuyển hướng với thông báo thành công
                                    header('Location: menus.php?action=edited');
                                    exit();
                                }
                                catch(Exception $e)
                                {
                                    echo 'Error occurred: ' .$e->getMessage();
                                }
                            }
                            
                            
                        }

                    }
                    else
                    {
                        header('Location: menus.php');
                    }
                }
                else
                {
                    header('Location: menus.php');
                }
            }


        /*** FOOTER BOTTON ***/

        include 'Includes/templates/footer.php';

    }
    else
    {
        header('Location: index.php');
        exit();
    }

?>

<!-- JS SCRIPT -->

<script type="text/javascript">
    // Character counter function
    function countChar(val) {
        var len = val.value.length;
        var counterId = val.id === 'menu_description' ? 'charCount' : 'charCount_edit';
        document.getElementById(counterId).innerHTML = len;
        
        if (len > 200) {
            val.value = val.value.substring(0, 200);
            document.getElementById(counterId).innerHTML = 200;
        }
    }

    // Initialize counters on page load
    document.addEventListener('DOMContentLoaded', function() {
        var addDesc = document.getElementById('menu_description');
        var editDesc = document.getElementById('menu_description_edit');
        
        if (addDesc) {
            countChar(addDesc);
        }
        if (editDesc) {
            countChar(editDesc);
        }
    });

    // Image preview functionality
    function readURL(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).style.backgroundImage = 'url(' + e.target.result + ')';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Khi người dùng chọn ảnh
    var addMenuImageUpload = document.getElementById('add_menu_imageUpload');
    if (addMenuImageUpload) {
        addMenuImageUpload.addEventListener('change', function() {
            readURL(this, 'add_menu_imagePreview');
        });
    }

    var editMenuImageUpload = document.getElementById('edit_menu_imageUpload');
    if (editMenuImageUpload) {
        editMenuImageUpload.addEventListener('change', function() {
            readURL(this, 'edit_menu_imagePreview');
    });
    }
</script>
