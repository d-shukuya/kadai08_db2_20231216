<?php
// 1. funcs.php を呼び出す
include("./php/funcs.php");

// 2. DB接続
$pdoOrder = db_conn();
$pdoBooks = db_conn();

// 3. データ取得
// 3-1. order
$stmtOrder = $pdoOrder->prepare('SELECT `order` FROM gs_bm_order WHERE `type` = "books"');
$statusOrder = $stmtOrder->execute();
if ($statusOrder == false) {
    sql_error($stmtOrder);
} else {
    $resultOrder = $stmtOrder->fetch(PDO::FETCH_ASSOC);
    $orderAry = json_decode($resultOrder['order'], true);
}

// 3-2. books
$stmtBooks = $pdoBooks->prepare('SELECT * FROM gs_bm_books');
$statusBooks = $stmtBooks->execute();
$view = "";
$resAry = array();
if ($statusBooks == false) {
    sql_error($stmtBooks);
} else {
    while ($resultBooks = $stmtBooks->fetch(PDO::FETCH_ASSOC)) {
        $resAry[$resultBooks['id']] = $resultBooks;
    }
    for ($i=0; $i < count($orderAry); $i++) { 
        $view = createView($resAry[$orderAry[$i]], $view);
    }
}

function createView($result, $view){
    $id12 = str_pad($result['id'], 12, "0", STR_PAD_LEFT);
    $files = glob("./book_cover/$id12.*");
    $coverImgPath = (count($files) > 0) ? $files[0] : './book_cover/sample_cover.png';
    $createdDate = substr($result['created_date'], 0, 10);
    $updateDate = substr($result['update_date'], 0, 10);

    $view .= "<li class='book_item' id='" . h($id12) . "'>";
    $view .=    "<h3>" . h($result['name']) . "</h3>";
    $view .=    "<div class='book_cover'><img src='" . $coverImgPath . "'></div>";
    if (!is_null($result['url']) && $result['url'] != "") {
        $view .= "<a href='" . h($result['url']) . "'>外部リンク</a>";
    };
    $view .=    "<div class='date_info'>";
    $view .=        "<p>登録日： " . h($createdDate) . "</p>";
    $view .=        "<p?>更新日： " . h($updateDate) . "</p>";
    $view .=    "</div>";
    $view .= "</li>";
    return $view;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DogEarApp.</title>
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style_book.css">
</head>

<body>
    <header>
        <img src="./img/logo.png" alt="">
        <h1>DogEarApp.</h1>
    </header>

    <nav>
        <form id="insert_book_form" action="./php/insert_book.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <div id="register_book_box">
                    <h2>書籍の登録</h2>
                    <p id="book_name_label" class="label_fmt">書籍名：</p>
                    <div id="book_name" class="textbox_fmt"><input class="input_textbox" type="text" name="name" required></div>
                    <p id="book_url_label" class="label_fmt">リンク：</p>
                    <div id="book_url" class="textbox_fmt"><input class="input_textbox" type="text" name="url"></div>
                    <div id="book_cover_box"><img id="book_cover_img" src="./img/input_img.png" alt=""></div>
                    <input type="file" id="img_upload" accept="image/*" name="book_cover_img">
                    <input type="hidden" id="books_order" name="books_order" value="<?= h($resultOrder['order']) ?>">
                    <div id="book_register_btn"><input class="button" type="submit" value="登録"></div>
                </div>
            </fieldset>
        </form>
    </nav>

    <main>
        <ul id="book_list"><?= $view ?></ul>
    </main>

    <!-- JSの読み込み -->
    <script src="./js/jquery-2.1.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="./js/script_book.js"></script>
</body>

</html>