<?php
    ob_start();
	session_start();

	$pageTitle = 'Khách hàng';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        ?>

            <script type="text/javascript">
                var vertical_menu = document.getElementById("vertical-menu");
                var current = vertical_menu.getElementsByClassName("active_link");
                if(current.length > 0) {
                    current[0].classList.remove("active_link");   
                }
                vertical_menu.getElementsByClassName('clients_link')[0].className += " active_link";
            </script>

            <style type="text/css">
                .clients-table {
                    -webkit-box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
                }
                .clients-table th, .clients-table td {
                    text-align: center;
                    vertical-align: middle;
                }
            </style>

        <?php
            $do = 'Manage';

            if($do == "Manage")
            {
                $stmt = $con->prepare("SELECT DISTINCT client_name, client_phone, client_email 
                                     FROM clients 
                                     WHERE client_name IS NOT NULL 
                                     AND client_name != ''
                                     AND client_phone IS NOT NULL 
                                     AND client_phone != ''
                                     GROUP BY client_phone, client_name 
                                     ORDER BY client_name");
                $stmt->execute();
                $clients = $stmt->fetchAll();

            ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $pageTitle; ?>
                    </div>
                    <div class="card-body">
                        <!-- CLIENTS TABLE -->
                        <table class="table table-bordered clients-table">
                            <thead>
                                <tr>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Số lần đặt hàng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($clients as $client)
                                    {
                                        // Get order count for this customer
                                        $stmt = $con->prepare("SELECT COUNT(*) as order_count 
                                                             FROM placed_orders po 
                                                             JOIN clients c ON po.client_id = c.client_id 
                                                             WHERE c.client_phone = ?");
                                        $stmt->execute(array($client['client_phone']));
                                        $order_count = $stmt->fetch()['order_count'];

                                        echo "<tr>";
                                            echo "<td>";
                                                echo $client['client_name'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['client_phone'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo !empty($client['client_email']) ? $client['client_email'] : '-';
                                            echo "</td>";
                                            echo "<td>";
                                                echo $order_count > 0 ? $order_count : '-';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>  
                    </div>
                </div>
            <?php
            }

        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: index.php');
        exit();
    }
?>