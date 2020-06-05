<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();

    $status = intval(htmlspecialchars($_GET['status']));

    $db = connect_book_sc();

    if ($status == -1) {
        $query =
            'SELECT COUNT(*)
            FROM orders';
        $stmt = $db->query($query);
    } else {
        $query =
            'SELECT COUNT(*)
            FROM orders
            WHERE status = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$status]);
    }

    $_SESSION['_order_status'] = $status;

    $response = create_response();
    $response['message'] = $stmt->fetchColumn();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
