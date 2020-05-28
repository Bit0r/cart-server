<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $phone = login_verify();

    $db = connect_book_sc();

    $query =
        'SELECT
            books.isbn,
            title,
            author,
            price,
            quantity
        FROM
            cart
            JOIN books USING (isbn)
        WHERE
            phone = ?
        ORDER BY
            books.isbn';
    $stmt = $db->prepare($query);
    $stmt->execute([$phone]);

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
