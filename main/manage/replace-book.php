<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();

    $book = json_input();

    $db = connect_book_sc();

    $markers = create_markers(6);
    $query =
        "REPLACE books(isbn, title, author, price, introduction, catid)
        VALUES $markers";
    $stmt = $db->prepare($query);
    $stmt->execute([
        $book['isbn'],
        $book['title'],
        $book['author'],
        $book['price'],
        $book['introduction'],
        $book['catid']
    ]);

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
