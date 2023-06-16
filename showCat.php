<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');

[$dbnameC, $tbnameC] = getDBTBName('Table category listing:');
[$dbnameP, $tbnameP] = getDBTBName('Table posts:');



if (isset($_GET['slug'])) {
  // دریافت پارامتر slug از آدرس URL
  $slug = $_GET['slug'];

  // استفاده از پارامتر slug برای جستجوی دسته بندی مرتبط
  //////////////////
  $serch = new ActiveRecord($conn, $tbnameC, $dbnameC, "slug");
  $res = $serch->find($slug);
  /////////////////

  if ($res) {
    // در صورت وجود دسته بندی، نمایش پست‌های مرتبط با آن
    $categoryId = $res['id'];
    $stmt = $conn->prepare("SELECT * FROM $dbnameP.$tbnameP WHERE cat_id = :categoryId ORDER BY publicationDate DESC");
    $stmt->bindParam(':categoryId', $categoryId);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($posts) {
      // نمایش اطلاعات پست‌ها
      foreach ($posts as $post) {
        echo '<div class=" border border-dark rounded" style="border: black 2px solid;">';
        echo '<h3>' . $post['title'] . '</h3>';
        echo '<p>' . $post['content'] . '</p>';
        echo '<p>تاریخ بارگذاری: ' . $post['publicationDate'] . '</p>';
        echo '<td><a href="showPost?slug=' . $post['slug'] . '">لینک</a></td>';
        // دیگر اطلاعات پست
        echo '</div>';
      }
    } else {
      echo "دسته بندی مورد نظر پستی ندارد";
    }
  } else {
    // در صورت عدم وجود دسته بندی، نمایش پیام خطا
    echo "دسته بندی مورد نظر یافت نشد.";
  }
} else {
  echo '<html>
  <head>
  <meta charset="UTF-8">
  <title>پنل کاربری</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    table,
    th,
    td {
      border: 1px solid black;
      border-collapse: collapse;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
  
  <body>';
  echo "<br />
  <h1 >نمایش دسته بندی ها</h1>";
  $stmt = $conn->prepare("SELECT id, name, slug FROM $dbnameC.$tbnameC");
  $stmt->execute();
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo '<table style="width: 100%;" class="table table-striped">';
  echo '<thead><tr><th>نام ها</th><th>لینک</th></tr></thead>';
  echo '<tbody>';
  foreach ($categories as $category) {
    echo '<tr>';
    echo '<td>' . $category['name'] . '</td>';
    echo '<td><a href="showCat?slug=' . $category['slug'] . '">لینک</a></td>';
    echo '</tr>';
  }
  echo '</tbody>';
  echo '</table>';

  echo "</body></html>";
}
