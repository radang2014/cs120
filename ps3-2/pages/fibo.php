<!DOCTYPE html>
<html>
    <head>
        <title>Problem Set 3-2 Problem 3</title>
        <meta http-equiv="description" content="Problem Set 3-2 Solution Problem 3" />
        <meta http-equiv="keywords" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
    </head>
    <body>
        <?php 
            /* 
             * Returns `n` value from query string and outputs JSON containing that value 
             * and the n-length fibonacci sequence 
             */

            /* Retrieve `n` from query string */ 
            $n = intval($_GET["n"]);

            /* Generate fibonacci sequence, using a bottom-up dynamic programming approach */
            $fib = array();
            
            /* Create associative array containing both length and the fibonacci sequence */
            $result = array("length"=>$n, "fibSequence"=>$fib);
            if ($n < 0) {
                /* Fibonacci sequence is not defined for numbers less than 0 */
                array_push($result["fibSequence"], "undefined");
            }
            if ($n >= 1) {
                /* First seed of Fibonacci sequence */
                array_push($result["fibSequence"], 0);
            }
            if ($n >= 2) {
                /* Second seed of Fibonacci sequence */
                array_push($result["fibSequence"], 1);
            }
            for ($i = 2; $i < $n; $i++) {
                $fib = $result["fibSequence"];
                $new_num = $fib[$i - 2] + $fib[$i - 1];
                array_push($result["fibSequence"], $new_num);
            }

            /* Output JSON formatted array */ 
            echo json_encode($result);
        ?>
    </body>
</html>