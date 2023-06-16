<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');

session_start(); // شروع session

checkIfLog();


// چک کردن اتصال
// connCheck($conn=newPdo());
// goDb($conn);
// utf8($conn);
if (isset($_GET['message'])) {
  $message = $_GET['message'];
  // نمایش پیام
  echo $message;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Validate username and password fields
  if (empty($_POST['username']) || empty($_POST['password'])) {
    $error_message = "نام کاربری و رمز عبور را وارد کنید.";
  } else {

    // خواندن نام دیتابیس و جدول از فایل
    $file_content = file_get_contents('database/database.txt');
    $db_data = explode("\n", $file_content);
    $dbname = trim(str_replace('Database name: ', '', $db_data[0]));
    $tbname = trim(str_replace('Table user: ', '', $db_data[1]));
    $db =  $dbname;
    $table  = $tbname;

    ///////////////////////


    $username = $_POST['username'];
    $password = $_POST['password'];
    // Check if user with given username and password exists
    $serch = new ActiveRecord($conn, $tbname, $dbname, "userName");
    $res = $serch->find($username);

    // if ($stmt->rowCount() == 0) {
    if (!$res) {
      $error_message = "نام کاربری یا رمز عبور اشتباه است.";
    } elseif ($res) {
      if (password_verify($password, $res['pass'])) {
        // Redirect user to dashboard page
        header('Location: panel/dashboard');
        session_set_cookie_params(120);
        session_start(); // شروع session

        // انجام عملیات‌های مورد نیاز

        session_regenerate_id(); // تولید یک session id جدید
        $counter = 120;
        // Set the session variable with the default value
        $_SESSION['counter'] = $counter;
        $_SESSION['user_id'] = $username; // اختصاص دادن مقدار به متغیر سشن

        session_write_close(); // بستن session و ذخیره تغییرات


        exit();
      } else {
        $error_message = "نام کاربری یا رمز عبور اشتباه است.";
      }
    }
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>ورود به سایت</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>



  <div class="container mt-5">
    <h2>وارد شدن</h2>
    <form method="POST">
      <div class="form-group">
        <label for="username">نام کاربری:</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">رمز عبور:</label>
        <input type="text" class="form-control" id="password" name="password" required>
      </div>

      <button type="submit" class="btn btn-primary">ورود</button>
      <a href="register" class="btn btn-primary">ثبت نام</a>
    </form>
    <H3 style="color:red;">
      <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
      <?php endif; ?>
    </H3>
  </div>





</body>

</html>