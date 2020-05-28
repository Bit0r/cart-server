<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $phone = login_verify();

    $isbn = htmlspecialchars($_GET['isbn']);

    $db = connect_book_sc();

    $db->beginTransaction();

    $query =
        'SELECT COUNT(*)
        FROM cart
        WHERE phone = ? AND isbn = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$phone, $isbn]);
    $count = $stmt->fetchColumn();
    $stmt->closeCursor();

    if ($count) {
        $query =
            'UPDATE cart
            SET quantity = quantity + 1
            WHERE phone =? AND isbn = ?';
    } else {
        $query =
            'INSERT cart(phone, isbn, quantity)
            VALUES (?, ?, 1)';
    }
    $stmt = $db->prepare($query);
    $stmt->execute([$phone, $isbn]);

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

function create_params(string $phone, array $books)
{
    $params = [];
    foreach ($books as $book) {
        $params[] = $phone;
        $params[] = $book['isbn'];
        $params[] = $book['quantity'];
    }
    return $params;
}
