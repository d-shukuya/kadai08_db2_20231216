<?php
//XSS対応（ echoする場所で使用 ）
function h($val)
{
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

//DB接続関数
function db_conn()
{
    try {
        $db_name = 'gs_bm_db2';
        $db_id   = 'root';
        $db_pw   = '';
        $db_host = 'localhost';
        return new PDO("mysql:dbname=$db_name;charset=utf8;host=$db_host", $db_id, $db_pw);
    } catch (PDOException $e) {
        exit('DB Connection Error:' . $e->getMessage());
    }
}

//SQLエラー関数
function sql_error($stmt)
{
    $error = $stmt->errorInfo();
    exit('SQLError:' . print_r($error, true));
}

//リダイレクト関数
function redirect($file_name)
{
    header("Location: $file_name");
    exit();
}

// タグ名からbooksテーブルのカラム名を引き当てるテーブル
$tagNameToBooksTableColumn = array(
    'book_name' => 'name',
    'book_url' => 'url',
    'book_memo' => 'content',
);

// タグ名からdog_earテーブルのカラム名を引き当てるテーブル
$tagNameToDogEarTableColumn = array(
    'page_number' => 'page_number',
    'line_number' => 'line_number',
    'dog_ear_memo' => 'content',
);