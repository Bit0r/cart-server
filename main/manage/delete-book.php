<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();

    $isbn = htmlspecialchars($_GET['isbn']);

    $db = connect_book_sc();

    $query =
        'DELETE FROM books
        WHERE isbn = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$isbn]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
