<?php

require_once("Connection.php");
require_once("Routes.php");
require_once("Response.php");
new Routes($conn, new Login());


class Login
{
    function get($c, $d)
    {
        echo "get";
    }

    function post($conn, $data)
    {
        $stmt = $conn->prepare("SELECT * FROM Suppliers WHERE SupplierId = ? AND Password = ?;");
        $stmt->bind_param("is", $data["SupplierId"], $data["Password"]);
        $stmt->execute();
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            if (count($row) > 0) {
                session_start();
                $_SESSION['supplier'] = $row[0]['SupplierId'];
                Response::OK(array("SupplierId" => $_SESSION['supplier']));
            } else {
                Response::Unauthorized();
            }
        }
    }
}