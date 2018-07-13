<?php

require_once("Connection.php");
require_once("Routes.php");
require_once("Response.php");
new Routes($conn, new WarehouseStaffLogin());


class WarehouseStaffLogin
{
    function get($conn)
    {
        session_start();
        if (isset($_SESSION['warehouse'])) {
            Response::OK(array("WarehouseStaffId" => $_SESSION['warehouse'], "WarehouseStaffName" => $_SESSION['staffName']));
        } else {
            Response::Fail("User is not logged in");
        }
    }

    function post($conn, $data)
    {
        isset($data["WarehouseStaffId"]) or Response::RequireFieldEmpty();
        isset($data["Password"]) or Response::RequireFieldEmpty();

        session_start();
        session_destroy();
        $stmt = $conn->prepare("SELECT * FROM WarehouseStaff WHERE WarehouseStaffId = ? AND `Password` = ?;");
        $stmt->bind_param("is", $data["WarehouseStaffId"], $data["Password"]);
        $stmt->execute();
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            if (count($row) > 0) {
                session_start();
                $_SESSION['warehouse'] = $row[0]['WarehouseStaffId'];
                $_SESSION['staffName'] = $row[0]['Name'];
                Response::OK(array("WarehouseStaffId" => $_SESSION['warehouse'], "WarehouseStaffName" => $_SESSION['staffName']));
            } else {
                Response::Fail("Can't match any user record in database");
            }
        }
    }
}