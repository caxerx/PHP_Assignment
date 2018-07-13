<?php

require_once("Connection.php");
require_once("Routes.php");
require_once("Response.php");
new Routes($conn, new SupplierLogin());


class SupplierLogin
{
    function get($conn)
    {
        session_start();
        if (isset($_SESSION['supplier'])) {
            Response::OK(array("SupplierId" => $_SESSION['supplier']));
        } else {
            Response::Fail("User is not logged in");
        }
    }

    function post($conn, $data)
    {
        session_start();
        session_destroy();
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
                Response::Fail("Can't match any user record in database");
            }
        }
    }
}