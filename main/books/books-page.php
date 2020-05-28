<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $page = json_input();

    session_start();
    $catid = $_SESSION['catid'];
    if (empty($catid)) {
        throw new Exception("请先获取图书总数，然后再进行分页", 1);
    }

    $db = connect_book_sc();
    $query =
        'SELECT isbn, author, title, price, introduction
        FROM books
        WHERE catid = ?
        LIMIT ?, ?';
    $stmt = $db->prepare($query);


    $stmt->execute([$_SESSION['catid'], $page['offset'], $page['rowCount']]);

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
