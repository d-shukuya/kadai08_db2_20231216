<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. 変数定義
$type = $_POST['type'];
$columnName = $tagNameToDogEarTableColumn[$type];

// 3. DB接続
$pdo = db_conn();

// 4．データ登録
// 4-1. SQL文
$stmt = $pdo->prepare(
    "UPDATE
        gs_bm_dog_ear
    SET
        $columnName = :change_val,
        update_date = sysdate()
    WHERE
        id = :dog_ear_id"
);

// 4-2. バインド変数を定義
$param = '';
if ($columnName == 'content') {
    $param = PDO::PARAM_STR;
} else {
    $param = PDO::PARAM_INT;
}

$stmt->bindValue(':change_val', $_POST["change_val"], $param);
$stmt->bindValue(':dog_ear_id', $_POST["dog_ear_id"], PDO::PARAM_INT);

// 4-3. 登録
$status = $stmt->execute();

// 5. 登録後の処理
// 5-1. 失敗時の処理
if ($status == false) {
    sql_error($stmt);
}

// 5-2. 成功時の処理
exit();
