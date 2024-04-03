<?php 
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Products</title>
        <meta http-equiv="description" content="Restaurant Menu Items Available for Purchase" />
        <meta http-equiv="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Import Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Tangerine">

        <!-- Import JQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css" type="text/css" />
        <style type="text/css">
            div.description {
                display: none; /* hide descriptions by default */
            }
        </style>
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
                <strong>Products / Menu</strong>
            </div>
            <div class="navigation">
                <a href="cart.php">Cart</a> 
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
        
        <p>
            Welcome to Randy's Made-Up Restaurant, where you can purchase food items from our menu! Note 
            that it takes about two days to process your order (so place your order two days in advance 
            of when you expect to enjoy your meal), but you can be sure that your meal will be hot and 
            fresh when it arrives! 
        </p>

        <h2>Menu</h2>

        <?php 
            /***** Read from database and display menu items on page *****/ 
            include '../include/db_common.php';
            $conn = connect_db();

            /* Display appetizers, entrees, sides, and desserts, in that order */
            foreach(array("Appetizer", "Entree", "Side", "Dessert") as $menu_category) {
                echo "<h3>" . $menu_category . "s" . "</h3>";

                display_category($menu_category, $conn);
            }

            /* 
             * Displays `category` of menu on page, with `conn` being the handle connecting to the appropriate 
             * database
             */
            function display_category($category, $conn) {
                $sql = "SELECT * FROM products WHERE item_type = '$category'";
                $result = $conn->query($sql);

                /* Read results of query and display on page */
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        display_item($row);
                    }
                }
            }

            /* Displays menu item `item` on page, where `item` is an associative array containing info about the item */
            function display_item($item) {
                echo "<div>";

                    echo "<img class=\"menu_item\" src=\"" . $item["img_url"] . "\" alt=\"" . $item["img_url"] . "\" />";
                    echo "<div class=\"container\">";

                        echo "<span class=\"menu_item\">" . $item["name"] . "</span> <br />";
                        echo "<span class=\"menu_item\">$" . $item["price"] . "</span> <br />";

                        /* Implement functionality to post to cart.php if "Add to Cart" is pressed */
                        echo "<form method=\"post\" action=\"cart.php\">"; 
                            echo "Quantity: <select name=\"prod" . $item["id"] . "_qty\" id=\"prod" . $item["id"] . "_qty\">";

                                $MAX_QTY = 5;
                                for ($i = 1; $i <= $MAX_QTY; $i++) {
                                    echo "<option value=\"$i\">$i</option>";
                                }
                            
                            echo "</select> <br />";
                            echo "<input type=\"submit\" value=\"Add to Cart\" /> <br />";
                        echo "</form>";

                        echo "<input type=\"button\" value=\"More\" onclick=\"show_description('prod" . 
                             $item["id"] . "_description')\" />";

                    echo "</div>";
                echo "</div>";
                echo "<br />";
                echo "<div class=\"description\" id=\"prod" . $item["id"] . "_description\">" . 
                     $item["description"] . "</div>";
                echo "<br />";
            }
        ?>

        <script>
            /* Shows div containing description with inputted ID */ 
            function show_description(id) {
                $("#" + id).show();
            }
        </script>
    </body>
</html>