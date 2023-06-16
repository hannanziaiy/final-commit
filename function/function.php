<?php

$path = $_SERVER['DOCUMENT_ROOT'];

function utf8($conn)
{


  $conn->exec("set names utf8");
}

function checkIfLog()
{

  if (!isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();
  } else if ($_SESSION['user_id']) {
    header('Location: ' . $path . '/web/panel/dashboard');
  }
}
// $path = $_SERVER['DOCUMENT_ROOT'];
// include_once($path . '/web/database/database.php');
// require_once($path . '/web/function/function.php');

function getDBTBName($tbname)
{
  // خواندن نام دیتابیس و جدول از فایل
  $file_path = ($_SERVER['DOCUMENT_ROOT']  . '/web/database/database.txt');
  $file_content = file_get_contents($file_path);
  $lines = explode("\n", $file_content);
  $dbname = trim(str_replace('Database name: ', '', $lines[0]));
  foreach ($lines as $line) {
    if (strpos($line, $tbname) === 1) {
      $tbname = str_replace($tbname . " ", '', $line);
      break;
    }
  }

  return [$dbname, $tbname];
}

///////////USERS
class ActiveRecord
{
  protected $pdo;
  protected $table_name;
  protected $database_name;
  protected $primary_key = 'id';
  protected $columns = [];

  public function __construct(PDO $pdo, $table_name, $database_name, $primary_key = 'id', $columns = [])
  {
    $this->pdo = $pdo;
    $this->table_name = $table_name;
    $this->database_name = $database_name;
    $this->primary_key = $primary_key;
    $this->columns = $columns;
  }

  public function find($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM {$this->database_name}.{$this->table_name} WHERE {$this->primary_key} = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      return false;
    }
    $this->load($row);

    return $row;
    // return  true;
  }

  public function save()
  {
    if ($this->exists()) {
      $this->update();
    } else {
      $this->insert();
    }
  }


  protected function exists()
  {
    return isset($this->{$this->primary_key});
  }

  protected function update()
  {
    $set = [];
    $values = [];

    foreach ($this->columns as $column => $value) {
      if ($column !== $this->primary_key) {
        $set[] = "{$column} = ?";
        $values[] = $value;
      }
    }

    $values[] = $this->{$this->primary_key};

    $sql = "UPDATE {$this->database_name}.{$this->table_name} SET " . implode(', ', $set) . " WHERE {$this->primary_key} = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($values);
  }

  protected function insert()
  {
    $columns = array_keys($this->columns);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $sql = "INSERT INTO {$this->database_name}.{$this->table_name} (" . implode(', ', $columns) . ") VALUES ({$placeholders})";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(array_values($this->columns));
    $this->{$this->primary_key} = $this->pdo->lastInsertId();
  }

  protected function load($data)
  {
    foreach ($data as $key => $value) {
      $this->{$key} = $value;
    }
  }
}

///////////////slug

function generateSeoURL($string, $wordLimit = 0)
{
  define('UTF8_ENABLED', true);
  $separator = '-';

  if ($wordLimit != 0) {
    $wordArr = explode(' ', $string);
    $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
  }

  $quoteSeparator = preg_quote($separator, '#');

  $trans = array(
    '&.+?;'                    => '',
    '[^\w\d _-]'            => '',
    '\s+'                    => $separator,
    '(' . $quoteSeparator . ')+' => $separator
  );

  $string = strip_tags($string);
  foreach ($trans as $key => $val) {
    $string = preg_replace('#' . $key . '#i' . (UTF8_ENABLED ? 'u' : ''), $val, $string);
  }

  $string = strtolower($string);

  return trim(trim($string, $separator));
}
