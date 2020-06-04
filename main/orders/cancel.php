<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $phone = login_verify();

    $orderid = htmlspecialchars($_GET['orderid']);

    $db = connect_book_sc();

    $query =
        'UPDATE orders
        SET status = 3
        WHERE orderid = ? AND status != 3';
    $stmt = $db->prepare($query);
    $stmt->execute([$orderid]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
