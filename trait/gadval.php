<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>نمایش جدول دیتابیس</title>
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
	<h1>نمایش دیتابیس و جداول آن</h1>
	<table>
		<thead>
			<tr>
				<th>نام دیتابیس</th>
				<th>نام جدول</th>
			</tr>
		</thead>
		<tbody>
			<?php

			$file = file_get_contents('../database/database.txt');
			$data = explode("\n", $file);
			$data = array_filter($data);

			foreach ($data as $line) {
				// بررسی اینکه آیا خط فعلی شامل نام دیتابیس است یا خیر
				if (strpos($line, "Database name") === 0) {
					// اگر شامل نام دیتابیس است، نام دیتابیس را در متغیر $db_name ذخیره کن
					$db_name = substr($line, strpos($line, ":") + 2);
				} else {
					// در غیر این صورت، نام جدول را در متغیر $table_name ذخیره کن و سپس نام دیتابیس و جدول را به عنوان یک ردیف در جدول چاپ کن
					$table_name = substr($line, strpos($line, ":") + 2);
					echo "<tr><td>{$db_name}</td><td>{$table_name}</td></tr>";
				}
			}
			?>
		</tbody>
	</table>
</body>

</html>

<?php

// // خواندن محتوای فایل database.txt
// $file = file_get_contents('database.txt');
// // جدا کردن محتوای فایل بر اساس خط جدید
// $data = explode("\n", $file);
// // حذف خطوط خالی
// $data = array_filter($data);

// foreach ($data as $line) {
// 	// جدا کردن محتوای خط بر اساس کاراکتر ":" و قرار دادن نتیجه در یک آرایه
// 	$parts = explode(":", $line);
// 	$db_name = $parts[1]; // قسمت دوم آرایه برای نام دیتابیس
// 	$table_name = $parts[2]; // قسمت سوم آرایه برای نام جدول
// 	// نمایش نام دیتابیس و جدول در یک ردیف جدول
// 	echo "<tr><td>{$db_name}</td><td>{$table_name}</td></tr>";
// }
?>