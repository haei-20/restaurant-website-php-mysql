<?php
if (!isset($_SESSION)) {
    session_start();
}

/**
 * Xác thực form đăng nhập admin
 * 
 * @param string $username Tên đăng nhập
 * @param string $password Mật khẩu
 * @return array Mảng chứa các lỗi
 */
function validateAdminLoginForm($username, $password)
{
    $errors = [];
    
    if (empty($username) && empty($password)) {
        $errors['username'] = "Tên đăng nhập không được để trống";
        $errors['password'] = "Mật khẩu không được để trống";
    } elseif (empty($username)) {
        $errors['username'] = "Tên đăng nhập không được để trống";
    } elseif (empty($password)) {
        $errors['password'] = "Mật khẩu không được để trống";
    }
    
    return $errors;
}

/**
 * Hiển thị lớp active cho tab
 * 
 * @param string $current Tab hiện tại
 * @param string $tab Tab cần kiểm tra
 * @return string Class active nếu tab hiện tại khớp với tab cần kiểm tra
 */
function activeTabClass($current, $tab)
{
    return $current == $tab ? 'active' : '';
}

/**
 * Mở tab mới và lưu vào session
 * 
 * @param string $tabName Tên tab cần mở
 * @return void
 */
function openAdminTab($tabName)
{
    $_SESSION['active_admin_tab'] = $tabName;
    
    // Redirect để giữ nguyên URL
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/**
 * Lấy tab hiện tại từ session
 * 
 * @param string $default Tab mặc định
 * @return string Tab hiện tại
 */
function getCurrentAdminTab($default = '')
{
    if (isset($_GET['tab'])) {
        $_SESSION['active_admin_tab'] = $_GET['tab'];
        return $_GET['tab'];
    } elseif (isset($_SESSION['active_admin_tab'])) {
        return $_SESSION['active_admin_tab'];
    } else {
        return $default;
    }
}

/**
 * Hiển thị nội dung tab dựa trên tab hiện tại
 * 
 * @param string $tabName Tab cần hiển thị
 * @param string $currentTab Tab hiện tại
 * @return string Style display
 */
function showTabContent($tabName, $currentTab)
{
    return $tabName == $currentTab ? 'display: table;' : 'display: none;';
}

/**
 * Kiểm tra và hiển thị lỗi form
 * 
 * @param string $field Tên trường form
 * @param array $errors Mảng chứa các lỗi
 * @return string HTML lỗi nếu có
 */
function showFormError($field, $errors)
{
    if (isset($errors[$field])) {
        return '<div class="invalid-feedback" style="display: block;">' . $errors[$field] . '</div>';
    }
    
    return '';
}

/**
 * Lấy giá trị đã nhập từ POST hoặc database
 * 
 * @param string $field Tên trường
 * @param mixed $defaultValue Giá trị mặc định
 * @return mixed Giá trị trường
 */
function getFieldValue($field, $defaultValue = '')
{
    if (isset($_POST[$field])) {
        return $_POST[$field];
    } else {
        return $defaultValue;
    }
}

/**
 * Xác thực email
 * 
 * @param string $email Email cần xác thực
 * @return bool True nếu email hợp lệ, ngược lại là False
 */
function validateAdminEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Xử lý các hành động dựa trên request
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    // Xử lý chọn tab
    if (isset($_GET['tab'])) {
        $_SESSION['active_admin_tab'] = $_GET['tab'];
    }
    
    // Xử lý lọc
    if (isset($_GET['filter']) || isset($_POST['filter'])) {
        $filterValue = isset($_GET['filter']) ? $_GET['filter'] : $_POST['filter'];
        $_SESSION['admin_filter'] = $filterValue;
    }
    
    // Xử lý tìm kiếm
    if (isset($_GET['search']) || isset($_POST['search'])) {
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : $_POST['search'];
        $_SESSION['admin_search'] = $searchTerm;
    }
}
?> 