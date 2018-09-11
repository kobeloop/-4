<?php
// sqlの準備
$dsn = 'mysql:dbname=データベース名;host=localhost';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO ($dsn,$user,$password);

// テーブルの作成
$sql = "CREATE TABLE mission_4"
."("
."nomber INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date TEXT,"
."password TEXT"
.")";
$stmt = $pdo->query($sql);

//定数宣言
$name = $_POST['name'];
$comment = $_POST['comment'];
$new_password = $_POST['new_password'];
$de_number = $_POST['delete_num'];
$de_password = $POST['delete_password'];
$edi_number = $_POST['edit_num'];
$edi_password = $POST['edit_passwordel'];
$error_comment = "パスワードが間違っています<br>";

if(!empty($_POST["edit_num"])){
	$sql_select_password = "SELECT password FROM mission_4 WHERE nomber = {$_POST['edit_num']}";
	$result_select_password = $pdo -> query($sql_select_password);
	$result_password_password = $result_select_password -> fetch();
	
	if ($_POST['edit_password'] == $result_password_password["password"]){
		// 名前
		$sql_select_name = "SELECT name FROM mission_4 WHERE nomber = {$_POST['edit_num']}";
		$result_select_name = $pdo -> query($sql_select_name);
		$result_name = $result_select_name -> fetch();
		
		// コメント
		$sql_select_comment = "SELECT comment FROM mission_4 WHERE nomber = {$_POST['edit_num']}";
		$result_select_comment = $pdo -> query($sql_select_comment);
		$result_comment = $result_select_comment -> fetch();
	
		// 編集するnameの取得
		$edi_name = $result_name["name"];
		// 編集するcommentの取得
		$edi_comment = $result_comment["comment"];
	}
}
?>

<!DOCTYPE html>
<html lang = "ja">
<html>
<head>
<meta charset = "UTF-8">
</head>
<body>
<div>

<form method = "POST" action = "mission_4.php">
<!--新規投稿-->
<input type = "text" name = "name" placeholder="名前" value ="<?php echo $edi_name;?>"><br>
<input type = "text" name = "comment" placeholder="コメント" value ="<?php echo $edi_comment;?>"><br>
<input type = "text" name = "new_password" placeholder="パスワード">
<input type = "submit" name ="sent" value = "送信"/><br>

<!--編集モード-->
<input type = "hidden" name = "editting_num" value ="<?php echo $edi_number; ?>">
<br>

<!--削除対象番号-->
<input type = "text" name = "delete_num" placeholder="削除対象番号">
<input type = "text" name = "delete_password" placeholder="パスワード">
<input type ="submit" name = "delete" value = "削除"/><br>
<br>

<!--編集対象番号-->
<input type = "text" name = "edit_num" placeholder="編集対象番号">
<input type = "text" name = "edit_password" placeholder="パスワード">
<input type = "submit" name = "edit" value = "編集"/>

</form>
</div>
</body>
</html>

<?php
// 定数宣言
$name = $_POST['name'];
$comment = $_POST['comment'];
$de_number = $_POST['delete_num'];
$edi_number = $_POST['edit_num'];

// 投稿の日時
$timestamp = time();
$date = date("Y年m月d日 H:i:s",$timestamp);
$data = $count."<>".$name."<>".$comment."<>".$date;

// 新規登録する時
if( !empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['new_password']) && isset($_POST['sent']) && empty($_POST['editting_num'])){
	// データベース内の件数の確認
	$sql_count = 'SELECT COUNT(*) FROM mission_4';
	
	// データ入力
	$sql_insert = $pdo ->prepare("INSERT INTO mission_4 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
	$sql_insert -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql_insert -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql_insert -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql_insert -> bindParam(':password', $password, PDO::PARAM_STR);
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$password = $_POST['new_password'];
	$sql_insert -> execute();
	
	// データ出力
// 	$sql_select = 'SELECT * FROM mission_4';
// 	$result_select = $pdo -> query($sql_select);
// 	foreach($result_select as $row){
// 		echo $row['nomber'].'<>'.$row['name'].'<>'.$row['comment'].'<>'.$row['date'].'<br >';
// 	}
}

// 削除する時
if(!empty($_POST['delete_num']) && !empty($_POST['delete_password']) && isset($_POST['delete'])){
	$sql_select = "SELECT password FROM mission_4 WHERE nomber = {$_POST['delete_num']}";
	// echo $sql_select;
	$result_select = $pdo -> query($sql_select);
	$result_password = $result_select->fetch();
	// var_dump ($result_password);
	// echo $result_password["password"];
	
	// パスワード判定
	if ($_POST['delete_password'] == $result_password["password"]){
		$sql_delete = "delete from mission_4 where nomber = {$_POST['delete_num']}";
		$result_delete = $pdo -> query($sql_delete);
	}
	else{
		echo $error_comment;
	}
	
	// データ出力
// 	$sql_select = 'SELECT * FROM mission_4';
// 	$result_select = $pdo -> query($sql_select);
// 	foreach($result_select as $row){
// 		echo $row['nomber'].'<>'.$row['name'].'<>'.$row['comment'].'<>'.$row['date'].'<br >';
// 	}
}

//編集する名前とコメントを表示
if(!empty($_POST['edit_num']) && isset($_POST['edit']) && !empty($_POST['edit_password'])){
	$sql_select_password = "SELECT password FROM mission_4 WHERE nomber = {$_POST['edit_num']}";
	$result_select_password = $pdo -> query($sql_select_password);
	$result_password_password = $result_select_password -> fetch();
	
	if ($_POST['edit_password'] == $result_password_password["password"]){
		// echo "あってる";
		// 名前
		$sql_select_name = "SELECT name FROM mission_4 WHERE nomber = {$_POST['edit_num']}";
		$result_select_name = $pdo -> query($sql_select_name);
		$result_name = $result_select_name -> fetch();
		
		// コメント
		$sql_select_comment = "SELECT comment FROM mission_4 WHERE nomber = {$_POST['edit_num']}";
		$result_select_comment = $pdo -> query($sql_select_comment);
		$result_comment = $result_select_comment -> fetch();
		
		// 確認
		echo $result_name["name"]. $result_comment["comment"];
		
		 // 編集するnameの取得
		 $edi_name = $result_name["name"];
		 
		 // 編集するcommentの取得
		 $edi_comment = $result_comment["comment"];
	}
	else{
		echo $error_comment;
	}
	
	// データ出力
// 	$sql_select = 'SELECT * FROM mission_4';
// 	$result_select = $pdo -> query($sql_select);
// 	foreach($result_select as $row){
// 		echo $row['nomber'].'<>'.$row['name'].'<>'.$row['comment'].'<>'.$row['date'].'<br >';
// 	}
}

// 編集したものを表示
if( !empty($_POST['name']) && !empty($_POST['comment']) && isset($_POST['sent']) && !empty($_POST['editting_num'])){
	$edi_number = $_POST['editting_num'];
	
	// 上書き
	$new_name = $_POST['name'];
	$new_comment = $_POST['comment'];
	
// 	echo $new_name;
// 	var_dump($new_name);
// 	echo $new_comment;
	$sql_update = "update mission_4 set name = '$new_name' , comment = '$new_comment' where nomber = $edi_number";
	$result_update = $pdo -> query($sql_update);
	
	// データ出力
// 	$sql_select = 'SELECT * FROM mission_4 ';
// 	$result_select = $pdo -> query($sql_select);
// 	foreach($result_select as $row){
// 		echo $row['nomber'].'<>'.$row['name'].'<>'.$row['comment'].'<>'.$row['date'].'<br >';
// 	}
}

// データ出力
$sql_select = 'SELECT * FROM mission_4 ORDER BY nomber';
$result_select = $pdo -> query($sql_select);
foreach($result_select as $row){
	echo $row['nomber'].'<>'.$row['name'].'<>'.$row['comment'].'<>'.$row['date'].'<br >';
}
?>