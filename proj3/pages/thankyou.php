<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Thank You</title>
        <meta http-equiv="description" content="Confirmation Page After Order" />
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
                <strong>Thank You</strong>
            </div>
            <div class="navigation">
                <a href="orders.php">Orders</a> 
            </div>
            <div class="navigation">
                <a href="credits.html">Credits</a> 
            </div>
        </div>

        <div id="header_padding"></div>
        
        <h2>Thank You</h2>

        <?php 
            include '../include/db_common.php';

            /* `order_total` would only contain a value if an order was just placed */
            $order_placed = isset($_POST["order_total"]);

            if ($order_placed) {
                /* Error checking on cart size, just in case. This should never happen. */ 
                $MAX_CART_SIZE = 5;
                if (count($_SESSION["cart"]) < 1 or count($_SESSION["cart"]) > $MAX_CART_SIZE) {
                    die("IMPOSSIBLE ERROR: Cart size was not between 1 and $MAX_CART_SIZE. Please contact developer.");
                }

                $order_idx = 1;
                $colnames_to_insert = array();
                $values_to_insert = array();
                foreach ($_SESSION["cart"] as $item) { /* Read items from cart */
                    array_push($colnames_to_insert, "item" . $order_idx . "_id");
                    array_push($colnames_to_insert, "item" . $order_idx . "_qty");

                    array_push($values_to_insert, $item["prodid"]);
                    array_push($values_to_insert, $item["qty"]);

                    $order_idx++;
                }
                /* Add today's date as the order date */ 
                array_push($colnames_to_insert, "date");
                array_push($values_to_insert, "DATE '" . date("Y-m-d") . "'");

                /* Form SQL Query and insert into database */ 
                $sql = "INSERT INTO orders (" . join(", ", $colnames_to_insert) . ") " . 
                        "VALUES (" . join(", ", $values_to_insert) . ")";
                $conn = connect_db();
                $conn->query($sql);

                /* Form thank you message */ 
                $shipping_date = date("m/d/Y", strtotime(date("Y-m-d") . "+ 2 days"));
                echo "<p>Thank you for your order! Your total was $" . $_POST["order_total"] . 
                     ", and we will ship your order on $shipping_date. You should receive " . 
                     "your meal on that same day.</p>";
                
                /* Clear cart */ 
                $_SESSION["cart"] = array();
            } else {
                echo "<p>This page is meant to serve as a confirmation immediately following " . 
                     "the placement of an order. If you are reading this message, that means " . 
                     "that you did not <em>just</em> place an order. Use the buttons below to " . 
                     "shop for an order or view orders you have previously placed.</p>";
            }
        ?>

        <!-- Navigation Buttons -->
        <input type="button" value="Continue Shopping" onclick="return_to_products()" />
        <input type="button" value="View All Orders" onclick="go_to_orders()" />

        <script src="../include/navigation.js"></script>
    </body>
</html>