<?php
require_once '../../lib/book_sc_fns.php';
session_start();

json_header();

try {
    login_verify();

    $response = create_response();
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    echo json_encode($response);
}
