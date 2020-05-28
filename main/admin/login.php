<?php
require_once '../../lib/book_sc_fns.php';
json_header();

$admin = json_input();

try {
    $db = connect_book_sc();

    $query =
        'SELECT passwd_hash
        FROM `admin`
        WHERE username = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$admin['username']]);

    if ($stmt->rowCount() == 0) {
        throw new Exception("账号错误", 1);
    } elseif (!password_verify($admin['password'], $stmt->fetchColumn())) {
        throw new Exception("密码错误", 1);
    } else {
        session_start();
        $_SESSION['admin_username'] = $admin['username'];
        $response = create_response();
    }
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
