<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');

[$dbnameP, $tbnameP] = getDBTBName('Table posts:');



if (isset($_GET['slug'])) {
  // دریافت پارامتر slug از آدرس URL
  $slug = $_GET['slug'];

  // استفاده از پارامتر slug برای جستجوی دسته بندی مرتبط
  //////////////////
  $serch = new ActiveRecord($conn, $tbnameP, $dbnameP, "slug");
  $res = $serch->find($slug);
  /////////////////

  if ($res) {
    if ($res) {
      echo '
      <html>
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
      <body>
      <div class="text-right">
  <div class="row">
    <div class="col-md-1  border border-dark rounded" style="border: black 2px solid; ">
      menu
    </div>
    <div class="col-md-10 border border-dark rounded" style="border: black 2px solid; ">
    ' . $res['content'] . '
    </div>
    <div class="col-md-1 border border-dark rounded" style="border: black 2px solid; ">
      menu
    </div>

  </div>
</div>
      </body>
      </html>
     ';
    } else {
      echo "پست  مورد نظر یافت نشد";
    }
  } else {

    echo "پست  مورد نظر یافت نشد";
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
  <h1 >نمایش پست ها</h1>";
  // کد دریافت اطلاعات پست‌ها از جدول posts بر اساس تاریخ بارگذاری
  $query = "SELECT * FROM $dbnameP.$tbnameP ORDER BY publicationDate DESC";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // نمایش فرم ارسال پست
  // ...

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
  echo "</body></html>";
}
