<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $books = json_input();

    $phone = login_verify();

    $db = connect_book_sc();

    $db->beginTransaction();

    foreach ($books as $book) {
        if ($book['quantity']) {
            $query =
                'UPDATE cart
                SET quantity = ?
                WHERE phone =? AND isbn = ?';
            $stmt = $db->prepare($query);
            $stmt->execute([$book['quantity'], $phone, $book['isbn']]);
        } else {
            $query =
                'DELETE FROM cart
            WHERE phone =? AND isbn = ?';
            $stmt = $db->prepare($query);
            $stmt->execute([$phone, $book['isbn']]);
        }
    }

    $db->commit();
    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
