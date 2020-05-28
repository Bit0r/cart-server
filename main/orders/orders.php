<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $phone = login_verify();

    $status = htmlspecialchars($_GET['status']);

    $db = connect_book_sc();

    if ($status == -1) {
        $query =
            'SELECT orderid, status, total, stamp, address_name
            FROM orders
            WHERE phone = ?';

        $stmt = $db->prepare($query);
        $stmt->execute([$phone]);
    } else {
        $query =
            'SELECT orderid, total, stamp, address_name
            FROM orders
            WHERE phone = ? AND status = ?';

        $stmt = $db->prepare($query);
        $stmt->execute([$phone, $status]);
    }

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
