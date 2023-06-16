<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path . '/web/database/database.php');
require_once($path . '/web/function/function.php');
http: //localhost/web/panel/proc
// شروع یک session
session_start();

// بررسی اینکه کاربر وارد شده است یا خیر
if (!isset($_SESSION['user_id'])) {
  // در صورتی که کاربر وارد نشده باشد، او را به صفحه ورود هدایت کنید
  // کاربر لاگین نکرده است، انتقال به صفحه لاگین با پیام مناسب
  $message = "شما دسترسی به این صفحه ندارید";
  header("Location: ../login?message=" . urlencode($message));
  exit();
}

header("Refresh: 120; url=../login");
[$dbnameC, $tbnameC] = getDBTBName('Table category listing:');
[$dbnameP, $tbnameP] = getDBTBName('Table posts:');



?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>پنل کاربری</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script type="text/javascript" src="../tinymce/tinymce.min.js"></script>


  <script>
    tinymce.init({
      selector: 'textarea#content',
      height: 300,
      plugins: [
        'advlist autolink lists link image imagetools charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code help wordcount'
      ],
      toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
      content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
  </script>
  <script>
    $(document).ready(function() {
      // رویداد submit فرم را مدیریت می‌کنیم
      $('#submit').click(() => {
        // دریافت مقادیر فرم
        var title = $('#title').val();
        var content = tinymce.get('content').getContent();
        var category = $('#category').val();


        // ارسال اطلاعات به فایل s.php با استفاده از Ajax
        $.ajax({
          type: 'POST',
          url: 'proc.php',
          data: {
            title: title,
            content: content,
            category: category
          },
          success: function(response) {
            // دریافت پاسخ موفقیت‌آمیز از فایل s.php و نمایش آن در صفحه

            // خالی کردن محتوای فرم
            $('#title').val('');
            tinymce.get('content').setContent('');
            $('#category').val('');
            alert("ارسال شد")
          }
        });
      });

    });

    $(document).ready(function() {
      $('.edit-btn').click(function() {
        var postTitle = $(this).data('post-title');
        var postContent = $(this).data('post-content');
        var postCategory = $(this).data('post-category');

        // alert(postContent);
        $('#title').val(postTitle);
        tinymce.get('content').setContent(postContent);
        var selectElement = document.getElementById("category");
        selectElement.value = postCategory;
      });
    });

    $(document).ready(function() {
      $('.delete-btn').click(function() {
        var postId = $(this).data('post-id');
        // ارسال درخواست حذف به فایل proc.php با استفاده از Ajax
        $.ajax({
          type: 'POST',
          url: 'proc.php',
          data: {
            delete_post: postId
          },
          success: function(response) {
            // نمایش پیام موفقیت‌آمیز یا هرگونه اقدام دیگر
            alert("پست با موفقیت حذف شد");
            // بارگذاری مجدد صفحه
            location.reload();
          }
        });
      });
    });
  </script>
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
            <form action="proc.php" method="POST">
              <input class="btn btn-primary" type="submit" name="logout" value="خروج از حساب">
            </form>
            <form>
              <div class="mb-3">
                <label for="title" class="form-label">عنوان</label>
                <input type="text" class="form-control" id="title" name="title" required>
                <label for="content" class="form-label">مطلب</label>
                <textarea id="content" name="content" required></textarea>
                <label for="category" class="form-label">نام دسته بندی</label>
                <select class="form-control" id="category" name="category" required>

                  <?php
                  // دریافت لیست دسته بندی‌ها از دیتابیس
                  $stmt = $conn->prepare("SELECT name FROM $dbnameC.$tbnameC");
                  $stmt->execute();
                  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                  // نمایش گزینه‌های دسته بندی
                  foreach ($categories as $category) {
                    echo '<option value="' . $category['name'] . '">' . $category['name'] . '</option>';
                  }
                  ?>
                </select>

                <!-- <input class="btn btn-primary" type="submit" name="submit" value="ثبت"> -->
                <button class="btn btn-primary" type="submit" name="submit" id="submit">ارسال</button>
                <!-- <button class="btn btn-primary" type="submit" name="edit" id="edit">ویرایش</button> -->
              </div>
            </form>
            <div id="display-post"></div>
            <br />
            <h1>نمایش پست ها </h1>

            <?php

            // کد دریافت اطلاعات پست‌ها از جدول posts بر اساس تاریخ بارگذاری
            $query = "SELECT * FROM $dbnameP.$tbnameP ORDER BY publicationDate DESC";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // نمایش فرم ارسال پست
            $editSlug;
            // نمایش اطلاعات پست‌ها
            foreach ($posts as $post) {
              echo '<div class=" border border-dark rounded" style="border: black 2px solid;">';
              echo '<h3>' . $post['title'] . '</h3>';
              echo '<p>' . $post['content'] . '</p>';
              echo '<p>تاریخ بارگذاری: ' . $post['publicationDate'] . '</p>';
              echo '<td><a href="../showPost?slug=' . $post['slug'] . '">لینک</a></td>';
              $serch = new ActiveRecord($conn, $tbnameC, $dbnameC, "id");
              $res = $serch->find($post['cat_id']);
              $cat_name = $res['name'];

              //ویرایش
              $encodedContent = htmlentities($post['content']);
              $displayContent = '<pre><code>' . $encodedContent . '</code></pre>';
              echo '<button class="edit-btn" data-post-title="' . $post['title'] . '" data-post-content="' . $displayContent . '" data-post-category="' .  $cat_name . '"> ویرایش </button>';
              // $serch = new ActiveRecord($conn, $tbnameP, $dbnameP, "id");
              // $res = $serch->find($post['id']);
              //حذف
              echo '<button class="delete-btn" data-post-id="' .  $post['id'] . '">حذف</button>';
              echo '</div>';
            }

            ?>

          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="list-group ">
          <a href="dashboard.php" class="list-group-item ">داشبورد</a>
          <a href="category" class="list-group-item ">دسته بندی ها</a>
          <a href="#" class="list-group-item active">مطالب</a>
          <a href="comments" class="list-group-item">کامنت ها </a>
          <a href="setting" class="list-group-item">تنظیمات کاربری</a>
        </div>
      </div>

    </div>
  </div>


</body>

</html>