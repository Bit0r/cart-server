<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $isbn = htmlspecialchars($_GET['isbn']);
    if (empty($isbn)) {
        throw new Exception("没有isbn参数", 1);
    }

    $db = connect_book_sc();
    $query =
        'SELECT *
        FROM books
        WHERE isbn=?';
    $stmt = $db->prepare($query);
    $stmt->execute([$isbn]);

    $response = create_response();
    $response['message'] = $stmt->fetch();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
