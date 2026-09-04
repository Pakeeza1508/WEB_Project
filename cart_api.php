<?php

include "db.php";
include "auth_check.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Method not allowed"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$cart_id = isset($data["cart_id"]) ? (int)$data["cart_id"] : 0;
$qty = isset($data["qty"]) ? (int)$data["qty"] : 0;
$userid = (int)$_SESSION["uid"];

if ($cart_id <= 0 || $qty <= 0) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid cart item or quantity"
    ]);
    exit;
}

$stmt = $conn->prepare(
    "UPDATE cart
     SET qty = ?
     WHERE cart_id = ? AND userid = ?"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Unable to update cart"]);
    exit;
}

$stmt->bind_param("iii", $qty, $cart_id, $userid);

if (!$stmt->execute()) {
    http_response_code(500);

    echo json_encode(["success" => false, "message" => "Unable to update cart"]);
    exit;
}

/* Fetch updated item */
$item = $conn->prepare(
    "SELECT
        c.cart_id,
        c.qty,
        COALESCE(p.price, sp.price) AS price
     FROM cart c
     LEFT JOIN products p
        ON c.spid = p.pid
        AND c.item_type = 'featured'
     LEFT JOIN shop_products sp
        ON c.spid = sp.spid
        AND c.item_type = 'catalog'
     WHERE c.cart_id = ?
       AND c.userid = ?"
);

$item->bind_param("ii", $cart_id, $userid);
$item->execute();

$row = $item->get_result()->fetch_assoc();

if (!$row) {
    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "Cart item not found"
    ]);
    exit;
}

$subtotal = (float)$row["price"] * (int)$row["qty"];

/* Recalculate grand total */
$totalQuery = $conn->prepare(
    "SELECT SUM(
        c.qty * COALESCE(p.price, sp.price)
     ) AS grand_total
     FROM cart c
     LEFT JOIN products p
        ON c.spid = p.pid
        AND c.item_type = 'featured'
     LEFT JOIN shop_products sp
        ON c.spid = sp.spid
        AND c.item_type = 'catalog'
     WHERE c.userid = ?"
);

$totalQuery->bind_param("i", $userid);
$totalQuery->execute();

$totalRow = $totalQuery->get_result()->fetch_assoc();

echo json_encode([
    "success" => true,
    "cart_id" => $cart_id,
    "quantity" => $qty,
    "subtotal" => $subtotal,
    "grand_total" => (float)($totalRow["grand_total"] ?? 0)
]);