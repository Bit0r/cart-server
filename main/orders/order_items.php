<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    session_start();
    if (empty($_SESSION['customer_phone']) && empty($_SESSION['admin_username'])) {
        throw new Exception("请先登录", 1);
    }

    $orderid = htmlspecialchars($_GET['orderid']);

    $db = connect_book_sc();

    $query =
        'SELECT
            books.isbn,
            title,
            item_price price,
            quantity,
            item_price * quantity total
        FROM
            order_items
            JOIN books USING(isbn)
        WHERE
            orderid = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$orderid]);

    $response = create_response();
    $response['message'] = $stmt->fetchAll();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
