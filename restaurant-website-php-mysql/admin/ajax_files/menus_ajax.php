<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>

<?php

	if(isset($_POST['do_']) && $_POST['do_'] == "Delete")
	{
		$menu_id = $_POST['menu_id'];

        // Kiểm tra xem menu có tồn tại không
        $check = $con->prepare("SELECT COUNT(*) FROM menus WHERE menu_id = ?");
        $check->execute(array($menu_id));
        if($check->fetchColumn() == 0) {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy món ăn này!']);
            exit();
        }

        // Xóa các món ăn trong đơn hàng
        $stmt = $con->prepare("DELETE FROM in_order WHERE menu_id = ?");
        $stmt->execute(array($menu_id));

        // Xóa menu
        $stmt = $con->prepare("DELETE FROM menus WHERE menu_id = ?");
        if($stmt->execute(array($menu_id))) {
            echo json_encode(['status' => 'success', 'message' => 'Món ăn đã được xóa thành công!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi xóa món ăn!']);
        }
	}

?>