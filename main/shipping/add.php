<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    $shipping = json_input();
    $phone = login_verify();

    $db = connect_book_sc();
    $markers = create_markers(7, count($shipping));
    $query =
        "REPLACE shipping(phone, address_name, province, city, county, township, detail)
        VALUES $markers";
    $stmt = $db->prepare($query);
    $stmt->execute(create_params($shipping, $phone));

    $response = create_response();
} catch (\PDOException $th) {
    $response = create_response($th);
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}

function create_params(array $shipping, string $phone)
{
    $params = [];
    foreach ($shipping as $address) {
        $params[] = $phone;
        $params[] = $address['address_name'];
        $params[] = $address['province'];
        $params[] = $address['city'];
        $params[] = $address['county'];
        $params[] = $address['township'];
        $params[] = $address['detail'];
    }
    return $params;
}
