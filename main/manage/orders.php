<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();
    $page = json_input();

    $db = connect_book_sc();

    if ($_SESSION['_order_status'] == -1) {
        $query =
            'SELECT 
                orderid,
                total,
                stamp,
                phone,
                province,
                city,
                county,
                township,
                detail
            FROM
                orders
                    JOIN
                shipping USING (phone , address_name)
            ORDER BY orderid DESC
            LIMIT ?, ?';
        $stmt = $db->prepare($query);
        $stmt->execute([$page['offset'], $page['rowCount']]);
    } else {
        $query =
            'SELECT 
                orderid,
                total,
                stamp,
                phone,
                province,
                city,
                county,
                township,
                detail
            FROM
                orders
                    JOIN
                shipping USING (phone , address_name)
            WHERE
                status = ?
            ORDER BY orderid DESC
            LIMIT ?, ?';
        $stmt = $db->prepare($query);
        $stmt->execute([
            $_SESSION['_order_status'],
            $page['offset'],
            $page['rowCount']
        ]);
    }

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
