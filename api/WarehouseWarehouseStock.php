<?php
require_once("Response.php");
session_start();
if (!isset($_SESSION['warehouse'])) {
    Response::Unauthorized();
}

require_once("Connection.php");
require_once("Routes.php");

new Routes($conn, new WarehouseWarehouseStock());


class WarehouseWarehouseStock
{
    function get($conn)
    {
        $stmt = $conn->prepare("SELECT WarehouseStockId, Stock.Name AS StockName, Amount FROM `WarehouseStock` LEFT JOIN Stock ON WarehouseStock.StockId = Stock.StockId");
        $stmt->execute();
        if ($rs = $stmt->get_result()) {
            $row = $rs->fetch_all(MYSQLI_ASSOC);
            Response::OK($row);
        } else {
            Response::OK([]);
        }
    }

    function post($conn, $data)
    {
        isset($data["StockId"]) or Response::RequireFieldEmpty();
        isset($data["Amount"]) or Response::RequireFieldEmpty();

        $stmt = $conn->prepare("SELECT * FROM `WarehouseStock` WHERE `StockId` = ?;");
        $stmt->bind_param("i", $data["StockId"]);
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs && count($rs->fetch_all(MYSQLI_ASSOC)) > 0) {
            Response::Fail("Already contains a Warehouse Stock with Stock Id " . $data['StockId']);
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `WarehouseStock` (`WarehouseStockId`, `WarehouseStaffId`, `StockId`, `Amount`) VALUES (0, ?, ?, ?);");
            $stmt->bind_param("iii", $_SESSION['warehouse'], $data["StockId"], $data["Amount"]);
            $stmt->execute();
            $stmt->close();
            Response::OK($data);
        }

    }

    function patch($conn, $data)
    {
        //UPDATE `WarehouseStock` SET `Amount` = '442' WHERE `WarehouseStock`.`WarehouseStockId` = 1;

        isset($data["WarehouseStockId"]) or Response::RequireFieldEmpty();
        isset($data["Amount"]) or Response::RequireFieldEmpty();

        $stmt = $conn->prepare("UPDATE `WarehouseStock` SET `Amount` = ? WHERE `WarehouseStock`.`WarehouseStockId` = ?;");
        $stmt->bind_param("ii", $data["Amount"], $data["WarehouseStockId"]);
        $stmt->execute();
        $stmt->close();
        Response::OK($data);
    }

    function delete($conn, $data)
    {
        isset($data["WarehouseStockId"]) or Response::RequireFieldEmpty();

        $stmt = $conn->prepare("DELETE FROM `WarehouseStock` WHERE `WarehouseStockId` = ?;");
        $stmt->bind_param("i", $data["WarehouseStockId"]);
        $stmt->execute();
        $stmt->close();
        Response::OK($data);

    }

}

/*
 * SELECT Orders.OrderId, Stock.Name, Orders.Amount, Orders.PurchaseDate, Orders.DeliveryDate, Orders.ReceivedDate FROM `Orders` LEFT JOIN SupplierStock ON Orders.SupplierStockId = SupplierStock.SupplierStockId LEFT JOIN Stock ON SupplierStock.StockId = Stock.StockId;
 */

/*
 * UPDATE Orders SET DeliveryDate = ? WHERE OrderId = ?;
 */
?>