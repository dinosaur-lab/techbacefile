<html>
<head>
<meta charset="utf-8">
<style type="text/css">
<!--
h1{  
    width: 670px;
    text-shadow: 0 7px 10px rgba(0, 0, 0, .5);
    margin: 0 auto;  /*中央に配置*/
}
body{  
    font-family='Yu Gothic','ＭＳ Ｐゴシック',serif;
    background-color: #ffffff;  
}
/*タブ切り替え全体のスタイル*/
.tabs {
  margin-top: 50px;
  padding-bottom: 40px;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  width: 300px;
  margin: 0 auto;
}

/*タブのスタイル*/
.tab_item {
  width: calc(100%/3);
  height: 50px;
  border-bottom: 3px solid #5ab4bd;
  background-color: #d9d9d9;
  line-height: 50px;
  font-size: 16px;
  text-align: center;
  color: #565656;
  display: block;
  float: left;
  text-align: center;
  font-weight: bold;
  transition: all 0.2s ease;
}
.tab_item:hover {
  opacity: 0.75;
}

/*ラジオボタンを全て消す*/
input[name="tab_item"] {
  display: none;
}

/*タブ切り替えの中身のスタイル*/
.tab_content {
  display: none;
  padding: 40px 40px 0;
  clear: both;
  overflow: hidden;
}


/*選択されているタブのコンテンツのみを表示*/
#all:checked ~ #all_content,
#kiroku:checked ~ #kiroku_content,
#gakusyuunaiyou:checked ~ #gakusyuunaiyou_content {
  display: block;
}

/*選択されているタブのスタイルを変える*/
.tabs input:checked + .tab_item {
  background-color: #5ab4bd;
  color: #fff;
}
-->
</style>
</head>
<form method="POST" action="" >


<?php

//入力値を取得//
$comment = $_POST['comment'];
$name = $_POST['name'];
$month = $_POST['month'];
$day = $_POST['day'];
$edit = $_POST['edit'];
$editnumber = $_POST['editnumber'];
$delete = $_POST['delete'];


//3-1:データベースを開く//
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));


//3-2:テーブルを作る//
$sql="CREATE TABLE IF NOT EXISTS tableyu"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."month TEXT,"
."day TEXT"
.");";
$stmt=$pdo->query($sql);


//3-5:テーブルにデータを入力する//
if(!empty($_POST["comment"]) &&!empty($_POST["name"]) &&empty($_POST["editnumber"])){
	$sql=$pdo->prepare("INSERT INTO tableyu(name,comment,month,day)VALUES(:name,:comment,:month,:day)");
	$sql->bindParam(':name',$name,PDO::PARAM_STR);
	$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql->bindParam(':month',$month,PDO::PARAM_STR);
	$sql->bindParam(':day',$day,PDO::PARAM_STR);
	$sql->execute();
}


//3-8:削除//
if(!empty($_POST["delete"])){
	$sql='delete from table11 where id=:id';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':id',$delete,PDO::PARAM_INT);
	$stmt->execute();
}


if(!empty($_POST["edit"])){
	//編集　入力欄に表示//
	if(!empty($_POST["edit"])){       //editに数値が入れられたら実行
		$sql=$pdo->prepare('SELECT id,name,comment,month,day FROM tableyu WHERE id = :edit');
		$sql->bindParam(':edit',$edit,PDO::PARAM_INT);
		$sql->execute();
		$result =$sql->fetch();
		$nameedit=$result['name'];
		$commentedit=$result['comment'];
		$monthedit=$result['month'];
		$dayedit=$result['day'];
		$numberedit=$result['id'];
	}
}


//3-7:編集//
if(!empty($_POST['name']) &&!empty($_POST['comment']) &&!empty($_POST['month']) &&!empty($_POST['day']) && !empty($_POST['editnumber'])){       //editnumberに数値が入れられたら実行
	$sql='update tableyu set name=:name,comment=:comment,month=:month,day=:day where id=:editnumber';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':name',$name,PDO::PARAM_STR);
	$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
	$stmt->bindParam(':month',$month,PDO::PARAM_STR);
	$stmt->bindParam(':day',$day,PDO::PARAM_STR);
	$stmt->bindParam(':editnumber',$editnumber,PDO::PARAM_INT);
	$stmt->execute();
}
?>
<br>
<body>
<font face="HG行書体" color="#000000" size="5"><h1>プログラミング　学習備忘録</h1></font>
<hr size="2"  color="#000000" />


<input id="all" type="radio" name="tab_item" checked>
<label class="tab_item" for="all">PHP</label>
<input id="kiroku" type="radio" name="tab_item" >
<label class="tab_item" for="kiroku">Python</label>
<input id="gakusyuunaiyou" type="radio" name="tab_item" >
<label class="tab_item" for="gakusyuunaiyou">HTML</label>

<div class="tab_content" id="all_content">
<b>学習日</b>
<br />
<input type="text" name="month" size="15" value="<?php echo $monthedit; ?>">
月
<input type="text" name="day" size="15" value="<?php echo $dayedit; ?>">
日
<br />
<b>学習時間</b><br />
<input type="text" name="name" size="15" value="<?php echo $nameedit; ?>">
時間
<br />
<b>学習内容</b><br />
<textarea name="comment" cols="50" rows="6" ><?php echo $commentedit; ?></textarea>
<input type="submit" value="送信"><br /><br />
<input type="hidden" name="editnumber" value="<?php echo $numberedit; ?>">
<input type="text" name="delete" placeholder="削除対象番号" >
<input type="submit" value="削除"><br /><br />
<input type="text" name="edit" placeholder="編集番号">
<input type="submit" value="送信"><br />
</body>

<p>
<?php
if(!empty($_POST["comment"]) &&empty($_POST["name"])){
	echo("<h5>名前を入力してください</h5>");
}
if(!empty($_POST["name"]) &&empty($_POST['comment'])){
	echo("<h5>コメントを入力してください</h5>");
}
?>
</p>

<?php
//3-6:データを表示する//
$sql='SELECT*FROM tableyu ORDER BY id ASC';
$stmt=$pdo->query($sql);

$results=$stmt->fetchAll();

foreach($results as $row){
//$rowの中にはテーブルのカラム名が入る
echo 'No.'.$row['id'].' ';
echo $row['month'].'月 ';
echo $row['day'].'日 ';
echo $row['name'].'時間 | ';
echo $row['comment'].'</br>';
}
?>
</div>
<div class="tab_content" id="kiroku_content">
    Pythonの学習内容がここに入ります
</div>
<div class="tab_content" id="gakusyuunaiyou_content">
    HTMLの学習内容がここに入ります
</div>
</form>
</html>
