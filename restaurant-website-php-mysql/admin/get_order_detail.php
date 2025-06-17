<?php
// Kết nối database
include 'connect.php';

// Kiểm tra có order_id được gửi lên không
if (!isset($_GET['order_id'])) {
    echo json_encode(['error' => 'Thiếu mã đơn hàng']);
    exit;
}

$order_id = intval($_GET['order_id']);

try {
    // Lấy thông tin đơn hàng và khách hàng
    $stmt = $con->prepare("
        SELECT po.*, c.*
        FROM placed_orders po
        JOIN clients c ON po.client_id = c.client_id
        WHERE po.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['error' => 'Không tìm thấy đơn hàng']);
        exit;
    }

    // Lấy chi tiết các món ăn trong đơn hàng
    $stmt = $con->prepare("
        SELECT m.menu_name, m.menu_price, io.quantity
        FROM menus m
        JOIN in_order io ON m.menu_id = io.menu_id
        WHERE io.order_id = ?
    ");
    $stmt->execute([$order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Chuẩn bị dữ liệu trả về
    $response = [
        'order_id' => $order['order_id'],
        'client_name' => $order['client_name'],
        'client_email' => $order['client_email'],
        'client_phone' => $order['client_phone'],
        'delivery_address' => $order['delivery_address'],
        'order_time' => $order['order_time'],
        'delivery_time' => $order['delivery_time'],
        'canceled' => $order['canceled'],
        'cancel_time' => $order['cancel_time'],
        'canceled_by' => $order['canceled_by'],
        'cancellation_reason' => $order['cancellation_reason'],
        'delivered' => $order['delivered'],
        'items' => $items
    ];

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
} 