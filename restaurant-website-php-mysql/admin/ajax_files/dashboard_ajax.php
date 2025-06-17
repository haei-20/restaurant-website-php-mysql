<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>

<?php
	if(isset($_POST['do_']) && $_POST['do_'] == "Deliver_Order")
	{
		$order_id = $_POST['order_id'];

        $stmt = $con->prepare("UPDATE placed_orders SET delivered = 1, delivery_time = NOW() WHERE order_id = ?");
        $stmt->execute(array($order_id));
        
        echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được đánh dấu là hoàn thành!']);
	}
	elseif(isset($_POST['do_']) && $_POST['do_'] == "Cancel_Order")
	{
		$order_id = $_POST['order_id'];
		$cancellation_reason = $_POST['cancellation_reason'];

        $stmt = $con->prepare("UPDATE placed_orders SET canceled = 1, cancel_time = NOW(), cancellation_reason = ?, canceled_by = 'Admin' WHERE order_id = ?");
        $stmt->execute(array($cancellation_reason, $order_id));
        
        echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được hủy thành công!']);
	}
	elseif(isset($_POST['do_']) && $_POST['do_'] == "Liberate_Table")
	{
		$reservation_id = $_POST['reservation_id'];

        $stmt = $con->prepare("UPDATE reservations SET liberated = 1, delivery_time = NOW() WHERE reservation_id = ?");
        $stmt->execute(array($reservation_id));
	}
	elseif(isset($_POST['do_']) && $_POST['do_'] == "Cancel_Reservation")
	{
		$reservation_id = $_POST['reservation_id'];
		$cancellation_reason = $_POST['cancellation_reason'];

        $stmt = $con->prepare("UPDATE reservations SET canceled = 1, cancel_time = NOW(), cancellation_reason = ?, canceled_by = 'Admin' WHERE reservation_id = ?");
        $stmt->execute(array($cancellation_reason, $reservation_id));
	}

?>