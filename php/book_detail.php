<?php
// 1. funcs.php を呼び出す
include("./funcs.php");

// 2. id の定義
$bookId12 = $_GET['book_id'];
$hBookId12 = h($bookId12);

// 3. DB接続&取得
// 3-1. BooksOrder
$pdoOrder = db_conn();
$stmtOrder = $pdoOrder->prepare('SELECT `order` FROM gs_bm_order WHERE `type` = "books"');
$statusOrder = $stmtOrder->execute();
if ($statusOrder == false) {
    sql_error($stmtOrder);
} else {
    $resultOrder = $stmtOrder->fetch(PDO::FETCH_ASSOC);
    $orderJSON = $resultOrder['order'];
    $orderAry = json_decode($orderJSON, true);
}

// 3-1. Books
$pdoBooks = db_conn();
$stmtBooks = $pdoBooks->prepare(
    "SELECT * FROM gs_bm_books WHERE id = $bookId12"
);
$statusBooks = $stmtBooks->execute();
if ($statusBooks == false) {
    sql_error($stmt);
} else {
    $resultBooks = $stmtBooks->fetch(PDO::FETCH_ASSOC);
    $hBookName = h($resultBooks['name']);
    $hBookUrl = h($resultBooks['url']);
    $hBookMemo = h($resultBooks['content']);
    $hCreatedDateBooks = h(substr($resultBooks['created_date'], 0, 10));
    $hUpdateDateBooks = h(substr($resultBooks['update_date'], 0, 10));
}

$files = glob("../book_cover/$bookId12.*");
$bookCoverImg = (count($files) > 0) ? $files[0] : '../book_cover/sample_cover.png';
$hBookCoverImg = h($bookCoverImg);

// 3-2. DogEarOrder
$pdoDogEarOrder = db_conn();
$stmtDogEarOrder = $pdoDogEarOrder->prepare(
    "SELECT `order` FROM gs_bm_order WHERE book_id = $bookId12"
);
$statusDogEarOrder = $stmtDogEarOrder->execute();
if ($statusDogEarOrder == false) {
    sql_error($stmtDogEarOrder);
} else {
    $resultDogEarOrder = $stmtDogEarOrder->fetch(PDO::FETCH_ASSOC);
    $dogEarOrderJSON = $resultDogEarOrder['order'];
    $dogEarOrderAry = json_decode($dogEarOrderJSON, true);
}

// 3-3. DogEar
$pdoDogEar = db_conn();
$stmtDogEar = $pdoDogEar->prepare(
    "SELECT * FROM gs_bm_dog_ear WHERE book_id = $bookId12"
);
$statusDogEar = $stmtDogEar->execute();
$view = "";
$resAry = array();
if ($statusDogEar == false) {
    sql_error($stmt);
} else {
    while ($resultDogEar = $stmtDogEar->fetch(PDO::FETCH_ASSOC)) {
        $resAry[$resultDogEar['id']] = $resultDogEar;
    }
    for ($i = 0; $i < count($dogEarOrderAry); $i++) {
        $view = createView($hBookId12, $resAry[$dogEarOrderAry[$i]], $view, $dogEarOrderJSON);
    }
}

function createView($hBookId, $result, $view, $dogEarOrderJSON)
{
    $hDogEarId12 = h(str_pad($result['id'], 12, "0", STR_PAD_LEFT));
    $hPageNumber = h($result['page_number']);
    $hLineNumber = h($result['line_number']);
    $hDogEarMemo = h($result['content']);
    $hCreatedDateDogEar = h(substr($result['created_date'], 0, 10));
    $hUpdateDateDogEar = h(substr($result['update_date'], 0, 10));
    $hDogEarOrderJSON = h($dogEarOrderJSON);

    $view .= "<li id='$hDogEarId12' class='dog_ear_item'>";
    $view .=    "<div class='left_block'>";
    $view .=        "<div class='input_fmt'>";
    $view .=            "<label>ページ：</label>";
    $view .=            "<input type='text' name='page_number' data-dog_ear_id='$hDogEarId12' value='$hPageNumber'>";
    $view .=        "</div>";
    $view .=        "<div class='input_fmt'>";
    $view .=            "<label>行：</label>";
    $view .=            "<input type='text' name='line_number' data-dog_ear_id='$hDogEarId12' value='$hLineNumber'>";
    $view .=        "</div>";
    $view .=        "<div class='dog_ear_date_info'>";
    $view .=            "<p>登録日： $hCreatedDateDogEar</p>";
    $view .=            "<p>更新日： $hUpdateDateDogEar</p>";
    $view .=        "</div>";
    $view .=    "</div>";
    $view .=    "<div class='dog_ear_memo_box'>";
    $view .=        "<label>メモ：</label>";
    $view .=        "<textarea name='dog_ear_memo' data-dog_ear_id='$hDogEarId12'>$hDogEarMemo</textarea>";
    $view .=    "</div>";
    $view .=    "<div class='delete_dog_ear' data-book_id='$hBookId' data-dog_ear_id='$hDogEarId12' data-dog_ear_order='$hDogEarOrderJSON'>削除</div>";
    $view .= "</li>";
    return $view;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $hBookName ?></title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style_dog_ear.css">
</head>

<body>
    <header>
        <div id="top_btn">DogEarApp.</div>
        <h1 id="book_name" data-book_id=<?= $hBookId12 ?> contenteditable="true"><?= $hBookName ?></h1>
        <div id="book_cover_box">
            <img id='book_cover_img' src="<?= $hBookCoverImg ?>" alt="">
            <input type="file" id="img_upload" accept="image/*" name="book_cover_img" data-book_id=<?= $hBookId12 ?>>
        </div>
        <div id="book_url_box" class="book_url_box_fmt">
            <a id="book_url" href='<?= $hBookUrl ?>'>外部リンク</a>
            <img id="book_url_edit_btn" src="../img/edit.png" alt="">
        </div>
        <div id="book_url_edit_box" class="book_url_box_fmt">
            <input id="book_url_update" type="text" name="book_url_update">
            <div id="book_url_ok" class="book_url_btn" data-book_id=<?= $hBookId12 ?>>○</div>
            <div id="book_url_cancel" class="book_url_btn">×</div>
        </div>
        <h2>書籍のメモ</h2>
        <textarea id="book_memo" name="book_memo" data-book_id=<?= $hBookId12 ?>><?= $hBookMemo ?></textarea>
        <div id='book_date_info'>
            <p>登録日： <?= $hCreatedDateBooks ?></p>
            <p>更新日： <?= $hUpdateDateBooks ?></p>
        </div>
        <div id="book_delete_btn" data-book_id=<?= $hBookId12 ?> data-books_order=<?= h($orderJSON) ?>>本を削除</div>
    </header>

    <nav>
        <form action="./insert_dog_ear.php" method="post">
            <fieldset>
                <input type="hidden" name="book_id" value=<?= $hBookId12 ?>></input>
                <input type="hidden" id="dog_ear_order" name="dog_ear_order" value="<?= h($resultDogEarOrder['order']) ?>">
                <input id="add_dog_ear_btn" type="submit" value="ドッグイヤー追加">
            </fieldset>
        </form>
    </nav>

    <main>
        <ul id="dog_ear_list" data-book_id="<?= $hBookId12 ?>"><?= $view ?></ul>
    </main>

    <!-- JSの読み込み -->
    <script src="../js/jquery-2.1.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="../js/script_dog_ear.js"></script>
</body>

</html>