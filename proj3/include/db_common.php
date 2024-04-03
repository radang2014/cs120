<?php 
    /* Defines single point of truth for connecting to database */ 
    function connect_db() {
        /* Access database */ 
        $server = "localhost";
        $db = "db9r7g90hj6igl";
        $userid = "uckgwggewitgq";
        $pw = "*r#79gHKQMv!g#";

        $conn = new mysqli($server, $userid, $pw);
        if ($conn->connect_error) {
            die("IMPOSSIBLE ERROR: Connection to database failed: " . $conn->connect_error);
        }
        $conn->select_db($db);

        return $conn;
    }

    /* Given product id, return associative array containing info about product */ 
    function get_product_info_by_id($id) {
        $conn = connect_db();
        $sql = "SELECT * FROM products WHERE id = $id";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            die("IMPOSSIBLE ERROR: Invalid ID passed to get_product_info_by_id. Please contact developer.");
        }
        return $result->fetch_assoc();
    }
?>