<?php
require_once '../../lib/common.php';

function connect_book_sc()
{
    return connect_mysql('book_sc', 'book_sc', '%3e8Um0_');
}

function login_verify()
{
    session_start();
    $phone = $_SESSION['customer_phone'];
    if (empty($phone)) {
        throw new Exception("请先登录", 1);
    }
    return $phone;
}

function admin_verify()
{
    session_start();
    $admin = $_SESSION['admin_username'];
    if (empty($admin)) {
        throw new Exception("非管理员无法访问", 1);
    }
    return $admin;
}
