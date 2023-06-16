<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');

[$dbnameC, $tbnameC] = getDBTBName('Table category listing:');
[$dbnameP, $tbnameP] = getDBTBName('Table posts:');

$serch = new ActiveRecord($conn, $tbnameC, $dbnameC, "name");
$res = $serch->find("ewe");
$cat_id = $res['id'];
echo $cat_id;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['title'] && $_POST['content'] && $_POST['category']) {

    // دریافت اطلاعات ارسال شده از صفحه قبلی
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $titleSlug = generateSeoURL($title);
    // انجام عملیات دلخواه با اطلاعات دریافتی

    $serch = new ActiveRecord($conn, $tbnameC, $dbnameC, "name");
    $res = $serch->find($category);
    $cat_id = $res['id'];
    $qqq = "def";

    $post = array(
      "cat_id" => $cat_id,
      "title" => $title,
      "slug" => $titleSlug,
      "content" => $content,
      "author" => $qqq
    );

    // بررسی وجود پست با عنوان مشابه
    $stmt = $conn->prepare("SELECT id FROM $dbnameP.$tbnameP WHERE slug = :slug");
    $stmt->bindParam(':slug', $titleSlug);
    $stmt->execute();
    $postRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$postRow) {
      // در صورتی که پست با عنوان مشابه وجود نداشته باشد، ایجاد پست جدید
      $record = new ActiveRecord($conn, $tbnameP, $dbnameP, null, $post);
      $record->save();

      echo "پست با موفقیت ایجاد شد!";
    } else {
      // در صورتی که پست با عنوان مشابه وجود داشته باشد، به روز رسانی پست
      $stmt = $conn->prepare("UPDATE $dbnameP.$tbnameP SET content = :content WHERE id = :post_id");
      $stmt->bindParam(':content', $content);
      $stmt->bindParam(':post_id', $postRow['id']);
      $stmt->execute();
      echo "پست با موفقیت به روز رسانی شد!";
    }
  } else if ($_POST['delete_post']) {
    // دریافت شناسه پست از درخواست
    $postId = $_POST['delete_post'];
    // انجام عملیات حذف پست در دیتابیس
    $stmt = $conn->prepare("DELETE FROM $dbnameP.$tbnameP WHERE id = ?");
    $stmt->execute([$postId]);

    // ارسال پاسخ موفقیت‌آمیز به جاوااسکریپت
    echo "success";
    exit();
  } else if ($_POST['logout']) {
    session_start();
    session_unset();
    session_destroy();
    header("location: ../login");
    exit();
  }
}
