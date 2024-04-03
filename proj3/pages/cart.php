<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cart</title>
        <meta http-equiv="description" content="Cart Temporarily Storing Items User Would Like to Purchase" />
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
                <strong>Cart</strong> 
            </div>
            <div class="navigation">
                <a href="thankyou.php">Thank You</a>
            </div>
            <div class="navigation">
                <a href="orders.php">Orders</a> 
            </div>
            <div class="navigation">
                <a href="credits.html">Credits</a> 
            </div>
        </div>

        <div id="header_padding"></div>
        
        <h2>Your Cart</h2>

        <?php 
            /***** Read item to add to cart from post data, if applicable *****/
            include '../include/db_common.php';
            $MAX_CART_SIZE = 5; /* Database cannot support order with >5 unique items */

            /* Read from `products` database and returns array of product ids */
            function get_product_ids() {
                $conn = connect_db();
                $sql = "SELECT id FROM products";
                $result = $conn->query($sql);

                $prod_ids = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($prod_ids, $row["id"]);
                    }
                }
                
                return $prod_ids;
            }

            $prod_ids = get_product_ids();
            
            /* If applicable, add item that was just added to cart to the cart session variable */
            foreach($prod_ids as $prod_id) {
                $qty = $_POST["prod" . $prod_id . "_qty"];
                if (isset($qty)) {
                    if (!isset($_SESSION["cart"])) {
                        /* Initialize cart if this is the first time it is being used */
                        $_SESSION["cart"] = array();
                    }

                    /* If item already in cart, simply add to the quantity. Otherwise, append as new item */
                    $in_cart = false;
                    for($i = 0; $i < count($_SESSION["cart"]); $i++) {
                        if ($_SESSION["cart"][$i]["prodid"] == $prod_id) {
                            $_SESSION["cart"][$i]["qty"] += $qty;
                            $in_cart = true;
                        }
                    }
                    if (!$in_cart and count($_SESSION["cart"]) < $MAX_CART_SIZE) {
                        array_push($_SESSION["cart"], array(
                            "prodid"=>$prod_id,
                            "qty"=>$qty
                        ));
                    } elseif (!$in_cart) {
                        /* Display error message if cart is full */
                        echo "<script>alert('Unfortunately, we do not currently support cart sizes exceeding " .
                             "$MAX_CART_SIZE unique items. If you would like to purchase more than $MAX_CART_SIZE " .
                             "unique items, please split that across multiple orders. Thank you for shopping with " .
                             "us!')</script>";
                    }
                }
            }

            /* If applicable, remove item that was just requested to be removed from the cart */ 
            $idx_to_remove = $_POST["idx_to_remove"];
            if (isset($idx_to_remove)) {
                array_splice($_SESSION["cart"], $idx_to_remove, 1);
            }

            /**** Display all items in cart on the page *****/
            $order_total = 0;
            if (!isset($_SESSION["cart"]) or count($_SESSION["cart"]) == 0) {
                echo "<p>Your cart is currently empty.</p>";
                echo "<input type=\"button\" onclick=\"return_to_products()\" value=\"Continue Shopping\" />";
            } else {
                echo "<p>You currently have the following items in your cart:</p>";
                
                echo "<table border=\"1\" cellpadding=\"4\" cellspacing=\"2\">";
                    echo "<tr>";
                        echo "<th>Item</th>";
                        echo "<th>Qty</th>";
                        echo "<th>Price Per Unit</th>";
                        echo "<th>Total Cost</th>";
                        echo "<th>&nbsp;</th>";
                    echo "</tr>";

                    $item_idx = 0;
                    foreach ($_SESSION["cart"] as $item) {
                        echo "<tr>";

                        $item_info = get_product_info_by_id($item["prodid"]);
                        $total_price = $item_info["price"] * $item["qty"];

                        echo "<td>" . $item_info["name"] . "</td>";
                        echo "<td>" . $item["qty"] . "</td>";
                        echo "<td>$" . $item_info["price"] . "</td>";
                        echo "<td>$" . $total_price . "</td>";
                        $order_total += $total_price;

                        echo "<td>";
                            echo "<form method=\"post\" action=\"cart.php\" name=\"remove$item_idx\">";
                                /* hidden input field specifying index of item on cart to remove */
                                echo "<input type=\"hidden\" name=\"idx_to_remove\" value=\"$item_idx\" />";
                                echo "<input type=\"submit\" value=\"Remove from cart\" />";
                            echo "</form>";
                        echo "</td>";

                        echo "</tr>";

                        $item_idx++;
                    }
                echo "</table> <br />";
                
                /***** Display Order Total and Button for Checking Out *****/
                echo "<p>Your order total is: $" . $order_total . ".</p> <br />";
                echo "<form method=\"post\" action=\"thankyou.php\">";
                    echo "<input type=\"hidden\" name=\"order_total\" value=\"$order_total\" />";
                    echo "<input type=\"button\" onclick=\"return_to_products()\" value=\"Continue Shopping\" />";
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; /* Add space between two buttons */
                    echo "<input type=\"submit\" value=\"Check Out\" />"; 
                echo "</form>";
            }
        ?>

        <script src="../include/navigation.js"></script>
    </body>
</html>