<?php 
	session_start();
	$pageTitle = 'Đăng nhập';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		header('Location: dashboard.php');
	}
?>

<?php include 'connect.php'; ?>
<?php include 'Includes/functions/functions.php'; ?>

<?php
$errors = [];

// Kiểm tra nếu user click button
if(isset($_POST['admin_login']))
{
	if(empty($_POST['username']) && empty($_POST['password'])) {
		$errors['username'] = "Nhập tên người dùng!";
		$errors['password'] = "Nhập mật khẩu!";
	} elseif(empty($_POST['username'])) {
		$errors['username'] = "Nhập tên người dùng!";
	} elseif(empty($_POST['password'])) {
		$errors['password'] = "Nhập mật khẩu!";
	} else {
		$username = test_input($_POST['username']);
		$password = test_input($_POST['password']);
		$hashedPass = sha1($password);

		$stmt = $con->prepare("Select user_id, username, password from users where username = ? and password = ?");
		$stmt->execute(array($username,$hashedPass));
		$row = $stmt->fetch(); //lấy dòng kq đầu tiên
		$count = $stmt->rowCount();

		if($count > 0)
		{
			$_SESSION['username_restaurant_qRewacvAqzA'] = $username;
			$_SESSION['password_restaurant_qRewacvAqzA'] = $password;
			$_SESSION['userid_restaurant_qRewacvAqzA'] = $row['user_id'];
			header('Location: dashboard.php'); 
			die();
		}
		else
		{
			$login_error = "Tài khoản hoặc mật khẩu không đúng!";
		}
	}
}
?>

<?php include 'Includes/templates/header.php'; ?>

	<!-- LOGIN FORM -->

	<div class="login">
		<form class="login-container validate-form" name="login-form" action="index.php" method="POST">
			<span class="login100-form-title p-b-32">
				Đăng nhập 
			</span>
			<?php
				if(isset($login_error))
				{
					?>
						<div class="alert alert-danger">
							<button data-dismiss="alert" class="close close-sm" type="button">
								<span aria-hidden="true">×</span>
							</button>
							<div class="messages">
								<div><?php echo $login_error; ?></div>
							</div>
						</div>
					<?php 
				}
			?>

			<!-- USERNAME -->

			<div class="form-input">
				<span class="txt1">Tài khoản</span>
				<input type="text" name="username" class="form-control username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" id="user" autocomplete="off">
				<?php if(isset($errors['username'])): ?>
					<div class="invalid-feedback" style="display: block;"><?php echo $errors['username']; ?></div>
				<?php endif; ?>
			</div>

			<!-- PASSWORD -->
			
			<div class="form-input">
				<span class="txt1">Mật khẩu</span>
				<input type="password" name="password" class="form-control" id="password" autocomplete="new-password">
				<?php if(isset($errors['password'])): ?>
					<div class="invalid-feedback" style="display: block;"><?php echo $errors['password']; ?></div>
				<?php endif; ?>
			</div>

			<!-- SIGNIN BUTTON -->
			
			<p>
				<button type="submit" name="admin_login" >Đăng nhập</button>
			</p>

			<!-- FORGOT PASSWORD PART -->

			<span class="forgotPW">Quên mật khẩu ? <a href="forgot_password.php">Đặt lại mật khẩu.</a></span>

		</form>
	</div>

<?php include 'Includes/templates/footer.php'; ?>
