<?php
// Bắt đầu session nếu chưa bắt đầu
if (!isset($_SESSION)) {
    session_start();
}

// Nhập các file cần thiết
require_once 'connect.php';
require_once 'Includes/functions/functions.php';

// Kiểm tra xem request có phải là POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Khởi tạo mảng phản hồi
    $response = array(
        'success' => false,
        'message' => '',
        'menu_id' => 0,
        'quantity' => 0,
        'item_price' => 0,
        'item_price_formatted' => '',
        'total' => 0,
        'total_formatted' => '',
        'is_empty' => true
    );
    
    // Lấy hành động và ID món ăn từ request
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $menuId = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;
    
    // Kiểm tra nếu món ăn tồn tại trong cơ sở dữ liệu
    $stmt = $con->prepare("SELECT * FROM menus WHERE menu_id = ?");
    $stmt->execute(array($menuId));
    $menu = $stmt->fetch();
    
    if (!$menu) {
        // Món ăn không tồn tại
        $response['message'] = 'Món ăn không tồn tại';
        echo json_encode($response);
        exit;
    }
    
    // Khởi tạo mảng selected_menus và menu_quantities nếu chưa tồn tại
    if (!isset($_SESSION['selected_menus'])) {
        $_SESSION['selected_menus'] = array();
    }
    
    if (!isset($_SESSION['menu_quantities'])) {
        $_SESSION['menu_quantities'] = array();
    }
    
    // Xử lý các hành động khác nhau
    switch ($action) {
        case 'select':
            // Thêm món ăn vào danh sách đã chọn
            if (!in_array($menuId, $_SESSION['selected_menus'])) {
                $_SESSION['selected_menus'][] = $menuId;
            }
            
            // Đảm bảo số lượng mặc định cho món ăn mới chọn
            if (!isset($_SESSION['menu_quantities'][$menuId])) {
                $_SESSION['menu_quantities'][$menuId] = 1;
            }
            
            $response['success'] = true;
            $response['message'] = 'Đã thêm món ăn vào danh sách';
            $response['menu_name'] = $menu['menu_name'];
            $response['quantity'] = $_SESSION['menu_quantities'][$menuId];
            $response['unit_price'] = $menu['menu_price'];
            break;
            
        case 'unselect':
            // Xóa món ăn khỏi danh sách đã chọn
            $key = array_search($menuId, $_SESSION['selected_menus']);
            if ($key !== false) {
                unset($_SESSION['selected_menus'][$key]);
                // Sắp xếp lại mảng (để các phần tử liên tục)
                $_SESSION['selected_menus'] = array_values($_SESSION['selected_menus']);
                
                // Lưu trữ lại số lượng dưới dạng tạm thời, để có thể khôi phục nếu người dùng chọn lại
                $tmpQuantity = isset($_SESSION['menu_quantities'][$menuId]) ? $_SESSION['menu_quantities'][$menuId] : 1;
                unset($_SESSION['menu_quantities'][$menuId]);
                
                $response['success'] = true;
                $response['message'] = 'Đã xóa món ăn khỏi danh sách';
            } else {
                $response['message'] = 'Món ăn không tồn tại trong danh sách';
            }
            break;
            
        case 'increase':
            // Tăng số lượng
            if (!isset($_SESSION['menu_quantities'][$menuId])) {
                $_SESSION['menu_quantities'][$menuId] = 1;
            } elseif ($_SESSION['menu_quantities'][$menuId] < 99) {
                $_SESSION['menu_quantities'][$menuId]++;
            }
            
            // Đảm bảo món ăn được thêm vào danh sách đã chọn
            if (!in_array($menuId, $_SESSION['selected_menus'])) {
                $_SESSION['selected_menus'][] = $menuId;
            }
            
            $response['success'] = true;
            $response['message'] = 'Đã tăng số lượng';
            break;
            
        case 'decrease':
            // Giảm số lượng
            if (!isset($_SESSION['menu_quantities'][$menuId])) {
                $_SESSION['menu_quantities'][$menuId] = 1;
            } elseif ($_SESSION['menu_quantities'][$menuId] > 1) {
                $_SESSION['menu_quantities'][$menuId]--;
            }
            
            $response['success'] = true;
            $response['message'] = 'Đã giảm số lượng';
            break;
            
        case 'update_direct':
            // Cập nhật số lượng trực tiếp
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $quantity = max(1, min(99, $quantity)); // Đảm bảo giá trị từ 1-99
            
            $_SESSION['menu_quantities'][$menuId] = $quantity;
            
            // Đảm bảo món ăn được thêm vào danh sách đã chọn
            if (!in_array($menuId, $_SESSION['selected_menus'])) {
                $_SESSION['selected_menus'][] = $menuId;
            }
            
            $response['success'] = true;
            $response['message'] = 'Đã cập nhật số lượng';
            break;
            
        case 'remove':
            // Xóa món ăn khỏi danh sách

            //lấy id từ danh sáchsách
            $key = array_search($menuId, $_SESSION['selected_menus']);
            if ($key !== false) {
                unset($_SESSION['selected_menus'][$key]);
                // Sắp xếp lại mảng (để các phần tử liên tục)
                $_SESSION['selected_menus'] = array_values($_SESSION['selected_menus']);
                
                // Xóa số lượng tương ứng
                if (isset($_SESSION['menu_quantities'][$menuId])) {
                    unset($_SESSION['menu_quantities'][$menuId]);
                }
                
                $response['success'] = true;
                $response['message'] = 'Đã xóa món ăn khỏi danh sách';
                
                // Thêm menu_id vào phản hồi để JavaScript có thể bỏ chọn checkbox tương ứng
                $response['removed_menu_id'] = $menuId;
            } else {
                $response['message'] = 'Món ăn không tồn tại trong danh sách';
            }
            break;
            
        default:
            $response['message'] = 'Hành động không hợp lệ';
            break;
    }
    
    // Cập nhật thông tin phản hồi
    $response['menu_id'] = $menuId;
    $response['quantity'] = isset($_SESSION['menu_quantities'][$menuId]) ? $_SESSION['menu_quantities'][$menuId] : 0;
    
    // Tính tổng tiền
    $totalPrice = 0;
    $response['is_empty'] = empty($_SESSION['selected_menus']);
    
    if (!empty($_SESSION['selected_menus'])) {
        foreach ($_SESSION['selected_menus'] as $id) {
            $stmt = $con->prepare("SELECT menu_price FROM menus WHERE menu_id = ?");
            $stmt->execute(array($id));
            $menuPrice = $stmt->fetchColumn();
            $quantity = isset($_SESSION['menu_quantities'][$id]) ? $_SESSION['menu_quantities'][$id] : 1;
            $totalPrice += $menuPrice * $quantity;
            
            // Nếu đây là món ăn đang được cập nhật, lưu giá tiền của nó
            if ($id == $menuId) {
                $response['item_price'] = $menuPrice * $response['quantity'];
                $response['item_price_formatted'] = number_format($response['item_price'], 0, ',', '.') . '000đ';
            }
        }
    }
    
    $response['total'] = $totalPrice;
    $response['total_formatted'] = number_format($totalPrice, 0, ',', '.') . '000đ';
    
    // Trả về kết quả dưới dạng JSON
    echo json_encode($response);
} else {
    // Nếu không phải yêu cầu POST, chuyển hướng về trang chính
    header('Location: order_food.php');
    exit;
} 