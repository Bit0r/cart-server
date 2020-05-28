<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $phone = login_verify();

    $address_name = htmlspecialchars($_GET['address_name']);

    $db = connect_book_sc();
    $db->beginTransaction();

    #获取总价
    $query =
        'SELECT SUM(price*quantity)
        FROM cart JOIN books USING(isbn)
        WHERE phone = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$phone]);
    $total = $stmt->fetchColumn();
    $stmt->closeCursor();

    #插入订单
    $markers = create_markers(3);
    $query =
        "INSERT orders(phone, address_name, total)
        VALUES $markers";
    $stmt = $db->prepare($query);
    $stmt->execute([$phone, $address_name, $total]);

    #获取订单id
    $orderid = $db->lastInsertId('orderid');
    $orderid = intval($orderid);
    $stmt->closeCursor();

    #获取购物车行数
    $query =
        'SELECT COUNT(*)
        FROM cart
        WHERE phone = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$phone]);
    $count_books = $stmt->fetchColumn();
    $stmt->closeCursor();

    #插入订单项
    $query =
        "INSERT order_items(orderid, isbn, item_price, quantity)
        SELECT $orderid, books.isbn, price, quantity
        FROM cart JOIN books USING(isbn)";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->closeCursor();

    #清空购物车
    $query =
        'DELETE FROM cart
        WHERE phone = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$phone]);
    $stmt->closeCursor();

    $db->commit();
    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
