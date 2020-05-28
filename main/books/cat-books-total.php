<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $catid = intval(htmlspecialchars($_GET['catid']));
    if (empty($catid)) {
        throw new Exception("没有catid参数", 1);
    }

    $db = connect_book_sc();
    $query =
        'SELECT COUNT(*)
        FROM books
        WHERE catid = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$catid]);

    session_start();
    $_SESSION['catid'] = $catid;

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
