<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $phone = login_verify();
    $orderid = htmlspecialchars($_GET['orderid']);

    $db = connect_book_sc();

    #修改订单状态
    $query =
        'UPDATE orders
        SET status = 1
        WHERE orderid = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$phone]);
    $stmt->closeCursor();

    $response = create_response();
} catch (\PDOException $pdo_th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
