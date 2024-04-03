<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Orders</title>
        <meta http-equiv="description" content="Food Orders Placed" />
        <meta http-equiv="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Import Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">

        <!-- Import JQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css" type="text/css" />
        <style type="text/css"></style>
    </head>
    <body>
        <!-- Logo and Title -->
        <div id="title_stripe">
            <div id="logo"></div>
            <h1 id="title">Randy's Made-Up Restaurant</h1>
        </div>

        <!-- Navigation --> 
        <div class="navigation_stripe">
            <div class="navigation">
                <a href="products.php">Products / Menu</a>
            </div>
            <div class="navigation">
                <a href="cart.php">Cart</a>
            </div>
            <div class="navigation">
                <a href="thankyou.php">Thank You</a>
            </div>
            <div class="navigation">
                <strong>Orders</strong> 
            </div>
            <div class="navigation">
                <a href="credits.html">Credits</a> 
            </div>
        </div>

        <div id="header_padding"></div>
        
        <h2>Orders</h2>

        <?php 
            include '../include/db_common.php';

            /* Read info about all orders from database */
            $conn = connect_db();
            $sql = "SELECT * FROM orders ORDER BY id DESC";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                echo "<p>You do not currently have any orders placed.</p>";
            } else {
                echo "<hr />";
                while ($row = $result->fetch_assoc()) {
                    /*** Print summary for each order ***/
                    echo "<h3>Order " . $row["id"] . " Summary</h3>";

                    echo "<p>Date Ordered: " . $row["date"] . "</p>";
                    echo "<p>Order ID: " . $row["id"] . "</p>";

                    /* Print individual items ordered */
                    $order_total = 0;
                    echo "<table border=\"1\" cellpadding=\"4\" cellspacing=\"2\">";
                        echo "<tr>";
                            echo "<th>Item Ordered</th>";
                            echo "<th>Qty</th>";
                            echo "<th>Price Per Unit</th>";
                            echo "<th>Total Cost</th>";
                        echo "</tr>";

                        for ($i = 1; $i <= 5; $i++) { /* Each order has between 1 and 5 unique items */
                            /* If item does not exist (i.e. NULL value), we have finished displaying order items */ 
                            $item_id = $row["item" . $i . "_id"];
                            $qty = $row["item" . $i . "_qty"];
                            if ($item_id == NULL) {
                                break;
                            }

                            /* Display info about this order item */
                            echo "<tr>";

                            $item_info = get_product_info_by_id($item_id);
                            $total_price = $item_info["price"] * $qty;

                            echo "<td>" . $item_info["name"] . "</td>";
                            echo "<td>" . $qty . "</td>";
                            echo "<td>$" . $item_info["price"] . "</td>";
                            echo "<td>$" . $total_price . "</td>";
                            $order_total += $total_price;

                            echo "</tr>";
                        }
                    echo "</table> <br />";

                    echo "<p>Order Total: $$order_total</p>";

                    echo "<hr />";
                }
            }
        ?>

        <input type="button" value="Continue Shopping" onclick="return_to_products()" />

        <script src="../include/navigation.js"></script>
    </body>
</html>