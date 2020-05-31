<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();

    $catid = htmlspecialchars($_GET['catid']);

    $db = connect_book_sc();

    $markers = create_markers(1);
    $query =
        'DELETE FROM categories
        WHERE catid = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$catid]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
