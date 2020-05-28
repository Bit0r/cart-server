<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $pass = json_input();

    $username = admin_verify();

    $db = connect_book_sc();

    if ($pass['password'] != $pass['verify']) {
        throw new Exception("两次输入密码不一致", 1);
    }

    $query =
        "UPDATE `admin`
        SET passwd_hash = ?
        WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([
        password_hash($pass['password'], PASSWORD_DEFAULT),
        $username
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
