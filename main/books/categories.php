<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $db = connect_book_sc();
    $query =
        'SELECT *
        FROM categories';
    $stmt = $db->prepare($query);
    $stmt->execute();

    $response = create_response();
    $response['message'] = $stmt->fetchAll();
} catch (\PDOException $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
