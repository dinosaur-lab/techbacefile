<html>
<head>
<meta charset="utf-8">
</head>
<form method="POST" action="" >


<?php

//���͒l���擾//
$comment = $_POST['comment'];
$name = $_POST['name'];
$date =date("Y/m/d H:i:s");
$edit = $_POST['edit'];
$editnumber = $_POST['editnumber'];
$pass =  $_POST['pass'];
$delete = $_POST['delete'];


//3-1:�f�[�^�x�[�X���J��//
$dsn='�f�[�^�x�[�X��';
$user='���[�U�[��';
$password='�p�X���[�h';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));


//3-2:�e�[�u�������//
$sql="CREATE TABLE IF NOT EXISTS table1"
."("
."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."date TEXT,"
."pass TEXT"
.");";
$stmt=$pdo->query($sql);


//3-5:�e�[�u���Ƀf�[�^����͂���//
if(!empty($_POST["comment"]) &&!empty($_POST["name"]) &&!empty($_POST["pass"]) &&empty($_POST["editnumber"])){
	$sql=$pdo->prepare("INSERT INTO table1(name,comment,date,pass)VALUES(:name,:comment,:date,:pass)");
	$sql->bindParam(':name',$name,PDO::PARAM_STR);
	$sql->bindParam(':comment',$comment,PDO::PARAM_STR);
	$sql->bindParam(':date',$date,PDO::PARAM_STR);
	$sql->bindParam(':pass',$pass,PDO::PARAM_STR);
	$sql->execute();
}


//3-8:�폜//
if(!empty($_POST["delete"])&& !empty($_POST["pass"])){
	$sql=$pdo->prepare('SELECT id,pass FROM table1 WHERE id = :id');
	$sql->bindParam(':id',$delete,PDO::PARAM_INT);
	$sql->execute();
	$result =$sql->fetch();
	$correctpass=$result['pass'];

	if($correctpass==$pass){
		$sql='delete from table1 where id=:id';
		$stmt=$pdo->prepare($sql);
		$stmt->bindParam(':id',$delete,PDO::PARAM_INT);
		$stmt->execute();
	}else{
		echo("<h5>�p�X���[�h���Ⴂ�܂�</h5>");
	}
}


if(!empty($_POST["edit"]) && !empty($_POST["pass"])){
	$sql=$pdo->prepare('SELECT id,pass FROM table1 WHERE id = :id');
	$sql->bindParam(':id',$edit,PDO::PARAM_INT);
	$sql->execute();
	$result =$sql->fetch();
	$correctpass=$result['pass'];

	if($correctpass==$pass){
		//�ҏW�@���͗��ɕ\��//
		if(!empty($_POST["edit"])){       //edit�ɐ��l�������ꂽ����s
			$sql=$pdo->prepare('SELECT id,name,comment FROM table1 WHERE id = :edit');
			$sql->bindParam(':edit',$edit,PDO::PARAM_INT);
			$sql->execute();
			$result =$sql->fetch();
			$nameedit=$result['name'];
			$commentedit=$result['comment'];
			$numberedit=$result['id'];
		}
	}else{
		echo("<h5>�p�X���[�h���Ⴂ�܂�</h5>");
		}
}


//3-7:�ҏW//
if(!empty($_POST['name']) &&!empty($_POST['comment']) && !empty($_POST['editnumber'])){       //editnumber�ɐ��l�������ꂽ����s
	$sql='update table1 set name=:name,comment=:comment,date=:date where id=:editnumber';
	$stmt=$pdo->prepare($sql);
	$stmt->bindParam(':name',$name,PDO::PARAM_STR);
	$stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
	$stmt->bindParam(':date',$date,PDO::PARAM_STR);
	$stmt->bindParam(':editnumber',$editnumber,PDO::PARAM_INT);
	$stmt->execute();
}
?>


<input type="text" name="name" placeholder="���O" value="<?php echo $nameedit; ?>"><br />
<input type="text" name="comment" placeholder="�R�����g" value="<?php echo $commentedit; ?>">
<input type="submit" value="���M"><br /><br />
<input type="hidden" name="editnumber" value="<?php echo $numberedit; ?>">
<input type="text" name="delete" placeholder="�폜�Ώ۔ԍ�" >
<input type="submit" value="�폜"><br /><br />
<input type="text" name="edit" placeholder="�ҏW�ԍ�">
<input type="submit" value="���M"><br />
<input type="text" name="pass" placeholder="�p�X���[�h">
<input type="submit" value="�p�X���[�h"><br /><br />
<?php
if(!empty($_POST["comment"]) &&empty($_POST["name"])){
	echo("<h5>���O����͂��Ă�������</h5>");
}
if(!empty($_POST["name"]) &&empty($_POST['comment'])){
	echo("<h5>�R�����g����͂��Ă�������</h5>");
}
if(!empty($_POST["name"]) &&!empty($_POST['comment']) &&empty($_POST['editnumber']) &&empty($_POST['pass'])){
	echo("<h5>�p�X���[�h����͂��Ă�������</h5>");
}
?>

<?php
//3-6:�f�[�^��\������//
$sql='SELECT*FROM table1 ORDER BY id ASC';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
foreach($results as $row){
//$row�̒��ɂ̓e�[�u���̃J������������
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['date'].'<br>';
}
?>
</form>
</html>