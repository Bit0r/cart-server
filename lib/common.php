<?php
function connect_mysql(
    string $dbname,
    string $username,
    string $passwd,
    array $options = [
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
) {
    return new PDO('mysql:host=localhost;dbname=' . $dbname, $username, $passwd, $options);
}

function create_markers(int $question, int $group = 1)
{
    $markers = '(?' . str_repeat(', ?', $question - 1) . ')';
    $markers = $markers . str_repeat(', ' . $markers, $group - 1);
    return $markers;
}

function mbstr_split(string $str, int $split_length = 1)
{

    $arr = [];
    $length = mb_strlen($str);

    for ($i = 0; $i < $length; $i += $split_length) {

        $arr[] = mb_substr($str, $i, $split_length);
    }

    return $arr;
}

function json_input()
{
    //检测到结果为null时必须退出，因为json请求会发报2次。第一次只是确认，第二次才是正文
    //若要调试，则不能在file_get_contents之后进行标记，因为永远无法到达标记位置，理由同上
    return json_decode(file_get_contents("php://input"), true) ?? exit;
}

function json_header()
{
    header('Content-Type: application/json');
}

function create_response(?Exception $th = null)
{
    if (isset($th)) {
        $response['ok'] = false;
        $response['message'] = $th->getMessage();
        $response['code'] = $th->getCode();
    } else {
        $response['ok'] = true;
        $response['code'] = 0;
    }
    return $response;
}
