<?php
try {
    require_once '../../lib/book_sc_fns.php';
    json_header();

    admin_verify();

    #处理上传错误
    if ($_FILES['image']['error']) {
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $message = '图片过大';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = '图片超过表单容量';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = '图片上传中断';
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = '没有接收到文件';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = '文件无法写入';
                break;
        }
        throw new Exception($message, $_FILES['image']['error']);
    }

    #防止恶意文件
    if (is_uploaded_file($_FILES['image']['tmp_name'])) {

        $isbn = $_POST['isbn'];

        #校验文件类型
        $image_types = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['image']['type'], $image_types, true)) {
            throw new Exception("文件类型不受支持", 10);
        }

        #移动文件
        $uploaded_file =  "/home/bit0r/文档/cart-client/src/assets/img/$isbn";
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploaded_file)) {
            $response = create_response();
        } else {
            throw new Exception("文件移动失败", 12);
        }
    } else {
        throw new Exception("非法上传文件", 11);
    }
} catch (Exception $th) {
    $response = create_response($th);
} finally {
    $db = null;
    echo json_encode($response);
}
