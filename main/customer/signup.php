<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();
    $account = json_input();

    $db = connect_book_sc();

    if ($account['password'] != $account['verify']) {
        throw new Exception("两次输入密码不一致", 1);
    }

    $markers = create_markers(2);
    $query =
        "INSERT customer(phone, passwd_hash)
        VALUES $markers";
    $stmt = $db->prepare($query);
    $stmt->execute([
        $account['phone'],
        password_hash($account['password'], PASSWORD_DEFAULT)
    ]);

    session_start();
    $_SESSION['customer_phone'] = $account['phone'];

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
    if ($pdo_th->getCode() == '23000') {
        $response['message'] = '该手机号已被注册';
    } else {
        $response['message'] = $pdo_th->getMessage();
    }
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
