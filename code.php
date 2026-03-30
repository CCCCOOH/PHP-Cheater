{{/*  index.php  */}}
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  <?php 
      include  'HeaderNav.html';  
  ?>

<img  src="Images/main.jpg"  style="margin-top: 80px" /> 

<p>
<?php
 if(isset($_SESSION['stuno'])){
    echo $_SESSION['stuno'].",欢迎访问学生信息管理系统！<a href='logout.php'>注销</a>";
 }else{
    echo "欢迎访问！";
 }
 ?>
 </p>

  <?php 
       include  'Footer.html';   
  ?>

</body>
</html>

{{/*  conn.php  */}}
<?php
$db=new mysqli('localhost','root','12345678','studentmis');
if($db->connect_errno){
    exit('数据库连接失败！');
}
?>

{{/*  fetch_assoc.php  */}}
<?php
//引用数据库连接文件
require_once 'conn.php';

//设置字符集，避免中文乱码
$db->query("SET NAMES UTF8");

//定义SQL语句
$sql="select studentid,studentname from student";

//执行SQL语句，返回结果
$result=$db->query($sql);

if($result){
    //获取关联数组
    // while($row=$result->fetch_assoc()){
    //     echo $row['studentid'],',',$row['studentname'],"<br>";
    //  }

    // while($row=$result->fetch_row()){
    //     echo $row['0'],',',$row['1'],"<br>";
    //  }

    //      while($row=$result->fetch_array()){
    //     echo $row['0'],',',$row['studentname'],"<br>";
    //  }

     while($row=$result->fetch_object()){
        echo $row->studentid,',',$row->studentname,"<br>";
     }

     //释放结果集
     $result->close();
}

//关闭连接
$db->close();
?>

{{/*  Footer.html  */}}
