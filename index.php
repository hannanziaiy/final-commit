<?php
// ini_set('display_errors', '1');

// function dd($input)
// {
//   echo "<pre>";
//   var_dump($input);
//   echo "</pre>";
//   die;
// }


// // دریافت مقدار Slug از پارامتر درخواست
// $slug = isset($_GET['slug']) ? $_GET['slug'] : '';

// // بر اساس Slug، انجام عملیات مورد نیاز
// if ($slug === '') {
//   // نمایش لیست مطالب
//   // ...
// } else {
//   // استخراج مطلب بر اساس Slug
//   // ...
// }


// تجزیه URL پارامترها
// دریافت مسیر از پارامتر url
$url = isset($_GET['url']) ? $_GET['url'] : '';

// function chekInput($input)
// {

//   $pieces = explode("/", "showCat/هواشناسی");
//   echo $pieces[0]; // piece1
//   echo $pieces[1]; // piece2
//   $er = "showCat?slug=هواشناسی";
// }

// مسیریابی بر اساس مسیر درخواست شده
switch ($url) {
  case '':
    // صفحه اصلی سایت
    include 'home.php';
    break;
  case 'home':

    include 'home.php';
    break;

  case 'panel/dashboard':
    include 'panel/dashboard.php';
    break;
  case 'panel/category':
    include 'panel/category.php';
    break;
  case 'panel/posts':
    include 'panel/posts.php';
    break;
  case 'panel/comments':
    include 'panel/comments.php';
    break;
  case 'panel/setting':
    include 'panel/setting.php';
    break;
  case 'panel/proc':
    include 'panel/proc.php';
    break;

  case 'showCat':

    include 'showCat.php';
    break;
  case 'showPost':

    include 'showPost.php';
    break;

  case 'login':

    include 'login.php';
    break;

  case 'register':

    include 'register.php';
    break;


  default:
    // صفحه 404 (یافت نشد)
    include 'html/404.html';
    break;
}
