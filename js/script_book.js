// ソートテーブルのセット
$(function () {
  $("#book_list").sortable();
});

// イベント
$("#book_list").on("click", ".book_item", function () {
  const bookId = $(this).attr("id");
  window.location.href = `./php/book_detail.php?book_id=${bookId}`;
});

$("#book_cover_box").on("click", "#book_cover_img", function () {
  $("#img_upload").click();
});

$("#img_upload").on("change", function (e) {
  const reader = new FileReader();
  reader.onload = function (e) {
    $("#book_cover_img").attr("src", e.target.result);
  };
  reader.readAsDataURL(e.target.files[0]);
});

$("#book_list").sortable({
  stop: function (event, ui) {
    let ary = $(this).sortable("toArray");
    let order = [];
    ary.forEach(ele => {
      order.push(parseInt(ele, 10));
    });

    let orderJSON = JSON.stringify(order);
    $("#books_order").val(orderJSON);
    postUpdateBooksOrder(orderJSON);
  },
});

// 関数
function postUpdateBooksOrder(order) {
  $.ajax({
    url: "./php/update_order.php",
    type: "post",
    data: {
      type: "books",
      book_id: "NULL",
      order: order,
    },
    success: function (response) {
      console.log(response);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log("Error: " + textStatus + ": " + errorThrown);
    },
  });
}
