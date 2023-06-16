<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');
// شروع یک session
session_start();

// بررسی اینکه کاربر وارد شده است یا خیر
if (!isset($_SESSION['user_id'])) {
  // در صورتی که کاربر وارد نشده باشد، او را به صفحه ورود هدایت کنید
  // کاربر لاگین نکرده است، انتقال به صفحه لاگین با پیام مناسب
  $message = "برای دسترسی به دسته بندی، لازم است وارد شوید.";
  header("Location: ../login?message=" . urlencode($message));
  exit();
}

header("Refresh: 120; url=../login");
// خواندن نام دیتابیس و جدول از فایل
[$dbname, $tbname] = getDBTBName('Table category listing: ');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("location: ../login");
    exit();
  }
}
?>
<!DOCTYPE html>
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
      <div class="col-md-9">
        <div class="panel panel-default">
          <div class="panel-heading">
            <?php
            echo "خوش آمدید به داشبورد" . $_SESSION['user_id'] . "!";
            ?>
          </div>
          <div class="panel-body">
            <?php
            // If the session variable exists, use its value as the counter value
            $counter = $_SESSION['counter'];


            echo '<p id="countdown"></p>
                    <script>
                    var counter = ' . $counter . ';
                    var interval = setInterval(function() {
                        counter--;
                        ' . $_SESSION['counter']-- . ' 
                        document.getElementById("countdown").innerHTML = ":بعد از  " + counter.toString() + " ثانیه وارد صفحه لاگین میشوید";
                        
                        
                        if (counter === 0) {
                            clearInterval(interval);
                        }
                        
                    }, 1000);
                    </script>';
            ?>
            <p>دسته بندی</p>
            <!-- <h1>Welcome to Dashboard</h1> -->
            <!-- action="logout.php" -->
            <form method="POST">
              <input class="btn btn-primary" type="submit" name="logout" value="خروج از حساب">
            </form>

            <br />
            <h1> q </h1>




          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="list-group ">
          <a href="dashboard.php" class="list-group-item ">داشبورد</a>
          <a href="category" class="list-group-item ">دسته بندی ها</a>
          <a href="posts" class="list-group-item ">مطالب</a>
          <a href="comments" class="list-group-item active">کامنت ها </a>
          <a href="setting" class="list-group-item">تنظیمات کاربری</a>
        </div>
      </div>

    </div>
  </div>
</body>

</html>