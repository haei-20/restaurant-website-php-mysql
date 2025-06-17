<?php
    // Import các file cần thiết
    include "connect.php";                              // File kết nối database
    include 'Includes/functions/functions.php';         // File chứa các hàm tiện ích
    include "Includes/templates/header.php";            // Template header
    include "Includes/templates/navbar.php";            // Template navigation bar

    // Xử lý gửi form liên hệ
    $contact_status_message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
        $contact_name = test_input($_POST['contact_name']);
        $contact_email = test_input($_POST['contact_email']);
        $contact_subject = test_input($_POST['contact_subject']);
        $contact_message = test_input($_POST['contact_message']);
        
        $errors = [];
        
        // Kiểm tra tên
        if (empty($contact_name)) {
            $errors['name'] = "Họ và tên không được để trống!";
        } elseif (strlen($contact_name) < 2) {
            $errors['name'] = "Họ và tên phải có ít nhất 2 ký tự!";
        }
        
        // Kiểm tra email
        if (!validateEmail($contact_email)) {
            $errors['email'] = "Email không hợp lệ!";
        }
        
        // Kiểm tra chủ đề
        if (empty($contact_subject)) {
            $errors['subject'] = "Chủ đề không được để trống!";
        }
        
        // Kiểm tra nội dung
        if (empty($contact_message)) {
            $errors['message'] = "Nội dung tin nhắn không được để trống!";
        }
        
        // Nếu không có lỗi, gửi email
        if (empty($errors)) {

            $email_content = "Tên: " . $contact_name . "\n";
            $email_content .= "Email: " . $contact_email . "\n\n";
            $email_content .= "Nội dung:\n" . $contact_message;
            
            // Thiết lập header
            $headers = "From: " . $contact_email . "\r\n";
            
            // Gửi email
            $mail_sent = mail(
                CONTACT_EMAIL, 
                EMAIL_SUBJECT_PREFIX . $contact_subject, 
                $email_content,
                $headers
            );
            
            if ($mail_sent) {
                $contact_status_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Tin nhắn đã được gửi thành công!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                
                // Reset form
                $contact_name = $contact_email = $contact_subject = $contact_message = "";
            } else {
                $contact_status_message = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Đã xảy ra sự cố khi gửi tin nhắn, vui lòng thử lại!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
            }
        }
    }

    // Lấy thông tin cài đặt website từ database
    $stmt_web_settings = $con->prepare("SELECT * FROM website_settings");
    $stmt_web_settings->execute();
    $web_settings = $stmt_web_settings->fetchAll();

    // Khởi tạo các biến thông tin nhà hàng
    $restaurant_name = "";
    $restaurant_email = "";
    $restaurant_address = "";
    $restaurant_phonenumber = "";

    // Lặp qua các cài đặt và gán giá trị cho từng biến tương ứng
    foreach ($web_settings as $option)
    {
        if($option['option_name'] == 'restaurant_name')
        {
            $restaurant_name = $option['option_value'];
        }
        elseif($option['option_name'] == 'restaurant_email')
        {
            $restaurant_email = $option['option_value'];
        }
        elseif($option['option_name'] == 'restaurant_phonenumber')
        {
            $restaurant_phonenumber = $option['option_value'];
        }
        elseif($option['option_name'] == 'restaurant_address')
        {
            $restaurant_address = $option['option_value'];
        }
    }
?>

<!-- Hiển thị thông báo đặt hàng thành công -->
<?php if (isset($_SESSION['order_success'])): ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                <h4 class="alert-heading"><?php echo $_SESSION['order_success']['message']; ?></h4>
                <p>Mã đơn hàng: #<?php echo $_SESSION['order_success']['order_id']; ?></p>
                <p class="mb-0">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>
<?php 
    // Xóa thông báo sau khi hiển thị
    unset($_SESSION['order_success']);
endif; 
?>

	<!-- PHẦN TRANG CHỦ -->
	<section class="home-section" id="home">
		<div class="container">
			<div class="row" style="flex-wrap: nowrap;">
				<div class="col-md-6 home-left-section">
					<div style="padding: 100px 0px; color: white;">
						<h1>
							VINCENT PIZZA.
						</h1>
						<h2>
							MANG NIỀM VUI ĐẾN MỌI NGƯỜI
						</h2>
						<hr>
						<p>
							Pizza Ý với Cà Chua Bi và Húng Quế Xanh
						</p>
						<div style="display: flex;">
							<a href="order_food.php" class="bttn_style_1" style="margin-right: 10px; display: flex;justify-content: center;align-items: center;">
								Đặt Hàng Ngay
								<i class="fas fa-angle-right"></i>
							</a>
							<a href="#menus" class="bttn_style_2" style="display: flex;justify-content: center;align-items: center;">
								XEM MENU
								<i class="fas fa-angle-right"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- PHẦN CHẤT LƯỢNG -->

	<section class="our_qualities" style="padding:100px 0px;">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="our_qualities_column">
						<img src="Design/images/quality_food_img.png" alt="Thực phẩm chất lượng">
						<div class="caption">
							<h3>
								Thực Phẩm Chất Lượng
							</h3>
							<p>
								Ngồi lại, tận hưởng những món ăn ngon nhất với nguyên liệu tươi sạch và chất lượng cao.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="our_qualities_column">
						<img src="Design/images/fast_delivery_img.png" alt="Giao hàng nhanh">
						<div class="caption">
							<h3>
								Giao Hàng Nhanh
							</h3>
							<p>
								Đảm bảo giao hàng nhanh chóng, đúng giờ để bạn luôn hài lòng.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="our_qualities_column">
						<img src="Design/images/original_taste_img.png" alt="Hương vị nguyên bản">
						<div class="caption">
							<h3>
								Hương Vị Nguyên Bản
							</h3>
							<p>
								Thưởng thức hương vị nguyên bản từ những công thức nấu ăn truyền thống.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- PHẦN MENU CỦA CHÚNG TÔI -->

	<section class="our_menus" id="menus">
		<div class="container">
			<h2 style="text-align: center;margin-bottom: 30px">MENU</h2>
			<div class="menu_section" id="menus">
				<div class="container">
					<ul class="nav nav-tabs menu_tabs">
						<?php
							// Lấy danh mục được chọn từ phương thức GET hoặc từ session
							$selected_category = isset($_GET['category']) ? $_GET['category'] : 
										(isset($_SESSION['selected_category']) ? $_SESSION['selected_category'] : null);
							
							// Truy vấn danh sách danh mục
							$stmt = $con->prepare("Select * from menu_categories");
							$stmt->execute();
							$rows = $stmt->fetchAll();
							
							$i = 0;
							foreach($rows as $row) {
								// Nếu chưa có danh mục được chọn, mặc định chọn danh mục đầu tiên
								if($i == 0 && $selected_category === null) {
									$selected_category = str_replace(' ', '', $row['category_name']);
									$_SESSION['selected_category'] = $selected_category;
								}
								
								// Kiểm tra xem danh mục này có phải là danh mục hiện tại không
								$is_active = ($selected_category == str_replace(' ', '', $row['category_name'])) ? 'active' : '';
								$category_id = str_replace(' ', '', $row['category_name']);
								
								// Hiển thị tab danh mục với data attributes thay vì form
								echo '<li class="nav-item">';
								echo '<button type="button" class="tab_category_links nav-link ' . $is_active . '" data-category="' . $category_id . '">' . $row['category_name'] . '</button>';
								echo '</li>';
								
								$i++;
							}
						?>
					</ul>

					<div class="tab-content">
						<?php
							$i = 0;
							foreach($rows as $row) {
								$category_id = str_replace(' ', '', $row['category_name']);
								// Hiển thị nội dung của tab
								$style = ($selected_category == $category_id) ? 'display: block;' : 'display: none;';
								
								if($i == 0) {
									echo '<div class="menus_categories tab_category_content" id="' . $category_id . '" style="' . $style . '">';
									
									$stmt_menus = $con->prepare("Select * from menus where category_id = ?");
									$stmt_menus->execute(array($row['category_id']));
									$rows_menus = $stmt_menus->fetchAll();
									
									if($stmt_menus->rowCount() == 0) {
										echo "<div class='no_menus_div'>Không có món ăn nào trong danh mục này!</div>";
									}
									
									echo "<div class='row'>";
									foreach($rows_menus as $menu) {
										?>
										<div class="col-md-4 col-lg-3 menu-column">
											<div class="thumbnail" style="cursor:pointer">
												<?php $source = "admin/Uploads/images/".$menu['menu_image']; ?>
												<div class="menu-image">
													<div class="image-preview">
														<div style="background-image: url('<?php echo $source; ?>');"></div>
													</div>
												</div>
												<div class="caption">
													<h5>
														<?php echo $menu['menu_name']; ?>
													</h5>
													<p>
														<?php echo $menu['menu_description']; ?>
													</p>
													<span class='menu_price'>
														<?php echo number_format(intval($menu['menu_price']), 0, ',', '.') . "000đ"; ?>
													</span>
												</div>
											</div>
										</div>
										<?php
									}
									echo "</div>";
									echo '</div>';
								} else {
									echo '<div class="menus_categories tab_category_content" id="' . $category_id . '" style="' . $style . '">';
									$stmt_menus = $con->prepare("Select * from menus where category_id = ?");
									$stmt_menus->execute(array($row['category_id']));
									$rows_menus = $stmt_menus->fetchAll();

									if ($stmt_menus->rowCount() == 0) {
										echo "<div class='no_menus_div'>Không có món ăn nào trong danh mục này!</div>";
									}

									echo "<div class='row'>";
									foreach ($rows_menus as $menu) {
										?>
										<div class="col-md-4 col-lg-3 menu-column">
											<div class="thumbnail" style="cursor:pointer">
												<?php $source = "admin/Uploads/images/" . $menu['menu_image']; ?>
												<div class="menu-image">
													<div class="image-preview">
														<div style="background-image: url('<?php echo $source; ?>');"></div>
													</div>
												</div>
												<div class="caption">
													<h5>
														<?php echo $menu['menu_name']; ?>
													</h5>
													<p>
														<?php echo $menu['menu_description']; ?>
													</p>
													<span class='menu_price'>
														<?php echo number_format(intval($menu['menu_price']), 0, ',', '.') . "000đ"; ?>
													</span>
												</div>
											</div>
										</div>
										<?php
									}
									echo "</div>";
									echo '</div>';
								}
								$i++;
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- PHẦN HÌNH ẢNH -->

	<section class="image-gallery" id="gallery">
		<div class="container">
			<h2 style="text-align: center;margin-bottom: 30px">HÌNH ẢNH</h2>
			<?php
				$stmt_image_gallery = $con->prepare("Select * from image_gallery");
				$stmt_image_gallery->execute();
				$rows_image_gallery = $stmt_image_gallery->fetchAll();

				echo "<div class = 'row'>";

					foreach($rows_image_gallery as $row_image_gallery)
					{
						echo "<div class = 'col-md-4 col-lg-3' style = 'padding: 15px;'>";
							$source = "admin/Uploads/images/".$row_image_gallery['image'];
							?>

							<div style = "background-image: url('<?php echo $source; ?>') !important;background-repeat: no-repeat;background-position: 50% 50%;background-size: cover;background-clip: border-box;box-sizing: border-box;overflow: hidden;height: 230px;">
							</div>

							<?php
						echo "</div>";
					}

				echo "</div>";
			?>
		</div>
	</section>

	<!-- PHẦN LIÊN HỆ -->

	<section class="contact-section" id="contact">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 sm-padding">
					<div class="contact-info">
						<h2>
							Liên hệ với chúng tôi & 
							<br>gửi tin nhắn ngay hôm nay!
						</h2>
						<p>
							Nhà hàng của chúng tôi luôn sẵn sàng phục vụ bạn với những món ăn ngon nhất.
						</p>
						<h3>
							<?php echo $restaurant_address; ?>
						</h3>
						<h4>
							<span>Email:</span> 
							<?php echo $restaurant_email; ?>
							<br> 
							<span>Điện thoại:</span> 
							<?php echo $restaurant_phonenumber; ?>
						</h4>
					</div>
				</div>
				<div class="col-lg-6 sm-padding">
					<div class="contact-form">
						<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>#contact" class="contactForm">
							<div class="form-group colum-row row">
								<div class="col-sm-6">
									<input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Họ và Tên" value="<?php echo isset($contact_name) ? $contact_name : ''; ?>">
                                    <?php if(isset($errors['name'])): ?>
                                        <div class="invalid-feedback" style="display: block;"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
								</div>
								<div class="col-sm-6">
									<input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="Email" value="<?php echo isset($contact_email) ? $contact_email : ''; ?>">
                                    <?php if(isset($errors['email'])): ?>
                                        <div class="invalid-feedback" style="display: block;"><?php echo $errors['email']; ?></div>
                                    <?php endif; ?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<input type="text" id="contact_subject" name="contact_subject" class="form-control" placeholder="Chủ đề" value="<?php echo isset($contact_subject) ? $contact_subject : ''; ?>">
                                    <?php if(isset($errors['subject'])): ?>
                                        <div class="invalid-feedback" style="display: block;"><?php echo $errors['subject']; ?></div>
                                    <?php endif; ?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<textarea id="contact_message" name="contact_message" cols="30" rows="5" class="form-control message" placeholder="Tin nhắn"><?php echo isset($contact_message) ? $contact_message : ''; ?></textarea>
                                    <?php if(isset($errors['message'])): ?>
                                        <div class="invalid-feedback" style="display: block;"><?php echo $errors['message']; ?></div>
                                    <?php endif; ?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-12">
									<button type="submit" name="contact_submit" class="bttn_style_2">Gửi Tin Nhắn</button>
								</div>
							</div>
                            <?php echo $contact_status_message; ?>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- PHẦN CHẤT LƯỢNG  -->
		
	<section class="our_qualities_v2">
		<div class="container">
			<div class="row">
				<div class="col-md-4" style="padding: 10px;">
					<div class="quality quality_1">
						<div class="text_inside_quality">
							<h5>Thực Phẩm Chất Lượng</h5>
							<p>
								Chúng tôi sử dụng nguyên liệu tươi ngon nhất để mang đến cho bạn những món ăn tuyệt vời.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4" style="padding: 10px;">
					<div class="quality quality_2">
						<div class="text_inside_quality">
							<h5>Giao Hàng Nhanh</h5>
							<p>
								Đảm bảo giao hàng nhanh chóng, đúng giờ để bạn luôn hài lòng.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4" style="padding: 10px;">
					<div class="quality quality_3">
						<div class="text_inside_quality">
							<h5>Công Thức Nguyên Bản</h5>
							<p>
								Thưởng thức hương vị nguyên bản từ những công thức nấu ăn truyền thống.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- PHẦN WIDGET / CHÂN TRANG -->

	<section class="widget_section" style="background-color: #222227;padding: 100px 0;">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-6">
					<div class="footer_widget">
						<img src="Design/images/restaurant-logo.png" alt="Logo Nhà Hàng" style="width: 150px;margin-bottom: 20px;">
						<p>
							Nhà hàng của chúng tôi là một trong những nhà hàng tốt nhất, cung cấp thực đơn và món ăn ngon. Bạn có thể đặt bàn hoặc gọi món.
						</p>
						<ul class="widget_social">
							<li><?php echo social_media_icon('facebook', '#', 'Facebook'); ?></li>
							<li><?php echo social_media_icon('twitter', '#', 'Twitter'); ?></li>
							<li><?php echo social_media_icon('instagram', '#', 'Instagram'); ?></li>
							<li><?php echo social_media_icon('linkedin', '#', 'LinkedIn'); ?></li>
							<li><?php echo social_media_icon('google-plus', '#', 'Google+'); ?></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer_widget">
						<h3>Trụ Sở Chính</h3>
						<p>
							<?php echo $restaurant_address; ?>
						</p>
						<p>
							<?php echo $restaurant_email; ?>
							<br>
							<?php echo $restaurant_phonenumber; ?>   
						</p>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer_widget">
						<h3>Giờ Mở Cửa</h3>
						<ul class="opening_time">
							<li>Thứ Hai - Thứ Sáu: 11:30 sáng - 8:00 tối</li>
							<li>Thứ Bảy - Chủ Nhật: 11:30 sáng - 10:00 tối</li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="footer_widget">
						<h3>Đăng Ký Nhận Tin</h3>
						<div class="subscribe_form">
							<form action="#" class="subscribe_form" novalidate="true">
								<input type="email" name="EMAIL" id="subs-email" class="form_input" placeholder="Địa chỉ Email...">
								<button type="submit" class="submit">ĐĂNG KÝ</button>
								<div class="clearfix"></div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	
    <!-- PHẦN CUỐI TRANG -->

    <!-- SCRIPT CHO MENU TABS -->
	<script>
	// Đoạn mã JavaScript nhỏ chỉ cho phép chuyển đổi menu mà không cần tải lại trang
	document.addEventListener('DOMContentLoaded', function() {
		// Lấy tất cả các nút tab
		var tabButtons = document.querySelectorAll('.tab_category_links');
		
		// Thêm sự kiện click cho mỗi nút
		tabButtons.forEach(function(button) {
			button.addEventListener('click', function() {
				// Lấy ID danh mục từ thuộc tính data-category
				var categoryId = this.getAttribute('data-category');
				
				// Xóa class active từ tất cả các tab
				tabButtons.forEach(function(btn) {
					btn.classList.remove('active');
				});
				
				// Thêm class active cho tab được nhấp
				this.classList.add('active');
				
				// Ẩn tất cả các nội dung tab
				var allTabContents = document.querySelectorAll('.tab_category_content');
				allTabContents.forEach(function(content) {
					content.style.display = 'none';
				});
				
				// Hiển thị nội dung tab tương ứng
				var selectedTabContent = document.getElementById(categoryId);
				if (selectedTabContent) {
					selectedTabContent.style.display = 'block';
				}
				
				// Lưu trạng thái tab đã chọn vào sessionStorage
				sessionStorage.setItem('selectedCategory', categoryId);
				
				// Đặt URL hash 
				window.location.hash = 'menus';
			});
		});
		
		// Kiểm tra xem có tab nào đã được lưu trong sessionStorage không
		var savedCategory = sessionStorage.getItem('selectedCategory');
		if (savedCategory) {
			// Tìm tab tương ứng và kích hoạt nó
			var savedTab = document.querySelector('.tab_category_links[data-category="' + savedCategory + '"]');
			if (savedTab) {
				savedTab.click();
			}
		}
	});
	</script>

    <?php include "Includes/templates/footer.php"; ?>
</body>