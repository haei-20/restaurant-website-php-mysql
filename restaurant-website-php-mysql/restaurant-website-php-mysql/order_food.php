<!-- FORM ĐẶT MÓN ĂN -->
<form method="post" id="order_food_form" action="order_food.php">

<?php if ($_SESSION['currentTab'] == 0): ?>
    <!-- Tab chọn món ăn -->
    <div class="order_food_tab">
        <?php include "order_food_tab_0.php"; ?>
    </div>
<?php else: ?>
    <!-- Tab thông tin khách hàng -->
    <div class="client_details_tab">
        <?php include "order_food_tab_1.php"; ?>
    </div>
<?php endif; ?>

<!-- NÚT TIẾP THEO VÀ QUAY LẠI -->