<?php
    $pageTitle = 'Order Food';
    include "connect.php";                             
    include 'Includes/functions/functions.php';        
    
    // Khởi tạo session nếu chưa có
    if (!isset($_SESSION)) {
        session_start();
    }
 // Reset toàn bộ session khi vừa vô 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' && (!isset($_SESSION['order_started']) || isset($_GET['reset']))) {
       
        $_SESSION['selected_menus'] = array();
        $_SESSION['menu_quantities'] = array();
        $_SESSION['currentTab'] = 0;
        $_SESSION['order_started'] = true;
        
        // Reset thông tin khách hàng
        unset($_SESSION['client_full_name']);
        unset($_SESSION['client_email']);
        unset($_SESSION['client_phone_number']);
        unset($_SESSION['client_delivery_address']);
        unset($_SESSION['form_errors']);
    }
    
    // XỬ LÝ REQUEST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['selected_menus'])) {
            $_SESSION['selected_menus'] = array_map('intval', $_POST['selected_menus']);
            
            // Cập nhật số lượng lần đầu =1
            foreach ($_SESSION['selected_menus'] as $menuId) {
                if (!isset($_SESSION['menu_quantities'][$menuId])) {
                    $_SESSION['menu_quantities'][$menuId] = 1;
                }
            }
            
            // Xóa số lượng của món không còn được chọn
            foreach ($_SESSION['menu_quantities'] as $menuId => $quantity) {
                if (!in_array($menuId, $_SESSION['selected_menus'])) {
                    unset($_SESSION['menu_quantities'][$menuId]);
                }
            }
        }
        
        // Xử lý số lượng món ăn
        if (isset($_POST['menu_quantities']) && is_array($_POST['menu_quantities'])) {
            foreach ($_POST['menu_quantities'] as $id => $quantity) {
                $id = intval($id);
                if (in_array($id, $_SESSION['selected_menus'])) {
                    $_SESSION['menu_quantities'][$id] = max(1, min(99, intval($quantity)));
                }
            }
        }
        
        // Xử lý các nút điều khiển số lượng
        if (isset($_POST['increase_quantity']) || isset($_POST['decrease_quantity']) || isset($_POST['remove_item'])) {
            $menuId = null;
            $action = '';
            
            if (isset($_POST['increase_quantity'])) {
                $menuId = key($_POST['increase_quantity']);
                $action = 'increase';
            } elseif (isset($_POST['decrease_quantity'])) {
                $menuId = key($_POST['decrease_quantity']);
                $action = 'decrease';
            } elseif (isset($_POST['remove_item'])) {
                $menuId = key($_POST['remove_item']);
                $action = 'remove';
            }
            
            if ($menuId !== null) {
                $menuId = intval($menuId);
                
                switch ($action) {
                    case 'increase':
                        if (!isset($_SESSION['menu_quantities'][$menuId])) {
                            $_SESSION['menu_quantities'][$menuId] = 1;
                        }
                        if ($_SESSION['menu_quantities'][$menuId] < 99) {
                            $_SESSION['menu_quantities'][$menuId]++;
                        }
                        if (!in_array($menuId, $_SESSION['selected_menus'])) {
                            $_SESSION['selected_menus'][] = $menuId;
                        }
                        break;
                        
                    case 'decrease':
                        if ($_SESSION['menu_quantities'][$menuId] > 1) {
                            $_SESSION['menu_quantities'][$menuId]--;
                        }
                        break;
                        
                    case 'remove':
                        $key = array_search($menuId, $_SESSION['selected_menus']);
                        if ($key !== false) {
                            unset($_SESSION['selected_menus'][$key]);
                            $_SESSION['selected_menus'] = array_values($_SESSION['selected_menus']);
                            unset($_SESSION['menu_quantities'][$menuId]);
                        }
                        break;
                }
                
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
        
        // Xử lý chuyển tab
        if (isset($_POST['next_tab']) || isset($_POST['prev_tab'])) {
            if (isset($_POST['next_tab'])) {
                // Validate trước khi chuyển tab
                $isValid = true;
                $errors = [];
                
                if ($_SESSION['currentTab'] == 0 && empty($_SESSION['selected_menus'])) {
                    $isValid = false;
                    $errors['menu'] = "Vui lòng chọn ít nhất một món ăn";
                }
                //Nếu hợp lệ, tăng currentTab (giới hạn tối đa là 1)
                if ($isValid) {
                    $_SESSION['currentTab'] = min(1, (int)$_SESSION['currentTab'] + 1);
                    unset($_SESSION['form_errors']);
                } else {
                    $_SESSION['form_errors'] = $errors;
                }
            } else {
                $_SESSION['currentTab'] = max(0, (int)$_SESSION['currentTab'] - 1);
            }
        }
        
        // Xử lý đặt hàng
        if (isset($_POST['submit_order_food_form'])) {
            $isValid = true;
            $errors = [];
            
            // Validate client details
            if (empty(trim($_POST['client_full_name']))) {
                $isValid = false;
                $errors['client_full_name'] = "Vui lòng nhập họ tên";
            }
            
            if (empty(trim($_POST['client_email']))) {
                $isValid = false;
                $errors['client_email'] = "Vui lòng nhập email";
            } elseif (!filter_var($_POST['client_email'], FILTER_VALIDATE_EMAIL)) {
                $isValid = false;
                $errors['client_email'] = "Email không hợp lệ";
            }
            
            if (empty(trim($_POST['client_phone_number']))) {
                $isValid = false;
                $errors['client_phone_number'] = "Vui lòng nhập số điện thoại";
            }
            
            if (empty(trim($_POST['client_delivery_address']))) {
                $isValid = false;
                $errors['client_delivery_address'] = "Vui lòng nhập địa chỉ giao hàng";
            }
            
            // Check for selected menus
            $selected_menus = isset($_SESSION['selected_menus']) ? $_SESSION['selected_menus'] : [];
            $menu_quantities = isset($_SESSION['menu_quantities']) ? $_SESSION['menu_quantities'] : [];
            
            if (empty($selected_menus) || !is_array($selected_menus) || count($selected_menus) == 0) {
                $isValid = false;
                $errors['menu'] = "Vui lòng chọn ít nhất một món ăn";
                $_SESSION['currentTab'] = 0;
            }
            
            if (!$isValid) {
                // Lưu lỗi vào session
                $_SESSION['form_errors'] = $errors;
                
                // Lưu dữ liệu đã nhập để điền lại form
                $_SESSION['client_full_name'] = test_input($_POST['client_full_name']);
                $_SESSION['client_email'] = test_input($_POST['client_email']);
                $_SESSION['client_phone_number'] = test_input($_POST['client_phone_number']);
                $_SESSION['client_delivery_address'] = test_input($_POST['client_delivery_address']);
                
                // Hiển thị thông báo lỗi
                echo "<div class='alert alert-danger'>";
                echo "<strong>Lỗi!</strong> Vui lòng kiểm tra lại các thông tin sau:<br>";
                foreach ($errors as $error) {
                    echo "- " . $error . "<br>";
                }
                echo "</div>";
            } 
            else {
                try {
                    $con->beginTransaction();
                    
                    // Thêm thông tin khách hàng
                    $stmtClient = $con->prepare("INSERT INTO clients(client_name,client_phone,client_email) VALUES(?,?,?)");
                    $stmtClient->execute(array(
                        test_input($_POST['client_full_name']),
                        test_input($_POST['client_phone_number']),
                        test_input($_POST['client_email'])
                    ));
                    
                    $client_id = $con->lastInsertId();
                    
                    // Tính tổng tiền
                    $total_amount = 0;
                    foreach($selected_menus as $menu_id) {
                        $quantity = isset($menu_quantities[$menu_id]) ? intval($menu_quantities[$menu_id]) : 1;
                        $stmtPrice = $con->prepare("SELECT menu_price FROM menus WHERE menu_id = ?");
                        $stmtPrice->execute(array($menu_id));
                        $menu_price = $stmtPrice->fetch();
                        if ($menu_price) {
                            $total_amount += ($menu_price['menu_price'] * $quantity);
                        }
                    }
                    
                    // Thêm đơn hàng
                    $stmt_order = $con->prepare("INSERT INTO placed_orders(
                        order_time, client_id, delivery_address, total_amount, 
                        delivered, delivery_time, canceled, cancel_time, 
                        canceled_by, cancellation_reason
                    ) VALUES(?, ?, ?, ?, ?, NULL, ?, NULL, NULL, NULL)");
                    
                    $stmt_order->execute(array(
                        date("Y-m-d H:i:s"),
                        $client_id,
                        test_input($_POST['client_delivery_address']),
                        $total_amount,
                        0, // not delivered
                        0  // not canceled
                    ));
                    
                    $order_id = $con->lastInsertId();
                    
                    // Thêm chi tiết đơn hàng
                    foreach($selected_menus as $menu_id) {
                        $quantity = isset($menu_quantities[$menu_id]) ? intval($menu_quantities[$menu_id]) : 1;
                        $stmt = $con->prepare("INSERT INTO in_order(order_id, menu_id, quantity) VALUES(?, ?, ?)");
                        $stmt->execute(array($order_id, $menu_id, $quantity));
                    }
                    
                    $con->commit();
                    
                    // Lưu thông báo thành công vào session
                    $_SESSION['order_success'] = array(
                        'status' => 'success',
                        'order_id' => $order_id,
                        'message' => 'Đơn hàng đã được tạo thành công!'
                    );
                    
                    // Reset các session liên quan đến đơn hàng
                    unset($_SESSION['selected_menus']);
                    unset($_SESSION['menu_quantities']);
                    unset($_SESSION['client_full_name']);
                    unset($_SESSION['client_email']);
                    unset($_SESSION['client_phone_number']);
                    unset($_SESSION['client_delivery_address']);
                    unset($_SESSION['currentTab']);
                    unset($_SESSION['form_errors']);
                    
                    // Tải lại trang để hiển thị thông báo thành công
                    header("Location: order_food.php?success=true");
                    exit();
                    
                } catch(Exception $e) {
                    $con->rollBack();
                    echo "<div class='alert alert-danger'>";
                    echo "Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.";
                    echo "</div>";
                }
            }
        }
    }
    
    // Đảm bảo currentTab hợp lệ
    if (!isset($_SESSION['currentTab']) || $_SESSION['currentTab'] < 0 || $_SESSION['currentTab'] > 1) {
        $_SESSION['currentTab'] = 0;
    }
    
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
?>

    <!-- ORDER FOOD PAGE STYLE -->

	<style type="text/css">
        body
        {
            background: #f7f7f7;
        }

		.text_header
		{
			margin-bottom: 5px;
    		font-size: 18px;
    		font-weight: bold;
    		line-height: 1.5;
    		margin-top: 22px;
    		text-transform: capitalize;
		}

        .items_tab
        {
            border-radius: 4px;
            background-color: white;
            overflow: hidden;
            box-shadow: 0 0 5px 0 rgba(60, 66, 87, 0.04), 0 0 10px 0 rgba(0, 0, 0, 0.04);
        }

        .itemListElement
        {
            font-size: 14px;
            line-height: 1.29;
            border-bottom: solid 1px #e5e5e5;
            cursor: pointer;
            padding: 16px 12px 18px 12px;
        }

        .item_details
        {
            width: auto;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -webkit-flex-direction: row;
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            -webkit-box-align: center;
            -webkit-align-items: center;
        }

        .item_label
        {
        	color: #9e8a78;
            border-color: #9e8a78;
            background: white;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active 
        {
            color: #fff;
            background-color: #9e8a78;
            border-color: #9e8a78;
        }

        .item_select_part
        {
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
            flex-shrink: 0;
        }

        .select_item_bttn
        {
            width: 55px;
            display: flex;
            margin-left: 30px;
            -webkit-box-pack: end;
            justify-content: flex-end;
        }

        .menu_price_field
        {
        	width: auto;
            display: flex;
            margin-left: 30px;
            -webkit-box-align: baseline;
            align-items: baseline;
        }

        .order_food_section
        {
            max-width: 720px;
            margin: 50px auto;
            padding: 0px 15px;
        }

        .item_label.focus,
        .item_label:focus
        {
            outline: none;
            background:initial;
            box-shadow: none;
            color: #9e8a78;
            border-color: #9e8a78;
        }

        .item_label:hover
        {
            color: #fff;
            background-color: #9e8a78;
            border-color: #9e8a78;
        }

        /* Make circles that indicate the steps of the form: */
        .step 
        {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;  
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active 
        {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish 
        {
            background-color: #4CAF50;
        }

        .order_food_tab
        {
            display: none;
        }

        .client_details_tab
        {
            display: none;
        }

        .next_prev_buttons
        {
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            cursor: pointer;
        }

        .client_details_tab  .form-control
        {
            background-color: #fff;
            border-radius: 0;
            padding: 25px 10px;
            box-shadow: none;
            border: 2px solid #eee;
        }

        .client_details_tab  .form-control:focus 
        {
            border-color: #ffc851;
            box-shadow: none;
            outline: none;
        }
        
        /* Kiểu dáng mới cho nút tăng/giảm và xóa */
        .quantity-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-color: #9e8a78;
            color: #9e8a78;
            transition: all 0.3s ease;
        }
        
        .quantity-btn:hover, .quantity-btn:focus {
            background-color: #9e8a78;
            color: white;
            border-color: #9e8a78;
        }
        
        .quantity-input {
            height: 32px;
            background-color: #fff;
            border-color: #9e8a78;
            color: #333;
            font-weight: 500;
        }
        
        .remove-btn {
            width: 34px;
            height: 34px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .remove-btn:hover {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-primary {
            background-color: #9e8a78;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 10px;
        }
        
        .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .list-group-item:hover {
            border-left-color: #9e8a78;
            background-color: #f8f9fa;
        }

        /* CSS cho thông báo AJAX */
        #ajax-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 250px;
            max-width: 350px;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.4s ease;
        }

        #ajax-message.show {
            transform: translateY(0);
            opacity: 1;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* CSS cho hiệu ứng loading */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear 0.2s, opacity 0.2s;
        }

        .loading-overlay.show {
            visibility: visible;
            opacity: 1;
            transition-delay: 0s;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #9e8a78;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Thêm style cho thông báo thành công */
        .success-message {
            max-width: 600px;
            margin: 20px auto;
            text-align: center;
        }
        
        .success-message .alert {
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .success-message .alert-heading {
            color: #155724;
            margin-bottom: 10px;
        }
        
        .success-message p {
            margin-bottom: 8px;
        }

	</style>

    <!-- START ORDER FOOD SECTION -->

	<section class="order_food_section">
        <?php
        // Hiển thị thông báo thành công nếu có
        if (isset($_SESSION['order_success'])) {
            echo "<div class='success-message'>";
            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
            echo "<h4 class='alert-heading'>Đặt hàng thành công!</h4>";
            echo "<p>Mã đơn hàng: #" . $_SESSION['order_success']['order_id'] . "</p>";
            echo "<p class='mb-0'>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>";
            echo "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";

            echo "</button>";
            echo "</div>";
            echo "</div>";
            
            // Xóa thông báo sau khi hiển thị
            unset($_SESSION['order_success']);
            
        }
        ?>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loading-overlay">
            <div class="loading-spinner"></div>
        </div>
        
        <!-- Debug Session -->
        <?php if(isset($_GET['debug'])): ?>
        <div class="alert alert-info mb-4">
            <h4>Debug Information</h4>
            <p>Current Tab: <?php echo var_export($_SESSION['currentTab'], true); ?> (Type: <?php echo gettype($_SESSION['currentTab']); ?>)</p>
            <p>Selected Menus: <?php echo isset($_SESSION['selected_menus']) ? implode(', ', $_SESSION['selected_menus']) : 'None'; ?></p>
            <p>POST Data: <?php echo !empty($_POST) ? var_export($_POST, true) : 'No POST data'; ?></p>
            <p>Display Condition (Tab 0): <?php echo ($_SESSION['currentTab'] == 0) ? 'true' : 'false'; ?></p>
            <p>Display Condition (Tab 1): <?php echo ($_SESSION['currentTab'] == 1) ? 'true' : 'false'; ?></p>
            <p>Current Errors: <?php echo !empty($errors) ? var_export($errors, true) : 'No errors'; ?></p>
            
            <!-- Trạng thái hiển thị của các tab -->
            <p>Tab Order Food style: <?php echo $order_food_tab_style = ($_SESSION['currentTab'] == 0) ? 'block' : 'none'; ?></p>
            <p>Tab Client Details style: <?php echo $client_details_tab_style = ($_SESSION['currentTab'] == 1) ? 'block' : 'none'; ?></p>
        </div>
        <?php endif; ?>

        <?php

            if(isset($_POST['submit_order_food_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $isValid = true;
                $errors = [];
                
                // Validate client details
                if (empty(trim($_POST['client_full_name']))) {
                    $isValid = false;
                    $errors['client_full_name'] = "Vui lòng nhập họ tên";
                }
                
                if (empty(trim($_POST['client_email']))) {
                    $isValid = false;
                    $errors['client_email'] = "Vui lòng nhập email";
                } elseif (!filter_var($_POST['client_email'], FILTER_VALIDATE_EMAIL)) {
                    $isValid = false;
                    $errors['client_email'] = "Email không hợp lệ";
                }
                
                if (empty(trim($_POST['client_phone_number']))) {
                    $isValid = false;
                    $errors['client_phone_number'] = "Vui lòng nhập số điện thoại";
                }
                
                if (empty(trim($_POST['client_delivery_address']))) {
                    $isValid = false;
                    $errors['client_delivery_address'] = "Vui lòng nhập địa chỉ giao hàng";
                }
                
                // Check for selected menus
                $selected_menus = isset($_SESSION['selected_menus']) ? $_SESSION['selected_menus'] : [];
                $menu_quantities = isset($_SESSION['menu_quantities']) ? $_SESSION['menu_quantities'] : [];
                
                if (empty($selected_menus) || !is_array($selected_menus) || count($selected_menus) == 0) {
                    $isValid = false;
                    $errors['menu'] = "Vui lòng chọn ít nhất một món ăn";
                    $_SESSION['currentTab'] = 0;
                }
                
                if (!$isValid) {
                    // Lưu lỗi vào session
                    $_SESSION['form_errors'] = $errors;
                    
                    // Lưu dữ liệu đã nhập để điền lại form
                    $_SESSION['client_full_name'] = test_input($_POST['client_full_name']);
                    $_SESSION['client_email'] = test_input($_POST['client_email']);
                    $_SESSION['client_phone_number'] = test_input($_POST['client_phone_number']);
                    $_SESSION['client_delivery_address'] = test_input($_POST['client_delivery_address']);
                    
                    // Hiển thị thông báo lỗi
                    echo "<div class='alert alert-danger'>";
                    echo "<strong>Lỗi!</strong> Vui lòng kiểm tra lại các thông tin sau:<br>";
                    foreach ($errors as $error) {
                        echo "- " . $error . "<br>";
                    }
                    echo "</div>";
                } 
                else {
                    try {
                        $con->beginTransaction();
                        
                        // Thêm thông tin khách hàng
                        $stmtClient = $con->prepare("INSERT INTO clients(client_name,client_phone,client_email) VALUES(?,?,?)");
                        $stmtClient->execute(array(
                            test_input($_POST['client_full_name']),
                            test_input($_POST['client_phone_number']),
                            test_input($_POST['client_email'])
                        ));
                        
                        $client_id = $con->lastInsertId();
                        
                        // Tính tổng tiền
                        $total_amount = 0;
                        foreach($selected_menus as $menu_id) {
                            $quantity = isset($menu_quantities[$menu_id]) ? intval($menu_quantities[$menu_id]) : 1;
                            $stmtPrice = $con->prepare("SELECT menu_price FROM menus WHERE menu_id = ?");
                            $stmtPrice->execute(array($menu_id));
                            $menu_price = $stmtPrice->fetch();
                            if ($menu_price) {
                                $total_amount += ($menu_price['menu_price'] * $quantity);
                            }
                        }
                        
                        // Thêm đơn hàng
                        $stmt_order = $con->prepare("INSERT INTO placed_orders(
                            order_time, client_id, delivery_address, total_amount, 
                            delivered, delivery_time, canceled, cancel_time, 
                            canceled_by, cancellation_reason
                        ) VALUES(?, ?, ?, ?, ?, NULL, ?, NULL, NULL, NULL)");
                        
                        $stmt_order->execute(array(
                            date("Y-m-d H:i:s"),
                            $client_id,
                            test_input($_POST['client_delivery_address']),
                            $total_amount,
                            0, // not delivered
                            0  // not canceled
                        ));
                        
                        $order_id = $con->lastInsertId();
                        
                        // Thêm chi tiết đơn hàng
                        foreach($selected_menus as $menu_id) {
                            $quantity = isset($menu_quantities[$menu_id]) ? intval($menu_quantities[$menu_id]) : 1;
                            $stmt = $con->prepare("INSERT INTO in_order(order_id, menu_id, quantity) VALUES(?, ?, ?)");
                            $stmt->execute(array($order_id, $menu_id, $quantity));
                        }
                        
                        $con->commit();
                        
                        // Lưu thông báo thành công vào session
                        $_SESSION['order_success'] = array(
                            'status' => 'success',
                            'order_id' => $order_id,
                            'message' => 'Đơn hàng đã được tạo thành công!'
                        );
                        
                        // Reset các session liên quan đến đơn hàng
                        unset($_SESSION['selected_menus']);
                        unset($_SESSION['menu_quantities']);
                        unset($_SESSION['client_full_name']);
                        unset($_SESSION['client_email']);
                        unset($_SESSION['client_phone_number']);
                        unset($_SESSION['client_delivery_address']);
                        unset($_SESSION['currentTab']);
                        unset($_SESSION['form_errors']);
                        
                        // Tải lại trang để hiển thị thông báo thành công
                        header("Location: order_food.php?success=true");
                        exit();
                        
                    } catch(Exception $e) {
                        $con->rollBack();
                        echo "<div class='alert alert-danger'>";
                        echo "Có lỗi xảy ra khi xử lý đơn hàng. Vui lòng thử lại sau.";
                        echo "</div>";
                    }
                }
            }

        ?>

    <!-- FORM ĐẶT MÓN ĂN -->

    <form method="post" id="order_food_form" action="order_food.php">

<?php if ($_SESSION['currentTab'] == 0): ?>
    <!-- Tab chọn món ăn -->
    <div class="order_food_tab" style="display: block;">
        <!-- CHỌN DANH MỤC VÀ TÌM KIẾM -->
        <div class="row mb-4">
            <div class="col-md-6">
                <select class="form-control" id="category_filter" name="category_filter" onchange="this.form.submit()">
                    <option value="">Tất cả danh mục</option>
                    <?php
                    $stmt = $con->prepare("SELECT * FROM menu_categories");
                    $stmt->execute();
                    $categories = $stmt->fetchAll();
                    foreach ($categories as $cat) {
                        $selected = isset($_POST['category_filter']) && $_POST['category_filter'] == $cat['category_id'] ? 'selected' : '';
                        echo "<option value='".$cat['category_id']."' ".$selected.">".$cat['category_name']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="search_menu" name="search_menu" placeholder="Tìm kiếm món ăn..." value="<?php echo isset($_POST['search_menu']) ? $_POST['search_menu'] : ''; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="search_submit">Tìm</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Xử lý lọc và tìm kiếm
        $category_filter = isset($_POST['category_filter']) ? $_POST['category_filter'] : '';
        $search_query = isset($_POST['search_menu']) ? '%' . $_POST['search_menu'] . '%' : '';
        
        // Truy vấn danh sách danh mục
        $stmt = $con->prepare("SELECT * FROM menu_categories");
        $stmt->execute();
        $menu_categories = $stmt->fetchAll();

        foreach($menu_categories as $category)
        {
            // Lọc theo danh mục nếu có
            if (!empty($category_filter) && $category['category_id'] != $category_filter) {
                continue;
            }
            
            // Chuẩn bị truy vấn để lấy món ăn
            if (!empty($search_query)) {
                $stmt = $con->prepare("SELECT * FROM menus WHERE category_id = ? AND menu_name LIKE ?");
                $stmt->execute(array($category['category_id'], $search_query));
            } else {
                $stmt = $con->prepare("SELECT * FROM menus WHERE category_id = ?");
                $stmt->execute(array($category['category_id']));
            }
            
            $rows = $stmt->fetchAll();
            
            // Chỉ hiển thị danh mục nếu có món ăn
            if (count($rows) > 0) {
                ?>
                <div class="text_header">
                    <span>
                        <?php echo $category['category_name']; ?>
                    </span>
                </div>
                <div class="items_tab">
                    <?php
                    foreach($rows as $row)
                    {
                        echo "<div class='itemListElement menu-item' data-category='" . $category['category_id'] . "' data-name='" . strtolower($row['menu_name']) . "'>";
                            echo "<div class='item_details'>";
                                echo "<div style='display: flex; align-items: center;'>";
                                    if(!empty($row['menu_image'])) {
                                        echo "<img src='admin/Uploads/images/" . $row['menu_image'] . "' alt='" . $row['menu_name'] . "' style='width: 100px; height: 100px; object-fit: cover; margin-right: 15px; border-radius: 4px;'>";
                                    } else {
                                        echo "<img src='admin/Uploads/images/default.jpg' alt='Default Image' style='width: 100px; height: 100px; object-fit: cover; margin-right: 15px; border-radius: 4px;'>";
                                    }
                                    echo "<div style='flex: 1;'>";
                                        echo "<div style='font-weight: bold; font-size: 16px; margin-bottom: 5px;'>" . $row['menu_name'] . "</div>";
                                        echo "<div style='font-size: 13px; color: #666; line-height: 1.4;'>" . $row['menu_description'] . "</div>";
                                    echo "</div>";
                                echo "</div>";
                                echo "<div class='item_select_part'>";
                                    echo "<div class='menu_price_field'>";
                                        echo "<span style='font-weight: bold; font-size: 16px; color: #9e8a78;'>";
                                            echo number_format(intval($row['menu_price']), 0, ',', '.') . ".000đ";
                                        echo "</span>";
                                    echo "</div>";
                                    ?>
                                    <div class="select_item_bttn">
                                        <div class="btn-group-toggle" data-toggle="buttons">
                                            <label class="menu_label item_label btn btn-secondary">
                                                <input type="checkbox" name="selected_menus[]" value="<?php echo $row['menu_id'] ?>" <?php echo (isset($_SESSION['selected_menus']) && in_array($row['menu_id'], $_SESSION['selected_menus'])) ? 'checked' : ''; ?> autocomplete="off">Chọn
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
                <?php
            }
        }
        ?>

        <!-- DANH SÁCH MÓN ĂN -->
        <div class="text_header" style="margin-top: 30px;">
            <span>
                    Danh Sách Món Ăn Đã Chọn
            </span>
        </div>

        <div class="selected-items-container" style="background: white; padding: 20px; border-radius: 4px; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
            <div id="selected-items-list">
                <?php
                // Hiển thị món ăn đã chọn từ session
                if (isset($_SESSION['selected_menus']) && is_array($_SESSION['selected_menus']) && !empty($_SESSION['selected_menus'])) {
                    echo '<ul class="list-group">';
                    $totalPrice = 0;
                    
                    foreach ($_SESSION['selected_menus'] as $menuId) {
                        // Lấy thông tin món ăn từ database
                        $stmt = $con->prepare("SELECT * FROM menus WHERE menu_id = ?");
                        $stmt->execute(array($menuId));
                        $menu = $stmt->fetch();
                        
                        if ($menu) {
                            $quantity = isset($_SESSION['menu_quantities'][$menuId]) ? $_SESSION['menu_quantities'][$menuId] : 1;
                            $itemPrice = $menu['menu_price'] * $quantity;
                            $totalPrice += $itemPrice;
                            
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center" id="menu-item-' . $menuId . '">';
                                echo '<div>' . $menu['menu_name'] . '</div>';
                                echo '<div class="d-flex align-items-center">';
                                echo '<div class="quantity-selector mr-3">';
                                echo '<div class="input-group input-group-sm">';
                                echo '<button type="button" class="btn btn-outline-secondary quantity-btn decrease-btn" data-menu-id="' . $menuId . '"><i class="fas fa-minus"></i></button>';
                                echo '<input type="number" name="menu_quantities[' . $menuId . ']" value="' . $quantity . '" min="1" max="99" class="form-control quantity-input" style="width: 50px; text-align: center;" data-menu-id="' . $menuId . '">';
                                echo '<button type="button" class="btn btn-outline-secondary quantity-btn increase-btn" data-menu-id="' . $menuId . '"><i class="fas fa-plus"></i></button>';
                                echo '</div>';
                                echo '</div>';
                                echo '<span class="badge badge-primary badge-pill mr-2 item-price" data-menu-id="' . $menuId . '" data-unit-price="' . $menu['menu_price'] . '">' . number_format($itemPrice, 0, ',', '.') . '.000đ</span>';
                                echo '<button type="button" class="btn btn-outline-danger remove-btn" title="Xóa món" data-menu-id="' . $menuId . '"><i class="fas fa-trash-alt"></i></button>';
                                echo '</div>';
                            echo '</li>';
                        }
                    }
                    
                    echo '</ul>';
                } else {
                    echo '<p class="empty-cart-message">Chưa có món ăn nào được chọn</p>';
                }
                ?>
            </div>
            <div class="total-price" style="text-align: right; margin-top: 20px; font-size: 18px; font-weight: bold;">
                Tổng tiền: <span id="total-price">
                <?php
                // Hiển thị tổng tiền
                if (isset($totalPrice)) {
                    echo number_format($totalPrice, 0, ',', '.') . '.000đ';
                } else {
                    echo '0đ';
                }
                ?>
                </span>
            </div>
            
            <!-- Thông báo kết quả AJAX -->
            <div id="ajax-message" class="mt-3" style="display: none;"></div>
        </div>
    </div>
<?php else: ?>
    <!-- Tab thông tin khách hàng -->
    <div class="client_details_tab" style="display: block;">
        <div class="text_header">
            <span>
                Thông Tin Khách Hàng
            </span>
        </div>

        <div>
            <div class="form-group colum-row row">
                <div class="col-sm-12">
                    <input type="text" name="client_full_name" id="client_full_name" 
                           value="<?php echo isset($_SESSION['client_full_name']) ? $_SESSION['client_full_name'] : ''; ?>" 
                           class="form-control <?php echo isset($errors['client_full_name']) ? 'is-invalid' : ''; ?>" 
                           placeholder="Họ và Tên">
                    <?php if(isset($errors['client_full_name'])): ?>
                    <div class="invalid-feedback" id="required_fname" style="display: block;">
                        <?php echo $errors['client_full_name']; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="email" name="client_email" id="client_email" 
                           value="<?php echo isset($_SESSION['client_email']) ? $_SESSION['client_email'] : ''; ?>" 
                           class="form-control <?php echo isset($errors['client_email']) ? 'is-invalid' : ''; ?>" 
                           placeholder="Email">
                    <?php if(isset($errors['client_email'])): ?>
                    <div class="invalid-feedback" id="required_email" style="display: block;">
                        <?php echo $errors['client_email']; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="client_phone_number" id="client_phone_number" 
                           value="<?php echo isset($_SESSION['client_phone_number']) ? $_SESSION['client_phone_number'] : ''; ?>" 
                           class="form-control <?php echo isset($errors['client_phone_number']) ? 'is-invalid' : ''; ?>" 
                           placeholder="Số điện thoại">
                    <?php if(isset($errors['client_phone_number'])): ?>
                    <div class="invalid-feedback" id="required_phone" style="display: block;">
                        <?php echo $errors['client_phone_number']; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group colum-row row">
                <div class="col-sm-12">
                    <input type="text" name="client_delivery_address" id="client_delivery_address" 
                           value="<?php echo isset($_SESSION['client_delivery_address']) ? $_SESSION['client_delivery_address'] : ''; ?>" 
                           class="form-control <?php echo isset($errors['client_delivery_address']) ? 'is-invalid' : ''; ?>" 
                           placeholder="Địa chỉ giao hàng">
                    <?php if(isset($errors['client_delivery_address'])): ?>
                    <div class="invalid-feedback" id="required_delivery_address" style="display: block;">
                        <?php echo $errors['client_delivery_address']; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Thông báo kết quả AJAX -->
        <div id="ajax-message-customer" class="mt-3" style="display: none;"></div>
    </div>
<?php endif; ?>

<!-- NÚT TIẾP THEO VÀ QUAY LẠI -->

    <div style="overflow:auto;padding: 30px;">
        <div style="float:right;">
            <?php if($_SESSION['currentTab'] > 0): ?>
            <button type="submit" name="prev_tab" class="next_prev_buttons" style="background-color: #bbbbbb;">Quay Lại</button>
            <?php endif; ?>
            
            <?php if($_SESSION['currentTab'] < 1): ?>
            <button type="button" id="next_tab_button" class="next_prev_buttons">Tiếp Theo</button>
            <input type="hidden" name="next_tab" id="next_tab_input" value="">
            <?php else: ?>
            <button type="submit" name="submit_order_food_form" class="next_prev_buttons">Gửi Đơn Hàng</button>
            <?php endif; ?>
        </div>
    </div>

    <!-- VÒNG TRÒN HIỂN THỊ BƯỚC -->

    <div style="text-align:center;margin-top:40px;">
        <span class="step <?php echo ($_SESSION['currentTab'] >= 0) ? 'active' : ''; ?> <?php echo ($_SESSION['currentTab'] > 0) ? 'finish' : ''; ?>"></span>
        <span class="step <?php echo ($_SESSION['currentTab'] >= 1) ? 'active' : ''; ?>"></span>
    </div>
    
    </form>

    <!-- Script để đảm bảo hiển thị tab đúng -->
    <script>
        // Đảm bảo hiển thị tab đúng dựa vào giá trị currentTab
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy các phần tử tab
            const orderFoodTab = document.querySelector('.order_food_tab');
            const clientDetailsTab = document.querySelector('.client_details_tab');
            
            // Lấy giá trị currentTab từ PHP
            const currentTab = <?php echo $_SESSION['currentTab']; ?>;
            
            console.log('Current tab from PHP:', currentTab);
            
            // Set hiển thị dựa vào giá trị currentTab
            if (currentTab == 0) {
                orderFoodTab.style.display = 'block';
                clientDetailsTab.style.display = 'none';
                console.log('Showing order food tab');
            } else if (currentTab == 1) {
                orderFoodTab.style.display = 'none';
                clientDetailsTab.style.display = 'block';
                console.log('Showing client details tab');
            }
        });
    </script>

	</section>


	<!-- WIDGET SECTION / FOOTER -->

    <section class="widget_section" style="background-color: #222227;padding: 100px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px;margin-bottom: 20px;">
                        <p>
                            Nhà hàng của chúng tôi là một trong những nhà hàng tốt nhất, cung cấp thực đơn và món ăn ngon. Bạn có thể đặt bàn hoặc gọi món.
                        </p>
                        <ul class="widget_social">
                            <li><a href="#" data-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Twitter"><i class="fab fa-twitter fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Instagram"><i class="fab fa-instagram fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="LinkedIn"><i class="fab fa-linkedin fa-2x"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="Google+"><i class="fab fa-google-plus-g fa-2x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                     <div class="footer_widget">
                        <h3>Trụ Sở Chính</h3>
                        <p>
                            PTIT
                        </p>
                        <p>
                            contact@restaurant.com
                            <br>
                            (+123) 456 789 101    
                        </p>
                     </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer_widget">
                        <h3>
                            Giờ Mở Cửa
                        </h3>
                        <ul class="opening_time">
                            <li>Thứ Hai - Thứ Sáu 11:30am - 2:00pm</li>
                            <li>Thứ Bảy - Chủ Nhật 11:30am - 3:00pm</li>
                            <li>Thứ Hai - Chủ Nhật 5:30pm - 10:00pm</li>
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

    <!-- FOOTER BOTTOM  -->

    <?php include "Includes/templates/footer.php"; ?>
    
    <!-- JavaScript để xử lý sự kiện chọn món ăn và các thao tác AJAX -->
    <script>

        //khởi tạo DOM
        document.addEventListener('DOMContentLoaded', function() {
            // Thiết lập sự kiện cho tất cả các checkbox chọn món
            const checkboxes = document.querySelectorAll('input[name="selected_menus[]"]');
            
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() { // Lắng nghe sự kiện khi checkbox được chọn/bỏ chọn.
                    const menuId = this.value;
                    const isChecked = this.checked;
                    
                    showLoading();
                    
                    // Gửi yêu cầu AJAX để cập nhật trạng thái chọn/bỏ chọn
                    fetch('order_food_ajax.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=' + (isChecked ? 'select' : 'unselect') + '&menu_id=' + menuId
                    })
                    .then(response => response.json()) //Chuyển đổi phản hồi thành json
                    .then(data => {
                        if (data.success) {
                            // Cập nhật UI dựa trên kết quả từ server
                            if (isChecked) {
                                // Highlight món ăn đã chọn
                                const menuItem = this.closest('.menu-item');
                                if (menuItem) {
                                    menuItem.style.backgroundColor = '#f8f9fa';
                                    menuItem.style.borderLeft = '3px solid #9e8a78';
                                }
                                
                                // Nếu trước đó giỏ hàng trống, xóa thông báo "Chưa có món ăn nào được chọn"
                                const emptyCartMessage = document.querySelector('.empty-cart-message');
                                if (emptyCartMessage) {
                                    // Tạo danh sách mới
                                    document.getElementById('selected-items-list').innerHTML = '<ul class="list-group"></ul>';
                                }
                                
                                // Thêm món ăn vào danh sách đã chọn
                                if (!document.getElementById('menu-item-' + menuId)) {
                                    const menuList = document.querySelector('#selected-items-list .list-group');
                                    if (menuList) {
                                        const newItem = createMenuItemElement(data);
                                        menuList.appendChild(newItem);
                                        
                                        // Khởi tạo sự kiện cho các nút trong món ăn mới thêm
                                        setupItemEvents(newItem);
                                    }
                                }
                            } else {
                                // Bỏ highlight món ăn đã bỏ chọn
                                const menuItem = this.closest('.menu-item');
                                if (menuItem) {
                                    menuItem.style.backgroundColor = '';
                                    menuItem.style.borderLeft = '';
                                }
                                
                                // Xóa món ăn khỏi danh sách đã chọn
                                const selectedItem = document.getElementById('menu-item-' + menuId);
                                if (selectedItem) {
                                    selectedItem.remove();
                                }
                                
                                // Kiểm tra nếu giỏ hàng trống
                                if (data.is_empty) {
                                    document.getElementById('selected-items-list').innerHTML = '<p class="empty-cart-message">Chưa có món ăn nào được chọn</p>';
                                }
                            }
                            
                            // Cập nhật tổng tiền
                            document.getElementById('total-price').textContent = data.total_formatted;
                            
                            showSuccessMessage(data.message);
                        } else {
                            showErrorMessage(data.message);
                            
                            // Khôi phục trạng thái checkbox nếu có lỗi
                            this.checked = !isChecked;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorMessage('Đã xảy ra lỗi. Vui lòng thử lại sau.');
                        
                        // Khôi phục trạng thái checkbox nếu có lỗi
                        this.checked = !isChecked;
                    })
                    .finally(() => {
                        hideLoading();
                    });
                });
            });
            
            // Hàm tạo phần tử HTML cho món ăn mới
            function createMenuItemElement(data) {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.id = 'menu-item-' + data.menu_id;
                
                // Tạo nội dung cho món ăn mới
                li.innerHTML = 
                    '<div>' + data.menu_name + '</div>' +
                    '<div class="d-flex align-items-center">' +
                        '<div class="quantity-selector mr-3">' +
                            '<div class="input-group input-group-sm">' +
                                '<button type="button" class="btn btn-outline-secondary quantity-btn decrease-btn" data-menu-id="' + data.menu_id + '"><i class="fas fa-minus"></i></button>' +
                                '<input type="number" name="menu_quantities[' + data.menu_id + ']" value="' + data.quantity + '" min="1" max="99" class="form-control quantity-input" style="width: 50px; text-align: center;" data-menu-id="' + data.menu_id + '">' +
                                '<button type="button" class="btn btn-outline-secondary quantity-btn increase-btn" data-menu-id="' + data.menu_id + '"><i class="fas fa-plus"></i></button>' +
                            '</div>' +
                        '</div>' +
                        '<span class="badge badge-primary badge-pill mr-2 item-price" data-menu-id="' + data.menu_id + '" data-unit-price="' + data.unit_price + '">' + data.item_price_formatted + '</span>' +
                        '<button type="button" class="btn btn-outline-danger remove-btn" title="Xóa món" data-menu-id="' + data.menu_id + '"><i class="fas fa-trash-alt"></i></button>' +
                    '</div>';
                
                return li;
            }
            
            // Hàm thiết lập sự kiện cho các nút trong món ăn mới thêm
            function setupItemEvents(itemElement) {
                // Thiết lập sự kiện cho nút tăng
                const increaseBtn = itemElement.querySelector('.increase-btn');
                if (increaseBtn) {
                    increaseBtn.addEventListener('click', function() {
                        const menuId = this.getAttribute('data-menu-id');
                        updateQuantity(menuId, 'increase');
                    });
                }
                
                // Thiết lập sự kiện cho nút giảm
                const decreaseBtn = itemElement.querySelector('.decrease-btn');
                if (decreaseBtn) {
                    decreaseBtn.addEventListener('click', function() {
                        const menuId = this.getAttribute('data-menu-id');
                        updateQuantity(menuId, 'decrease');
                    });
                }
                
                // Thiết lập sự kiện cho input số lượng
                const quantityInput = itemElement.querySelector('.quantity-input');
                if (quantityInput) {
                    quantityInput.addEventListener('change', function() {
                        const menuId = this.getAttribute('data-menu-id');
                        const newQuantity = parseInt(this.value);
                        
                        if (newQuantity >= 1 && newQuantity <= 99) {
                            updateQuantityDirect(menuId, newQuantity);
                        } else {
                            // Reset về giá trị hợp lệ nếu người dùng nhập không hợp lệ
                            this.value = Math.max(1, Math.min(99, newQuantity || 1));
                            updateQuantityDirect(menuId, parseInt(this.value));
                        }
                    });
                }
                
                // Thiết lập sự kiện cho nút xóa
                const removeBtn = itemElement.querySelector('.remove-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        const menuId = this.getAttribute('data-menu-id');
                        removeItem(menuId);
                    });
                }
            }
            
            // Hàm tăng/giảm số lượng
            function updateQuantity(menuId, action) {
                // Hiển thị hiệu ứng loading
                showLoading();
                
                // Gửi yêu cầu AJAX
                fetch('order_food_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=' + action + '&menu_id=' + menuId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật UI
                        updateUI(data);
                        showSuccessMessage(data.message);
                    } else {
                        showErrorMessage(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Đã xảy ra lỗi. Vui lòng thử lại sau.');
                })
                .finally(() => {
                    hideLoading();
                });
            }
            
            // Hàm cập nhật số lượng trực tiếp
            function updateQuantityDirect(menuId, quantity) {
                // Hiển thị hiệu ứng loading
                showLoading();
                
                // Gửi yêu cầu AJAX
                fetch('order_food_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=update_direct&menu_id=' + menuId + '&quantity=' + quantity
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cập nhật UI
                        updateUI(data);
                        showSuccessMessage(data.message);
                    } else {
                        showErrorMessage(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Đã xảy ra lỗi. Vui lòng thử lại sau.');
                })
                .finally(() => {
                    hideLoading();
                });
            }
            
            // Hàm xóa món ăn
            function removeItem(menuId) {
                if (confirm('Bạn có chắc muốn xóa món ăn này khỏi danh sách không?')) {
                    // Hiển thị hiệu ứng loading
                    showLoading();
                    
                    // Gửi yêu cầu AJAX
                    fetch('order_food_ajax.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=remove&menu_id=' + menuId
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Xóa phần tử khỏi UI
                            const itemElement = document.getElementById('menu-item-' + menuId);
                            if (itemElement) {
                                itemElement.remove();
                            }
                            
                            // Cập nhật tổng tiền
                            document.getElementById('total-price').textContent = data.total_formatted;
                            
                            // Kiểm tra nếu giỏ hàng trống
                            if (data.is_empty) {
                                document.getElementById('selected-items-list').innerHTML = '<p class="empty-cart-message">Chưa có món ăn nào được chọn</p>';
                            }
                            
                            // Bỏ chọn checkbox tương ứng
                            const checkbox = document.querySelector('input[type="checkbox"][value="' + menuId + '"]');
                            if (checkbox) {
                                checkbox.checked = false;
                                
                                // Xóa highlight trên món ăn trong danh sách
                                const menuItem = checkbox.closest('.menu-item');
                                if (menuItem) {
                                    menuItem.style.backgroundColor = '';
                                    menuItem.style.borderLeft = '';
                                }
                            }
                            
                            showSuccessMessage(data.message);
                        } else {
                            showErrorMessage(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorMessage('Đã xảy ra lỗi. Vui lòng thử lại sau.');
                    })
                    .finally(() => {
                        hideLoading();
                    });
                }
            }
            
            // Hàm cập nhật UI sau khi nhận phản hồi từ server
            function updateUI(data) {
                // Cập nhật số lượng trên input
                const inputElement = document.querySelector('.quantity-input[data-menu-id="' + data.menu_id + '"]');
                if (inputElement) {
                    inputElement.value = data.quantity;
                }
                
                // Cập nhật giá tiền của món
                const priceElement = document.querySelector('.item-price[data-menu-id="' + data.menu_id + '"]');
                if (priceElement) {
                    priceElement.textContent = data.item_price_formatted;
                }
                
                // Cập nhật tổng tiền
                document.getElementById('total-price').textContent = data.total_formatted;
            }
            
            // Hiển thị thông báo thành công
            function showSuccessMessage(message) {
                const messageElement = document.getElementById('ajax-message');
                messageElement.className = 'alert alert-success';
                messageElement.textContent = message;
                messageElement.classList.add('show');
                
                // Tự động ẩn sau 3 giây
                setTimeout(() => {
                    messageElement.classList.remove('show');
                }, 3000);
            }
            
            // Hiển thị thông báo lỗi
            function showErrorMessage(message) {
                const messageElement = document.getElementById('ajax-message');
                messageElement.className = 'alert alert-danger';
                messageElement.textContent = message;
                messageElement.classList.add('show');
                
                // Tự động ẩn sau 3 giây
                setTimeout(() => {
                    messageElement.classList.remove('show');
                }, 3000);
            }
            
            // Hiển thị hiệu ứng loading
            function showLoading() {
                document.getElementById('loading-overlay').classList.add('show');
            }
            
            // Ẩn hiệu ứng loading
            function hideLoading() {
                document.getElementById('loading-overlay').classList.remove('show');
            }

            // AJAX cho nút tăng số lượng
            document.querySelectorAll('.increase-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-menu-id');
                    updateQuantity(menuId, 'increase');
                });
            });

            // AJAX cho nút giảm số lượng
            document.querySelectorAll('.decrease-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-menu-id');
                    updateQuantity(menuId, 'decrease');
                });
            });

            // AJAX cho nút xóa món
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const menuId = this.getAttribute('data-menu-id');
                    removeItem(menuId);
                });
            });

            // AJAX cho thay đổi số lượng trực tiếp từ input
            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', function() {
                    const menuId = this.getAttribute('data-menu-id');
                    const newQuantity = parseInt(this.value);
                    
                    if (newQuantity >= 1 && newQuantity <= 99) {
                        updateQuantityDirect(menuId, newQuantity);
                    } else {
                        // Reset về giá trị hợp lệ nếu người dùng nhập không hợp lệ
                        this.value = Math.max(1, Math.min(99, newQuantity || 1));
                        updateQuantityDirect(menuId, parseInt(this.value));
                    }
                });
            });

            // Xử lý sự kiện click nút Tiếp theo
            document.getElementById('next_tab_button').addEventListener('click', function() {
                console.log('Next tab button clicked');
                
                // Lấy danh sách món ăn đã chọn từ các checkbox
                const selectedMenus = [];
                document.querySelectorAll('input[name="selected_menus[]"]:checked').forEach(checkbox => {
                    selectedMenus.push(checkbox.value);
                });
                
                console.log('Selected menus:', selectedMenus);
                
                // Kiểm tra nếu không có món ăn nào được chọn
                if (selectedMenus.length === 0) {
                    showErrorMessage('Vui lòng chọn ít nhất một món ăn');
                    return;
                }
                
                // Đặt giá trị cho input ẩn để biết form đã được submit bằng nút Tiếp theo
                document.getElementById('next_tab_input').value = '1';
                
                // Lưu URL hiện tại để xử lý chuyển hướng
                const currentURL = window.location.href.split('?')[0];
                
                // Đặt action của form với force_tab=1 để đảm bảo hiển thị tab thông tin khách hàng
                const form = document.getElementById('order_food_form');
                form.action = currentURL + '?force_tab=1';
                
                console.log('Submitting form with selected menus to:', form.action);
                
                // Submit form để chuyển sang bước tiếp theo
                form.submit();
            });

            // Tô màu nền cho món ăn đã chọn để dễ nhận biết khi trang được tải
            const menuItems = document.querySelectorAll('.menu-item');
            const selectedMenus = <?php echo json_encode(isset($_SESSION['selected_menus']) ? $_SESSION['selected_menus'] : []); ?>;

            menuItems.forEach(function(item) {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox && selectedMenus.includes(checkbox.value)) {
                    item.style.backgroundColor = '#f8f9fa';
                    item.style.borderLeft = '3px solid #9e8a78';
                }
            });
        });
    </script>
    
<?php
// Kích hoạt đầu ra đệm
ob_end_flush();
?>

<!-- Debug thông tin hiển thị nếu cần -->
<?php if(isset($_GET['debug'])): ?>
<div class="alert alert-info mt-3">
    <p><strong>Debug Order Tabs:</strong></p>
    <p>Current Tab: <?php echo $_SESSION['currentTab']; ?> (<?php echo gettype($_SESSION['currentTab']); ?>)</p>
    <p>Tab Order Food display: <span id="debug-tab-0-display"></span></p>
    <p>Tab Client Details display: <span id="debug-tab-1-display"></span></p>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('debug-tab-0-display').textContent = 
                getComputedStyle(document.querySelector('.order_food_tab')).display;
            document.getElementById('debug-tab-1-display').textContent = 
                getComputedStyle(document.querySelector('.client_details_tab')).display;
        });
    </script>
</div>
<?php endif; ?>
