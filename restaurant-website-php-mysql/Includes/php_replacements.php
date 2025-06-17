<?php

if (!isset($_SESSION)) {
    session_start();
}

/** 
 * ======================================
 * HỖ TRỢ HIỂN THỊ UI
 * ======================================
 */

/**
 * Thêm class 'active' dựa trên điều kiện
 * 
 * @param string|null $current Giá trị hiện tại
 * @param string $match Giá trị để so sánh
 * @param string $class Class để thêm nếu khớp
 * @return string Class nếu khớp, chuỗi rỗng nếu không
 */
function active_class($current, $match, $class = 'active') {
    return ($current == $match) ? $class : '';
}

/**
 * Đặt thuộc tính style display dựa trên điều kiện
 * 
 * @param string|null $current Giá trị hiện tại
 * @param string $match Giá trị để so sánh
 * @param string $display Giá trị hiển thị nếu khớp
 * @return string Giá trị thuộc tính display
 */
function display_if($current, $match, $display = 'block') {
    return ($current == $match) ? $display : 'none';
}

/**
 * Hiển thị thông báo lỗi cho các trường form
 * 
 * @param string $field Tên trường để hiển thị lỗi
 * @param array $errors Mảng chứa lỗi
 * @return string HTML hiển thị lỗi
 */
function show_error($field, $errors = null) {
    if ($errors === null && isset($_SESSION['form_errors'])) {
        $errors = $_SESSION['form_errors'];
    }
    
    if (isset($errors[$field])) {
        return '<div class="invalid-feedback" style="display: block;">' . $errors[$field] . '</div>';
    }
    
    return '';
}

/**
 * Kiểm tra nếu một tùy chọn nên được chọn
 * 
 * @param mixed $value Giá trị để kiểm tra
 * @param mixed $currentValue Giá trị hiện tại
 * @return string Thuộc tính 'selected' nếu khớp
 */
function is_selected($value, $currentValue) {
    return ($value == $currentValue) ? 'selected' : '';
}

/**
 * Kiểm tra nếu một checkbox/radio nên được đánh dấu
 * 
 * @param mixed $value Giá trị để kiểm tra
 * @param array $selectedValues Giá trị đã chọn
 * @return string Thuộc tính 'checked' nếu khớp
 */
function is_checked($value, $selectedValues) {
    return (is_array($selectedValues) && in_array($value, $selectedValues)) ? 'checked' : '';
}

/**
 * Xử lý hiển thị tab dựa trên session hoặc giá trị mặc định
 * 
 * @param string $defaultTab Tab mặc định nếu không có tab nào được chọn
 * @param string $sessionKey Khóa session để lưu tab hiện tại
 * @return string Tab hiện tại
 */
function get_active_tab($defaultTab = '', $sessionKey = 'active_tab') {
    if (isset($_GET['tab'])) {
        $_SESSION[$sessionKey] = $_GET['tab'];
        return $_GET['tab'];
    } elseif (isset($_SESSION[$sessionKey])) {
        return $_SESSION[$sessionKey];
    } else {
        return $defaultTab;
    }
}

/**
 * Xử lý lọc danh mục dựa trên session hoặc giá trị mặc định
 * 
 * @param string $defaultCategory Danh mục mặc định nếu không có danh mục nào được chọn
 * @param string $sessionKey Khóa session để lưu danh mục hiện tại
 * @return string Danh mục hiện tại
 */
function get_active_category($defaultCategory = '', $sessionKey = 'active_category') {
    if (isset($_GET['category'])) {
        $_SESSION[$sessionKey] = $_GET['category'];
        return $_GET['category'];
    } elseif (isset($_SESSION[$sessionKey])) {
        return $_SESSION[$sessionKey];
    } else {
        return $defaultCategory;
    }
}

/**
 * Xử lý tìm kiếm dựa trên session hoặc giá trị mặc định
 * 
 * @param string $defaultSearch Từ khóa tìm kiếm mặc định nếu không có từ khóa nào được nhập
 * @param string $sessionKey Khóa session để lưu từ khóa tìm kiếm hiện tại
 * @return string Từ khóa tìm kiếm hiện tại
 */
function get_search_term($defaultSearch = '', $sessionKey = 'search_term') {
    if (isset($_GET['search']) || isset($_POST['search'])) {
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : $_POST['search'];
        $_SESSION[$sessionKey] = $searchTerm;
        return $searchTerm;
    } elseif (isset($_SESSION[$sessionKey])) {
        return $_SESSION[$sessionKey];
    } else {
        return $defaultSearch;
    }
}

/**
 * Lấy giá trị trường từ session hoặc giá trị mặc định
 * 
 * @param string $field Tên trường để lấy giá trị
 * @param string $default Giá trị mặc định nếu không có giá trị nào tồn tại
 * @param string $sessionKey Khóa session để lưu giá trị trường
 * @return string Giá trị hiện tại của trường
 */
function get_field_value($field, $default = '', $sessionKey = 'form_data') {
    if (isset($_POST[$field])) {
        $_SESSION[$sessionKey][$field] = $_POST[$field];
        return $_POST[$field];
    } elseif (isset($_SESSION[$sessionKey][$field])) {
        return $_SESSION[$sessionKey][$field];
    } else {
        return $default;
    }
}

/** 
 * ======================================
 * CHỨC NĂNG GIỎ HÀNG
 * ======================================
 */

/**
 * Tăng số lượng sản phẩm
 * 
 * @param int $productId ID sản phẩm
 * @param int $max Số lượng tối đa
 * @param string $sessionKey Khóa session để lưu số lượng
 * @return int Số lượng sản phẩm sau khi tăng
 */
function increase_quantity($productId, $max = 99, $sessionKey = 'quantities') {
    if (!isset($_SESSION[$sessionKey][$productId])) {
        $_SESSION[$sessionKey][$productId] = 1;
    } elseif ($_SESSION[$sessionKey][$productId] < $max) {
        $_SESSION[$sessionKey][$productId]++;
    }
    
    return $_SESSION[$sessionKey][$productId];
}

/**
 * Giảm số lượng sản phẩm
 * 
 * @param int $productId ID sản phẩm
 * @param int $min Số lượng tối thiểu
 * @param string $sessionKey Khóa session để lưu số lượng
 * @return int Số lượng sản phẩm sau khi giảm
 */
function decrease_quantity($productId, $min = 1, $sessionKey = 'quantities') {
    if (!isset($_SESSION[$sessionKey][$productId])) {
        $_SESSION[$sessionKey][$productId] = $min;
    } elseif ($_SESSION[$sessionKey][$productId] > $min) {
        $_SESSION[$sessionKey][$productId]--;
    }
    
    return $_SESSION[$sessionKey][$productId];
}

/**
 * Lấy số lượng sản phẩm từ session
 * 
 * @param int $productId ID sản phẩm
 * @param int $default Số lượng mặc định nếu không có số lượng nào tồn tại
 * @param string $sessionKey Khóa session để lưu số lượng
 * @return int Số lượng sản phẩm hiện tại
 */
function get_quantity($productId, $default = 1, $sessionKey = 'quantities') {
    return isset($_SESSION[$sessionKey][$productId]) ? $_SESSION[$sessionKey][$productId] : $default;
}

/** 
 * ======================================
 * XỬ LÝ YÊU CẦU
 * ======================================
 */

// Xử lý các hành động dựa trên yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Xử lý lựa chọn tab
    if (isset($_GET['tab'])) {
        $_SESSION['active_tab'] = $_GET['tab'];
    }
    
    // Xử lý lựa chọn danh mục
    if (isset($_GET['category'])) {
        $_SESSION['active_category'] = $_GET['category'];
    }
    
    // Xử lý tìm kiếm
    if (isset($_GET['search']) || isset($_POST['search'])) {
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : $_POST['search'];
        $_SESSION['search_term'] = $searchTerm;
    }
    
    // Xử lý tăng số lượng sản phẩm
    if (isset($_POST['increase_quantity'])) {
        $productId = key($_POST['increase_quantity']);
        increase_quantity($productId);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    
    // Xử lý giảm số lượng sản phẩm
    if (isset($_POST['decrease_quantity'])) {
        $productId = key($_POST['decrease_quantity']);
        decrease_quantity($productId);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?> 