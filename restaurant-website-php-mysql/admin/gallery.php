<?php
    ob_start();
	session_start();

	$pageTitle = 'Thư viện ảnh';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        // Xử lý thêm ảnh mới
        $message = '';
        if(isset($_POST['add_image'])) {
            $image_name = htmlspecialchars($_POST['image_name'], ENT_QUOTES, 'UTF-8');
            
            // Kiểm tra xem tệp ảnh có được tải lên không
            if(isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] == 0) {
                $image = $_FILES['gallery_image'];

                // Đường dẫn lưu tệp
                $target_dir = "./Uploads/images/";
                $target_file = $target_dir . basename($image["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Kiểm tra định dạng tệp
                $allowed_types = ['jpg', 'jpeg', 'png'];
                if(!in_array($imageFileType, $allowed_types)) {
                    $message = '<div class="alert alert-warning">Chỉ >chấp nhận các định dạng JPG, JPEG, PNG!</div';
                } else {
                    // Di chuyển tệp vào thư mục đích
                    if(move_uploaded_file($image["tmp_name"], $target_file)) {
                        // Lưu thông tin vào cơ sở dữ liệu
                        $stmt = $con->prepare("INSERT INTO image_gallery (image_name, image) VALUES (?, ?)");
                        if($stmt->execute([$image_name, basename($image["name"])])) {
                            $message = '<div class="alert alert-success">Ảnh đã được thêm thành công!</div>';
                            // Chuyển hướng để tránh gửi lại form khi refresh
                            header("Location: gallery.php");
                            exit();
                        } else {
                            // Hiển thị lỗi nếu không lưu được vào cơ sở dữ liệu
                            $errorInfo = $stmt->errorInfo();
                            $message = '<div class="alert alert-danger">Không thể lưu thông tin ảnh vào cơ sở dữ liệu: ' . $errorInfo[2] . '</div>';
                        }
                    } else {
                        $message = '<div class="alert alert-danger">Đã xảy ra lỗi khi tải ảnh lên!</div>';
                    }
                }
            } else {
                $message = '<div class="alert alert-danger">Vui lòng chọn tệp ảnh để tải lên!</div>';
            }
        }
        
        // Xử lý xóa ảnh
        if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
            $image_id = $_GET['id'];
            
            // Lấy tên file ảnh từ cơ sở dữ liệu
            $stmt = $con->prepare("SELECT image FROM image_gallery WHERE image_id = ?");
            $stmt->execute([$image_id]);
            $image = $stmt->fetchColumn();
            
            if($image) {
                // Xóa file ảnh khỏi thư mục
                $file_path = "./Uploads/images/" . $image;
                if(file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Xóa ảnh khỏi cơ sở dữ liệu
                $stmt = $con->prepare("DELETE FROM image_gallery WHERE image_id = ?");
                if($stmt->execute([$image_id])) {
                    $message = '<div class="alert alert-success">Ảnh đã được xóa thành công!</div>';
                    // Chuyển hướng để tránh gửi lại lệnh xóa khi refresh
                    header("Location: gallery.php");
                    exit();
                } else {
                    $message = '<div class="alert alert-danger">Không thể xóa ảnh khỏi cơ sở dữ liệu!</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">Không tìm thấy ảnh để xóa!</div>';
            }
        }
        ?>

            <script type="text/javascript">
                var vertical_menu = document.getElementById("vertical-menu");
                var current = vertical_menu.getElementsByClassName("active_link");
            if(current.length > 0) {
                    current[0].classList.remove("active_link");   
                }
                vertical_menu.getElementsByClassName('gallery_link')[0].className += " active_link";
            </script>

            <style type="text/css">
            .gallery-table td, .gallery-table th {
                    vertical-align: middle;
                    text-align: center;
                }
            .image_gallery {
                    width: 50%;
                }
            .avatar-upload {
                position: relative;
                max-width: 205px;
                margin: 10px auto;
            }
            .avatar-upload .avatar-edit {
                position: absolute;
                right: 12px;
                z-index: 1;
                top: 10px;
            }
            .avatar-upload .avatar-edit input {
                display: none;
            }
            .avatar-upload .avatar-edit label {
                display: inline-block;
                width: 34px;
                height: 34px;
                margin-bottom: 0;
                border-radius: 100%;
                background: #FFFFFF;
                border: 1px solid transparent;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                font-weight: normal;
                transition: all .2s ease-in-out;
            }
            .avatar-upload .avatar-edit label:hover {
                background: #f1f1f1;
                border-color: #d6d6d6;
            }
            .avatar-upload .avatar-edit label:after {
                content: "\f040";
                font-family: 'FontAwesome';
                color: #757575;
                position: absolute;
                top: 10px;
                left: 0;
                right: 0;
                text-align: center;
                margin: auto;
            }
            .avatar-upload .avatar-preview {
                width: 192px;
                height: 192px;
                position: relative;
                border-radius: 100%;
                border: 6px solid #F8F8F8;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
            }
            .avatar-upload .avatar-preview > div {
                width: 100%;
                height: 100%;
                border-radius: 100%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
            }
            </style>

        <?php
            // Hiển thị thông báo nếu có
            echo $message;
            
            // Lấy danh sách ảnh từ cơ sở dữ liệu
            $stmt = $con->prepare("SELECT * FROM image_gallery");
            $stmt->execute();
            $gallery = $stmt->fetchAll();
        ?>

        <div class="card">
            <div class="card-header">
                <?php echo $pageTitle; ?>
            </div>
            <div class="card-body">

                <!-- ADD NEW IMAGE BUTTON -->
                <button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_image" data-placement="top">
                    <i class="fa fa-plus"></i> 
                    Thêm ảnh
                </button>

                <!-- Add New Image Modal -->
                <div class="modal fade" id="add_new_image" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Thêm ảnh mới</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" enctype="multipart/form-data">
                            <div class="modal-body">
                                <!-- Image Name -->
                                <div class="form-group">
                                    <label for="image_name">Tên ảnh</label>
                                        <input type="text" id="image_name_input" class="form-control" placeholder="Tên ảnh" name="image_name" required>
                                        <div class="invalid-feedback">
                                            <div>Tên ảnh không được để trống!</div>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="form-group">
                                    <label for="gallery_image">Ảnh</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                                <input type='file' name="gallery_image" id="add_gallery_imageUpload" accept=".png, .jpg, .jpeg" required />
                                            <label for="add_gallery_imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="add_gallery_imagePreview">
                                            </div>
                                        </div>
                                    </div>
                                        <div class="invalid-feedback">
                                        <div>Ảnh không được để trống!</div>
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy bỏ</button>
                                    <button type="submit" class="btn btn-info" name="add_image">Thêm ảnh</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- IMAGES TABLE -->
                <table class="table table-bordered gallery-table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Tên ảnh</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Quản lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($gallery as $image) {
                                echo "<tr>";
                                    echo "<td>";
                                        echo $image['image_id'];
                                    echo "</td>";

                                    echo "<td style = 'text-transform: capitalize'>";
                                        echo $image['image_name'];
                                    echo "</td>";

                                    $src = "./Uploads/images/".$image['image'];

                                    echo "<td style='width:25%!important'>";
                                        echo "<img src='".$src."' class='image_gallery img-fluid img-thumbnail' alt='".$image['image_name']."'>";
                                    echo "</td>";

                                    echo "<td>";
                                        ?>
                                            <ul class="list-inline m-0">
                                                <!-- DELETE BUTTON -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Xóa">
                                                    <a href="gallery.php?action=delete&id=<?php echo $image['image_id']; ?>" class="btn btn-danger btn-sm rounded-0" onclick="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này không?');">
                                                        <i class="fa fa-trash"></i>
                                                        Xóa
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
        /*** FOOTER BOTTON ***/
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: index.php');
        exit();
    }
?>

<script type="text/javascript">
    // JavaScript for image preview
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#add_gallery_imagePreview').css('background-image', 'url('+e.target.result +')');
                $('#add_gallery_imagePreview').hide();
                $('#add_gallery_imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#add_gallery_imageUpload").change(function() {
        readURL(this);
    });
</script>