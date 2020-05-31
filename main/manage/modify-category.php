<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();

    $category = json_input();

    $db = connect_book_sc();

    if (empty($category['catid'])) {
        $markers = create_markers(1);
        $query =
            "INSERT categories(catname)
            VALUES $markers";
        $stmt = $db->prepare($query);
        $stmt->execute([$category['catname']]);
    } else {
        $markers = create_markers(1);
        $query =
            'UPDATE categories
            SET catname = ?
            WHERE catid = ?';
        $stmt = $db->prepare($query);
        $stmt->execute([
            $category['catname'],
            $category['catid']
        ]);
    }

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
