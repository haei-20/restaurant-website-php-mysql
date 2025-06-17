<!-- Tab chọn món ăn -->
<div class="order_food_tab">
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
                                    echo number_format(intval($row['menu_price']), 0, ',', '.') . "000đ";
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
        if (isset($_SESSION['selected_menus']) && !empty($_SESSION['selected_menus'])) {
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
                        echo '<span class="badge badge-primary badge-pill mr-2 item-price" data-menu-id="' . $menuId . '" data-unit-price="' . $menu['menu_price'] . '">' . number_format($itemPrice, 0, ',', '.') . '000đ</span>';
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
            echo number_format($totalPrice, 0, ',', '.') . '000đ';
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