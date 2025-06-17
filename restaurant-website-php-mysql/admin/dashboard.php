<?php
	//Bắt đầu phiên
    session_start();

    //Đặt tiêu đề trang
    $pageTitle = 'Quản lý';

    //CÁC INCLUDES PHP
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
    {
        // Hiển thị thông báo thành công
        if (isset($_GET['completed'])) {
            $success_message = "Đơn hàng đã được đánh dấu là hoàn thành!";
        }
        
    	include 'Includes/templates/navbar.php';
    	?>

            <script type="text/javascript">
                var vertical_menu = document.getElementById("vertical-menu");
                var current = vertical_menu.getElementsByClassName("active_link");
            if(current.length > 0) {
                    current[0].classList.remove("active_link");
                }
                vertical_menu.getElementsByClassName('dashboard_link')[0].className += " active_link";
            </script>
        
        <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
        <?php endif; ?>

        <!-- Modal Xem Chi Tiết Đơn Hàng -->
        <div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailModalLabel">Chi tiết đơn hàng #<span id="order-id"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Thông tin khách hàng</h6>
                                <p><strong>Tên:</strong> <span id="customer-name"></span></p>
                                <p><strong>Email:</strong> <span id="customer-email"></span></p>
                                <p><strong>Số điện thoại:</strong> <span id="customer-phone"></span></p>
                                <p><strong>Địa chỉ:</strong> <span id="delivery-address"></span></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Thông tin đơn hàng</h6>
                                <p><strong>Thời gian đặt:</strong> <span id="order-time"></span></p>
                                <p><strong>Trạng thái:</strong> <span id="order-status"></span></p>
                                <p id="completion-time-container"><strong>Thời gian hoàn thành:</strong> <span id="completion-time"></span></p>
                                <p id="cancellation-container" style="display: none;">
                                    <strong>Thời gian hủy:</strong> <span id="cancel-time"></span><br>
                                    <strong>Người hủy:</strong> <span id="canceled-by"></span><br>
                                    <strong>Lý do hủy:</strong> <span id="cancel-reason"></span>
                                </p>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6>Chi tiết món ăn</h6>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Món ăn</th>
                                            <th>Số lượng</th>
                                            <th>Đơn giá</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody id="order-items">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-right"><strong>Tổng tiền:</strong></td>
                                            <td id="total-amount"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thêm script xử lý AJAX -->
        <script>
        function showOrderDetail(orderId) {
           
            $.ajax({
                url: 'get_order_detail.php',
                type: 'GET',
                data: { order_id: orderId },
                success: function(response) {
                    const data = JSON.parse(response);
                    
                    // Cập nhật thông tin vào modal
                    $('#order-id').text(data.order_id);
                    $('#customer-name').text(data.client_name);
                    $('#customer-email').text(data.client_email);
                    $('#customer-phone').text(data.client_phone);
                    $('#delivery-address').text(data.delivery_address);
                    $('#order-time').text(data.order_time);
                    
                    // Xử lý trạng thái đơn hàng
                    let statusHtml = '';
                    if (data.canceled == 1) {
                        statusHtml = '<span class="badge badge-danger">Đã hủy</span>';
                        $('#cancellation-container').show();
                        $('#completion-time-container').hide();
                        $('#cancel-time').text(data.cancel_time);
                        $('#canceled-by').text(data.canceled_by);
                        $('#cancel-reason').text(data.cancellation_reason);
                    } else if (data.delivered == 1) {
                        statusHtml = '<span class="badge badge-success">Đã hoàn thành</span>';
                        $('#cancellation-container').hide();
                        $('#completion-time-container').show();
                        $('#completion-time').text(data.delivery_time);
                    } else {
                        statusHtml = '<span class="badge badge-warning">Đang chờ xử lý</span>';
                        $('#cancellation-container').hide();
                        $('#completion-time-container').hide();
                    }
                    $('#order-status').html(statusHtml);
                    
                    // Hiển thị chi tiết món ăn
                    let itemsHtml = '';
                    let totalAmount = 0;
                    data.items.forEach(item => {
                        const itemTotal = item.quantity * item.menu_price;
                        totalAmount += itemTotal;
                        itemsHtml += `
                            <tr>
                                <td>${item.menu_name}</td>
                                <td>${item.quantity}</td>
                                <td>${number_format(item.menu_price)}.000đ</td>
                                <td>${number_format(itemTotal)}.000đ</td>
                            </tr>
                        `;
                    });
                    $('#order-items').html(itemsHtml);
                    $('#total-amount').text(number_format(totalAmount) + '.000đ');
                    
                    // Hiển thị modal
                    $('#orderDetailModal').modal('show');
                }
            });
        }

        function number_format(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }

        function completeOrder(orderId) {
            if(confirm('Bạn có chắc chắn muốn đánh dấu đơn hàng này là hoàn thành?')) {
                $.ajax({
                    url: 'ajax_files/dashboard_ajax.php',
                    type: 'POST',
                    data: {
                        do_: 'Deliver_Order',
                        order_id: orderId
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if(result.status === 'success') {
                            alert(result.message);
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi xử lý yêu cầu!');
                    }
                });
            }
        }

        function cancelOrder(orderId) {
            if(confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                const reason = prompt('Vui lòng nhập lý do hủy đơn hàng:');
                if(reason) {
                    $.ajax({
                        url: 'ajax_files/dashboard_ajax.php',
                        type: 'POST',
                        data: {
                            do_: 'Cancel_Order',
                            order_id: orderId,
                            cancellation_reason: reason
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if(result.status === 'success') {
                                alert(result.message);
                                location.reload();
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi xử lý yêu cầu!');
                        }
                    });
                }
            }
        }
        </script>

        <!-- 4 THẺ TRÊN CÙNG -->
        <div class="row">
            <div class="col-sm-6 col-lg-3">
                <div class="panel panel-green ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-3">
                                <i class="fa fa-users fa-4x"></i>
                            </div>
                            <div class="col-sm-9 text-right">
                                <div class="huge"><span><?php echo countItems("client_id","clients")?></span></div>
                                <div>Tổng số khách hàng</div>
                            </div>
                        </div>
                    </div>
                    <a href="clients.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-3">
                                <i class="fas fa-utensils fa-4x"></i>
                            </div>
                            <div class="col-sm-9 text-right">
                                <div class="huge"><span><?php echo countItems("menu_id","menus")?></span></div>
                                <div>Tổng số món ăn</div>
                            </div>
                        </div>
                    </div>
                    <a href="menus.php">
                        <div class="panel-footer">
                            <span class="pull-left">Xem chi tiết</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            
            <div class=" col-sm-6 col-lg-3">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-3">
                                <i class="far fa-calendar-alt fa-4x"></i>
                            </div>
                            <div class="col-sm-9 text-right">
                                <div class="huge"><span><?php echo countItems("order_id","placed_orders WHERE canceled = 0 AND delivered = 0")?></span></div>
                                <div>Đơn hàng đang chờ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-sm-6 col-lg-3">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-3">
                                <i class="fas fa-pizza-slice fa-4x"></i>
                            </div>
                            <div class="col-sm-9 text-right">
                                <div class="huge"><span><?php echo countItems("order_id","placed_orders")?></span></div>
                                <div>Tổng số đơn hàng</div>
                            </div>
                        </div>
                    </div>
                        </div>
                </div>
            </div>
        </div>

        <!-- START ORDERS TABS -->
        <div class="card" style = "margin: 20px 10px">
            <!-- ORDER SUMMARY -->
            <div class="card-header">
                <h5 class="card-title">Tổng quan đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Đơn hàng mới</h5>
                                <p class="card-text h2">
                                    <?php
                                    $stmt = $con->prepare("SELECT COUNT(*) FROM placed_orders WHERE canceled = 0 AND delivered = 0");
                                    $stmt->execute();
                                    echo $stmt->fetchColumn();
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title">Đã hoàn thành</h5>
                                <p class="card-text h2">
                                    <?php
                                    $stmt = $con->prepare("SELECT COUNT(*) FROM placed_orders WHERE delivered = 1");
                                    $stmt->execute();
                                    echo $stmt->fetchColumn();
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <h5 class="card-title">Đã hủy</h5>
                                <p class="card-text h2">
                                    <?php
                                    $stmt = $con->prepare("SELECT COUNT(*) FROM placed_orders WHERE canceled = 1");
                                    $stmt->execute();
                                    echo $stmt->fetchColumn();
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS BUTTONS -->
        <div class="card-header tab" style="padding: 10px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
            <div class="nav nav-tabs card-header-tabs">
                <a href="dashboard.php?tab=recent_orders" class="nav-item nav-link <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'recent_orders') ? 'active font-weight-bold' : ''; ?>" style="color: <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'recent_orders') ? '#007bff' : '#495057'; ?>; border-bottom: <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'recent_orders') ? '2px solid #007bff' : 'none'; ?>;">
                    <i class="fas fa-clipboard-list mr-2"></i>Đơn hàng gần đây
                </a>
                <a href="dashboard.php?tab=completed_orders" class="nav-item nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'completed_orders') ? 'active font-weight-bold' : ''; ?>" style="color: <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'completed_orders') ? '#28a745' : '#495057'; ?>; border-bottom: <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'completed_orders') ? '2px solid #28a745' : 'none'; ?>; margin-left: 10px;">
                    <i class="fas fa-check-circle mr-2"></i>Đơn đã hoàn thành
                </a>
                <a href="dashboard.php?tab=canceled_orders" class="nav-item nav-link <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'canceled_orders') ? 'active font-weight-bold' : ''; ?>" style="color: <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'canceled_orders') ? '#dc3545' : '#495057'; ?>; border-bottom: <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'canceled_orders') ? '2px solid #dc3545' : 'none'; ?>; margin-left: 10px;">
                    <i class="fas fa-times-circle mr-2"></i>Đơn hàng đã hủy
                </a>
            </div>
            </div>

            <!-- TABS CONTENT -->
            
            <div class='responsive-table'>

                <?php if(isset($_GET['debug'])): ?>
                <div class="alert alert-info">
                    <h4>Thông tin debug</h4>
                    <pre>
                    <?php
                        // Hiển thị cấu trúc đơn hàng đầu tiên để kiểm tra
                        $debug_stmt = $con->prepare("SELECT po.*, c.* FROM placed_orders po, clients c WHERE po.client_id = c.client_id LIMIT 1");
                        $debug_stmt->execute();
                        $debug_order = $debug_stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if($debug_order) {
                            echo "Các trường trong bảng đơn hàng và khách hàng:\n";
                            print_r(array_keys($debug_order));
                            
                            echo "\n\nGiá trị các trường chính:\n";
                            echo "- client_name: " . ($debug_order['client_name'] ?? 'không tồn tại') . "\n";
                            echo "- total_amount: " . ($debug_order['total_amount'] ?? 'không tồn tại') . "\n";
                            echo "- order_id: " . ($debug_order['order_id'] ?? 'không tồn tại') . "\n";
                        } else {
                            echo "Không có đơn hàng nào trong cơ sở dữ liệu.";
                        }
                    ?>
                    </pre>
                </div>
                <?php endif; ?>

                <!-- RECENT ORDERS -->
            <table class="table X-table" id="recent_orders" style="display:<?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'recent_orders') ? 'table' : 'none'; ?>">
                    <thead>
                        <tr>
                            <th>Thời gian tạo đơn hàng</th>
                            <th>Món ăn</th>
                            <th>Tổng tiền</th>
                            <th>Khách hàng</th>
                            <th>Trạng thái</th>
                            <th>Ghi chú</th>
                            <th>Phương thức thanh toán</th>
                            <th>Quản lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt = $con->prepare("SELECT po.*, c.* 
                                                FROM placed_orders po , clients c
                                                where 
                                                    po.client_id = c.client_id
                                                and canceled = 0
                                                and delivered = 0
                                                order by order_time;
                                                ");
                            $stmt->execute();
                            $placed_orders = $stmt->fetchAll();
                            $count = $stmt->rowCount();
                            
                            if($count == 0)
                            {
                                echo "<tr>";
                                    echo "<td colspan='8' style='text-align:center;'>";
                                        echo "Chưa có đơn hàng nào";
                                    echo "</td>";
                                echo "</tr>";
                            }
                            else
                            {
                                foreach($placed_orders as $order)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $order['order_time'];
                                        echo "</td>";
                                        echo "<td>";
                                            $stmtMenus = $con->prepare("SELECT menu_name, quantity, menu_price
                                                    from menus m, in_order in_o
                                                    where m.menu_id = in_o.menu_id
                                                    and order_id = ?");
                                            $stmtMenus->execute(array($order['order_id']));
                                            $menus = $stmtMenus->fetchAll();

                                            $total_price = 0;

                                            foreach($menus as $menu)
                                            {
                                                echo "<span style = 'display:block'>".$menu['menu_name']." <strong>x".$menu['quantity']."</strong></span>";
                                                $total_price += ($menu['menu_price']*$menu['quantity']);
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            if (isset($order['total_amount']) && $order['total_amount'] > 0) {
                                                echo number_format($order['total_amount'], 0, ',', '.') . ".000đ";
                                            } else {
                                                echo number_format($total_price, 0, ',', '.') . ".000đ";
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            echo $order['client_name'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<span class='badge badge-warning'>Đang chờ xử lý</span>";
                                        echo "</td>";
                                        echo "<td>";
                                            echo $order['notes'] ?? 'Không có';
                                        echo "</td>";
                                        echo "<td>";
                                            echo $order['payment_method'] ?? 'Tiền mặt';
                                        echo "</td>";
                                        echo "<td>";
                                            ?>
                                            <ul class="list-inline m-0">
                                                <!-- Nút xem chi tiết -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Xem chi tiết">
                                                    <button class="btn btn-info btn-sm rounded-0" onclick="showOrderDetail(<?php echo $order['order_id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </li>
                                                <!-- Deliver Order BUTTON -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Hoàn thành đơn hàng">
                                                    <a href="dashboard.php?action=complete&order_id=<?php echo $order['order_id']; ?>" 
                                                       class="btn btn-success btn-sm rounded-0" 
                                                       onclick="completeOrder(<?php echo $order['order_id']; ?>)">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </li>
                                                <!-- CANCEL BUTTON -->
                                                <li class="list-inline-item" data-toggle="tooltip" title="Hủy đơn hàng">
                                                    <a href="dashboard.php?action=cancel_form&order_id=<?php echo $order['order_id']; ?>" 
                                                       class="btn btn-danger btn-sm rounded-0"
                                                       onclick="cancelOrder(<?php echo $order['order_id']; ?>)">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                            <?php
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>

                <!-- COMPLETED ORDERS -->
            <table class="table X-table" id="completed_orders" style="display:<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'completed_orders') ? 'table' : 'none'; ?>">
                    <thead>
                        <tr>
                            <th>Thời gian tạo đơn hàng</th>
                            <th>Thời gian hoàn thành</th>
                            <th>Món ăn</th>
                            <th>Tổng tiền</th>
                            <th>Khách hàng</th>
                            <th>Phương thức thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt = $con->prepare("SELECT po.*, c.* 
                                                FROM placed_orders po , clients c
                                                where 
                                                    po.client_id = c.client_id
                                                    and delivered = 1
                                                    and canceled = 0
                                                order by delivery_time DESC;
                                                ");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            $count = $stmt->rowCount();

                            if($count == 0)
                            {
                                echo "<tr>";
                                    echo "<td colspan='6' style='text-align:center;'>";
                                        echo "Chưa có đơn hàng hoàn thành nào";
                                    echo "</td>";
                                echo "</tr>";
                            }
                            else
                            {
                                foreach($rows as $row)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $row['order_time'];
                                        echo "</td>";
                                        echo "<td>";
                                            if($row['delivery_time']) {
                                                echo date('d/m/Y H:i', strtotime($row['delivery_time']));
                                            } else {
                                                echo 'không có thông tin ';
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            $stmtMenus = $con->prepare("SELECT menu_name, quantity, menu_price
                                                    from menus m, in_order in_o
                                                    where m.menu_id = in_o.menu_id
                                                    and order_id = ?");
                                            $stmtMenus->execute(array($row['order_id']));
                                            $menus = $stmtMenus->fetchAll();
                                            
                                            $total_price = 0;
                                            foreach($menus as $menu)
                                            {
                                                echo "<span style = 'display:block'>".$menu['menu_name']." <strong>x".$menu['quantity']."</strong></span>";
                                                $total_price += ($menu['menu_price']*$menu['quantity']);
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            // Hiển thị tổng tiền từ trường total_amount nếu có
                                            if (isset($row['total_amount']) && $row['total_amount'] > 0) {
                                                echo number_format($row['total_amount'], 0, ',', '.') . ".000đ";
                                            } else {
                                                // Tính toán lại nếu không có giá trị lưu sẵn
                                                $stmtMenus = $con->prepare("SELECT menu_price, quantity
                                                        from menus m, in_order in_o
                                                        where m.menu_id = in_o.menu_id
                                                        and order_id = ?");
                                                $stmtMenus->execute(array($row['order_id']));
                                                $menus = $stmtMenus->fetchAll();
                                                
                                                $total_price = 0;
                                                foreach($menus as $menu)
                                                {
                                                    $total_price += ($menu['menu_price']*$menu['quantity']);
                                                }
                                                echo number_format($total_price, 0, ',', '.') . ".000đ";
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row['client_name'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row['payment_method'] ?? 'Tiền mặt';
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<button class='btn btn-info btn-sm' onclick='showOrderDetail(".$row['order_id'].")'>";
                                            echo "<i class='fas fa-eye'></i> Xem chi tiết";
                                            echo "</button>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>

                <!-- CANCELED ORDERS -->
            <table class="table X-table" id="canceled_orders" style="display:<?php echo (isset($_GET['tab']) && $_GET['tab'] == 'canceled_orders') ? 'table' : 'none'; ?>">
                    <thead>
                        <tr>
                            <th>Thời gian tạo đơn hàng</th>
                            <th>Thời gian hủy</th>
                            <th>Món ăn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Người hủy</th>
                            <th>Lý do hủy bỏ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $stmt = $con->prepare("SELECT po.*, c.* 
                                                FROM placed_orders po , clients c
                                                where 
                                                    po.client_id = c.client_id
                                                and 
                                                    canceled = 1
                                                order by cancel_time DESC;
                                                ");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            $count = $stmt->rowCount();

                            if($count == 0)
                            {
                                echo "<tr>";
                                    echo "<td colspan='6' style='text-align:center;'>";
                                        echo "Chưa có đơn hàng bị hủy nào";
                                    echo "</td>";
                                echo "</tr>";
                            }
                            else
                            {
                                foreach($rows as $row)
                                {
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $row['order_time'];
                                        echo "</td>";
                                        echo "<td>";
                                            if($row['cancel_time']) {
                                                echo date('d/m/Y H:i', strtotime($row['cancel_time']));
                                            } else {
                                                echo 'Không có thông tin';
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            $stmtMenus = $con->prepare("SELECT menu_name, quantity, menu_price
                                                    from menus m, in_order in_o
                                                    where m.menu_id = in_o.menu_id
                                                    and order_id = ?");
                                            $stmtMenus->execute(array($row['order_id']));
                                            $menus = $stmtMenus->fetchAll();
                                            
                                            $total_price = 0;
                                            foreach($menus as $menu)
                                            {
                                                echo "<span style = 'display:block'>".$menu['menu_name']." <strong>x".$menu['quantity']."</strong></span>";
                                                $total_price += ($menu['menu_price']*$menu['quantity']);
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row['client_name'];
                                        echo "</td>";
                                        echo "<td>";
                                            // Hiển thị tổng tiền từ trường total_amount nếu có
                                            if (isset($row['total_amount']) && $row['total_amount'] > 0) {
                                                echo number_format($row['total_amount'], 0, ',', '.') . ".000đ";
                                            } else {
                                                // Tính toán lại nếu không có giá trị lưu sẵn
                                                $stmtMenus = $con->prepare("SELECT menu_price, quantity
                                                        from menus m, in_order in_o
                                                        where m.menu_id = in_o.menu_id
                                                        and order_id = ?");
                                                $stmtMenus->execute(array($row['order_id']));
                                                $menus = $stmtMenus->fetchAll();
                                                
                                                $total_price = 0;
                                                foreach($menus as $menu)
                                                {
                                                    $total_price += ($menu['menu_price']*$menu['quantity']);
                                                }
                                                echo number_format($total_price, 0, ',', '.') . ".000đ";
                                            }
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row['canceled_by'] ?? 'Khách hàng';
                                        echo "</td>";
                                        echo "<td>";
                                            echo $row['cancellation_reason'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<button class='btn btn-info btn-sm' onclick='showOrderDetail(".$row['order_id'].")'>";
                                            echo "<i class='fas fa-eye'></i> Xem chi tiết";
                                            echo "</button>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card" style="margin: 20px 10px">
            <div class="card-header">
                <h5 class="card-title">Thống kê doanh thu</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- TOP SELLING ITEMS -->
        <div class="card" style="margin: 20px 10px">
            <div class="card-header">
                <h5 class="card-title">Thống kê số lượng món đã bán</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Món ăn</th>
                                <th>Số lượng đã bán</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $con->prepare("
                                SELECT m.menu_name, SUM(io.quantity) as total_quantity, 
                                        SUM(io.quantity * m.menu_price) as total_revenue
                                FROM menus m
                                JOIN in_order io ON m.menu_id = io.menu_id
                                JOIN placed_orders po ON io.order_id = po.order_id
                                WHERE po.canceled = 0 
                                AND po.delivered = 1
                                GROUP BY m.menu_id
                                ORDER BY total_revenue DESC
                            ");
                            $stmt->execute();
                            $top_items = $stmt->fetchAll();
                            
                            $total_revenue = 0;
                            $total_quantity = 0;

                            foreach($top_items as $item) {
                                echo "<tr>";
                                echo "<td>" . $item['menu_name'] . "</td>";
                                echo "<td>" . $item['total_quantity'] . "</td>";
                                echo "<td>" . number_format($item['total_revenue'], 0, ',', '.') . ".000đ</td>";
                                echo "</tr>";
                                
                                $total_revenue += $item['total_revenue'];
                                $total_quantity += $item['total_quantity'];
                            }
                            
                            // Thêm hàng tổng
                            echo "<tr class='table-info font-weight-bold'>";
                            echo "<td>Tổng cộng</td>";
                            echo "<td>" . $total_quantity . "</td>";
                            echo "<td>" . number_format($total_revenue, 0, ',', '.') . ".000đ</td>";
                            echo "</tr>";
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
    </div>
    <!-- END ORDERS TABS -->

<!-- REVENUE CHART -->

<?php
	include 'Includes/templates/footer.php';
}
else
{
	header("Location: index.php");
	exit();
}
?>

    <!-- Thêm Chart.js cho biểu đồ thống kê -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [
            <?php
            // Tạo mảng nhãn cho 7 ngày gần nhất đến hôm nay
            $labels = array();
            for ($i = 6; $i >= 0; $i--) {
                $date = date('d/m', strtotime("-$i days"));
                $labels[] = "'".$date."'";
            }
            echo implode(',', $labels);
            ?>
        ],
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: [
                <?php
                $stmt = $con->prepare("
                    SELECT DATE(order_time) as order_date, 
                           SUM(io.quantity * m.menu_price) as daily_revenue
                    FROM placed_orders po
                    JOIN in_order io ON po.order_id = io.order_id
                    JOIN menus m ON io.menu_id = m.menu_id
                    WHERE po.canceled = 0
                    AND po.delivered = 1
                    AND order_time >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                    AND order_time <= CURDATE()
                    GROUP BY DATE(order_time)
                    ORDER BY order_date
                ");
                $stmt->execute();
                $revenue_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Khởi tạo mảng doanh thu với 7 ngày, mặc định là 0
                $revenue_array = array_fill(0, 7, 0);
                
                // Điền dữ liệu doanh thu vào mảng
                foreach($revenue_data as $data) {
                    $days_ago = (strtotime('today') - strtotime($data['order_date'])) / (60 * 60 * 24);
                    $index = 6 - $days_ago;
                    if ($index >= 0 && $index < 7) {
                        $revenue_array[$index] = $data['daily_revenue'];
                    }
                }
                echo implode(',', $revenue_array);
                ?>
            ],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + '.000đ';
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let value = context.raw;
                        return 'Doanh thu: ' + value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});
</script>