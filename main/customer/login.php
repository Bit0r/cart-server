<?php
require_once '../../lib/book_sc_fns.php';
session_start();
json_header();

$customer = json_input();

try {
    $db = connect_book_sc();

    $query =
        'SELECT passwd_hash
        FROM customer
        WHERE phone = ?';
    $stmt = $db->prepare($query);
    $stmt->execute([$customer['phone']]);

    if ($stmt->rowCount() == 0) {
        throw new Exception("手机号输入错误", 1);
    } elseif (!password_verify($customer['password'], $stmt->fetchColumn())) {
        throw new Exception("密码错误", 2);
    } else {
        $_SESSION['customer_phone'] = $customer['phone'];
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
