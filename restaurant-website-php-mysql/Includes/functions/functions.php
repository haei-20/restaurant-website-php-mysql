<?php
function getTitle()
{
	global $pageTitle;
	if(isset($pageTitle))
		echo $pageTitle." | ".SITE_NAME." - Nhà Hàng Của Bạn";
	else
		echo SITE_NAME." | Nhà Hàng Của Bạn";
}
/**
 * Đếm Số Lượng Mục - Trả về số lượng mục trong một bảng cụ thể
 * @param string $item - Cột cần đếm
 * @param string $table - Bảng cần đếm từ
 * @return int - Số lượng mục
 */
function countItems($item, $table)
{
	global $con;
	$stat_ = $con->prepare("SELECT COUNT($item) FROM $table");
	$stat_->execute();
	
	return $stat_->fetchColumn();
}

/**
 * Kiểm Tra Mục - Kiểm tra xem một mục có tồn tại trong cơ sở dữ liệu hay không
 * @param string $select - Cột để chọn
 * @param string $from - Bảng để chọn từ
 * @param mixed $value - Giá trị cần kiểm tra
 * @return int - Số lượng hàng phù hợp
 */
function checkItem($select, $from, $value)
{
	global $con;
	$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");
	$statment->execute(array($value));
	$count = $statment->rowCount();
	
	return $count;
}

/**
 * Kiểm Tra Đầu Vào - Xử lý và làm sạch dữ liệu đầu vào của người dùng
 * Loại bỏ các ký tự đáng ngờ và khoảng trắng thừa
 * @param string $data - Dữ liệu đầu vào cần xử lý
 * @return string - Dữ liệu đã xử lý
 */
function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/**
 * Xác Thực Email - Kiểm tra nếu email hợp lệ
 * @param string $email - Email cần xác thực
 * @return bool - True nếu hợp lệ, false nếu không
 */
function validateEmail($email)
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
