<?php
// Kết nối đến database
require 'admin/connect.php';

try {
    // Kiểm tra xem có trường total_amount trong bảng không
    $checkColumn = $con->prepare("
        SELECT COLUMN_NAME 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'placed_orders' 
        AND COLUMN_NAME = 'total_amount'
    ");
    $checkColumn->execute();
    
    if ($checkColumn->rowCount() == 0) {
        // Thêm trường nếu chưa tồn tại
        echo "<h2>Đang thêm trường total_amount vào bảng placed_orders...</h2>";
        
        $addColumn = $con->prepare("ALTER TABLE placed_orders ADD COLUMN total_amount DECIMAL(10,2) DEFAULT 0");
        $addColumn->execute();
        
        echo "<p style='color:green'>Đã thêm trường total_amount vào bảng placed_orders thành công.</p>";
    } else {
        echo "<p>Trường total_amount đã tồn tại trong bảng placed_orders.</p>";
    }
    
    // Cập nhật giá trị total_amount cho tất cả các đơn hàng
    echo "<h2>Đang cập nhật giá trị total_amount cho tất cả các đơn hàng...</h2>";
    
    // Lấy danh sách tất cả các đơn hàng
    $getOrders = $con->prepare("SELECT order_id FROM placed_orders");
    $getOrders->execute();
    $orders = $getOrders->fetchAll(PDO::FETCH_ASSOC);
    
    $count = 0;
    foreach ($orders as $order) {
        // Tính tổng tiền cho từng đơn hàng
        $getTotal = $con->prepare("
            SELECT SUM(m.menu_price * io.quantity) as total
            FROM in_order io
            JOIN menus m ON io.menu_id = m.menu_id
            WHERE io.order_id = ?
        ");
        $getTotal->execute(array($order['order_id']));
        $totalResult = $getTotal->fetch(PDO::FETCH_ASSOC);
        $total = $totalResult['total'] ?? 0;
        
        // Cập nhật total_amount cho đơn hàng
        $updateOrder = $con->prepare("UPDATE placed_orders SET total_amount = ? WHERE order_id = ?");
        $updateOrder->execute(array($total, $order['order_id']));
        
        $count++;
    }
    
    echo "<p style='color:green'>Đã cập nhật giá trị total_amount cho {$count} đơn hàng.</p>";
    
    // Hiển thị dữ liệu đơn hàng để kiểm tra
    $checkOrders = $con->prepare("
        SELECT po.order_id, po.total_amount, 
               (SELECT SUM(m.menu_price * io.quantity) FROM in_order io JOIN menus m ON io.menu_id = m.menu_id WHERE io.order_id = po.order_id) as calculated_total
        FROM placed_orders po
        LIMIT 10
    ");
    $checkOrders->execute();
    $sampleOrders = $checkOrders->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Mẫu dữ liệu đơn hàng đã cập nhật (10 đơn đầu tiên):</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Order ID</th><th>Total Amount (DB)</th><th>Calculated Total</th></tr>";
    
    foreach ($sampleOrders as $order) {
        echo "<tr>";
        echo "<td>" . $order['order_id'] . "</td>";
        echo "<td>" . number_format($order['total_amount'], 0, ',', '.') . "</td>";
        echo "<td>" . number_format($order['calculated_total'], 0, ',', '.') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<p>Đã hoàn tất cập nhật!</p>";
    echo "<p><a href='admin/dashboard.php'>Quay lại trang quản lý</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Lỗi: " . $e->getMessage() . "</p>";
}
?> 