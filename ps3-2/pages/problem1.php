<!DOCTYPE html>
<html>
    <head>
        <title>Problem Set 3-2 Problem 1</title>
        <meta http-equiv="description" content="Problem Set 3-2 Solution Problem 1" />
        <meta http-equiv="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Import "Inter" google fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">

        <!-- CSS -->
        <style type="text/css">
            body {
                font-family: "Inter", serif;
                max-width: 945px;
            }
            h1 {
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Problem 1</h1>

        <?php 
            /* Read in `n' from query string */
            $n = $_GET["n"];

            /* Print times table up until 12 */ 
            $TIMES_TABLE_MAX = 12;
            for ($i = 1; $i <= $TIMES_TABLE_MAX; $i++) {
                $product = $i * $n;
                echo "<p>$i x $n = $product</p>";
            }
        ?>
    </body>
</html>