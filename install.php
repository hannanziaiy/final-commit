   <?php
    $path = $_SERVER['DOCUMENT_ROOT'];
    include_once($path . '/web/database/database.php');
    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      // Get the form data
      $host = $_POST["host"];
      $username = $_POST["username"];
      $password = $_POST["password"];
      $dbname = $_POST["dbname"];
      $tbname = $_POST["tbname"];

      try {

        $db = $dbname;
        $content = null;
        $conn->exec("CREATE DATABASE `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")

          or die(print_r($conn->errorInfo(), true));
        echo "database creat successfully <br/>";
        $content = "Database name: " . $db . "\n";
      } catch (PDOException $e) {
        echo  "<br>" . $e->getMessage();
      }
      $table = $tbname;


      $sqlUser = "CREATE TABLE $db.$table (`id` INT NOT NULL AUTO_INCREMENT , `firstName` VARCHAR(30) NOT NULL , `lastName` VARCHAR(30) NOT NULL , `userName` TEXT(50) NOT NULL , `nationalId` INT(40) NOT NULL , `mobile` VARCHAR(20) NOT NULL , `pass` TEXT(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";

      $sqlPost = "CREATE TABLE `db`.`posts` (`id` INT NOT NULL AUTO_INCREMENT , `cat_id` INT NOT NULL, `title` VARCHAR(50) NOT NULL , `slug` VARCHAR(50) NOT NULL , `content` TEXT NOT NULL , `author` VARCHAR(40) NOT NULL ,`publicationDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  PRIMARY KEY (`id`), UNIQUE (`slug`)) ENGINE = InnoDB;";

      $sqlCategory = "CREATE TABLE `db`.`categories` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(40) NOT NULL ,`slug` VARCHAR(50) NOT NULL , `description` TEXT NOT NULL , PRIMARY KEY (`id`), UNIQUE (`slug`)) ENGINE = InnoDB;";

      $sqlTags = "CREATE TABLE `db`.`tags` (`id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";

      $sqlComments = "CREATE TABLE `db`.`comments` (`id` INT NOT NULL AUTO_INCREMENT , `post_id` INT NOT NULL , `content` TEXT NOT NULL , `author` VARCHAR(50) NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), INDEX (`post_id`)) ENGINE = InnoDB;";
      //, INDEX (`post_id`)
      // "CREATE TABLE `db`.`comments` (CONSTRAINT `fk_comments_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`))";
      $sqlScores = "CREATE TABLE `db`.`scores` (`id` INT NOT NULL AUTO_INCREMENT , `postId` INT NOT NULL , `score` INT NOT NULL , PRIMARY KEY (`id`), INDEX (`postId`)) ENGINE = InnoDB;";

      $sqlMedia = "CREATE TABLE `db`.`media` (`id` INT NOT NULL AUTO_INCREMENT , `post_id` INT NOT NULL , `fileName` VARCHAR(150) NOT NULL , `filePath` VARCHAR(200) NOT NULL , `fileType` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`), INDEX (`post_id`)) ENGINE = InnoDB;";

      try {
        $conn->query($sqlUser) &&
          $conn->query($sqlPost) &&
          $conn->query($sqlCategory) &&
          $conn->query($sqlTags) &&
          $conn->query($sqlComments) &&
          $conn->query($sqlScores) &&
          $conn->query($sqlMedia)
          or die(print_r($conn->errorInfo(), true));
        echo "table creat successfully <br/>";
        $content = $content . " Table user: " . $table . "\n" . " Table scores: " . "scores" . "\n" . " Table category listing: " . "categories" . "\n" . " Table posts: " . "posts" . "\n" . " Table tags: " . "tags" . "\n" . " Table media: " . "media" . "\n" . " Table comments: " . "comments";
        file_put_contents($path . "/web/database/database.txt", $content);
      } catch (PDOException $e) {
        echo  "<br>" . $e->getMessage();
      }


      // create the content to be written to the file
      // $content = "Database name: " . $db . "\n" ;
      // $content =$content . " Table name: " . $table;

      // write the content to the file


      // $conn = null;

      header("location: register");
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
       <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
         <div class="form-group">
           <label for="host"> Host: </label>
           <input type="text" class="form-control" id="host" name="host" value="localhost" required>
         </div>
         <div class="form-group">
           <label for="username">Username: </label>
           <input type="text" class="form-control" id="username" name="username" value="root" required>
         </div>
         <div class="form-group">
           <label for="password"> Password: </label>
           <input type="password" class="form-control" id="password" name="password" value="" placeholder="پیش فرض خالی">
         </div>
         <div class="form-group">
           <label for="dbname"> Database Name: </label>
           <input type="text" class="form-control" id="dbname" name="dbname" required>
         </div>
         <div class="form-group">
           <label for="tbname"> Table Name: </label>
           <input type="text" class="form-control" id="tbname" name="tbname" required>
         </div>

         <button type="submit" class="btn btn-primary">ثبت</button>

       </form>
       <H3 style="color:red;">
         <?php
          if (!empty($errors)) {
            for ($i = 0; $i < count($errors); $i++) {
              echo $errors[$i] . "<br>";
            }
          }
          ?>
       </H3>
     </div>





   </body>

   </html>