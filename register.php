<?php


$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');



session_start(); // شروع session
checkIfLog();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Validate name and last name fields
    if (empty($_POST['first_name']) || empty($_POST['last_name'])) {
        $errors[] = "نام و نام خانوادگی را وارد کنید.";
    } else {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];

        // Check if name and last name contain only persian characters
        if (!preg_match("/^[\x{0600}-\x{06FF}]+$/u", $first_name) || !preg_match("/^[\x{0600}-\x{06FF}]+$/u", $last_name)) {
            $errors[] = "لطفاً نام و نام خانوادگی خود را به فارسی وارد کنید.";
        } elseif ((strlen($first_name) < 3) || (strlen($last_name) < 3)) {
            $errors[] = " نام و نام خانوادگی باید حداقل 2 کاراکتر داشته باشد.";
        } elseif ((strlen($first_name) > 20) || (strlen($last_name) > 20)) {
            $errors[] = " نام و نام خانوادگی باید حداکثر 20 کاراکتر داشته باشد.";
        }
    }

    // Validate national code field
    if (empty($_POST['national_code'])) {
        $errors[] = "کد ملی را وارد کنید.";
    } else {
        $national_code = $_POST['national_code'];

        // Check if national code contains only digits
        if (!ctype_digit($national_code)) {
            $errors[] = "کد ملی فقط باید شامل عدد باشد.";
        } elseif (strlen($national_code) < 3) {
            $errors[] = "کد ملی باید حداقل 3 کاراکتر داشته باشد.";
        }
    }

    // Validate phone number field
    if (empty($_POST['mobile_number'])) {
        $errors[] = "شماره موبایل را وارد کنید.";
    } else {
        $mobile_number = $_POST['mobile_number'];

        // Check if phone number contains only digits
        if (!ctype_digit($mobile_number)) {
            $errors[] = "شماره موبایل فقط باید شامل عدد باشد.";
        } else {
            // Check if phone number starts with 0
            if (substr($mobile_number, 0, 2) !== "09") {
                $errors[] = "شماره موبایل باید با 09 شروع شود.";
            } elseif ((strlen($mobile_number) < 11) || (strlen($mobile_number) > 11)) {
                $errors[] = "شماره موبایل باید  11 رقم داشته باشد.";
            }
        }
    }

    // Validate password fields

    //
    if (empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $errors[] = "رمزعبور را وارد کنید.";
    } else {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Check if password has at least 8 characters
        if (strlen($password) < 8) {
            $errors[] = "رمزعبور باید حداقل ۸ کاراکتر داشته باشد.";
        } elseif (!preg_match("#[0-9]+#", $password) || !preg_match("#[a-zA-Z]+#", $password) || !preg_match("#[\W]+#", $password)) {
            $errors[] = "رمز عبور باید شامل حروف بزرگ و کوچک انگلیسی، اعداد و نمادها باشد.";
        }

        // Check if password and confirm password match
        if ($password !== $confirm_password) {
            $errors[] = "رمزعبور و تکرار آن با هم مطابقت ندارند.";
        }
    }


    if (!isset($errors[0])) {

        // خواندن نام دیتابیس و جدول از فایل
        $file_content = file_get_contents('database/database.txt');
        $db_data = explode("\n", $file_content);
        $dbname = trim(str_replace('Database name: ', '', $db_data[0]));
        $tbname = trim(str_replace('Table user: ', '', $db_data[1]));


        $db =  $dbname;
        $table  = $tbname;
        ///////////////////////
        // ساخت یوزرنیم و پسورد هش شده
        $userName = $first_name . $last_name . $national_code;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $serchNid = new ActiveRecord($conn, $tbname, $dbname, "nationalId");
        $resNid = $serchNid->find($national_code);
        $serchNum = new ActiveRecord($conn, $tbname, $dbname, "mobile");
        $resNum = $serchNum->find($mobile_number);

        if ($resNid or $resNum) {
            if ($resNid) {
                $errors[] = "کاربری با این کد ملی قبلاً ثبت نام کرده است.";
            }
            if ($resNum) {
                $errors[] = "کاربری با این شماره موبایل قبلاً ثبت نام کرده است.";
            }
        }
    } else {
        echo "اطلاعات وارد شده صحیح نیست";
    }



    if (!isset($errors[0])) {
        $my_users = array(
            "firstName" => $first_name,
            "lastName" => $last_name,
            "userName" => $userName,
            "nationalId" => $national_code,
            "mobile" => $mobile_number,
            "pass" => $hashed_password
        );

        $record = new ActiveRecord($conn, $tbname, $dbname, null, $my_users);
        $record->save();

        echo "<br/>" . " نام کاربری شما : " . $my_users["userName"] . " خواهد بود " . "<br/>";


        echo "<br/>";
        header('refresh:10; url=login');

        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>ثبت نام</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container mt-5">
        <h2>ثبت نام </h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="first_name">نام:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">نام خانوادگی:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="national_code">کد ملی:</label>
                <input type="text" class="form-control" id="national_code" name="national_code" required>
            </div>
            <div class="form-group">
                <label for="mobile_number">شماره موبایل:</label>
                <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
            </div>
            <div class="form-group">
                <label for="password">رمز عبور:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">تکرار رمز عبور:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">ثبت نام</button>
            <a href="login" class="btn btn-primary">ورود </a>
        </form>
        <H3 style="color:red;">
            <?php
            if (!empty($errors)) {
                for ($i = 0; $i < count($errors); $i++) {
                    echo $errors[$i] . "<br>";
                }
            }
            ?></H3>
    </div>
</body>

</html>