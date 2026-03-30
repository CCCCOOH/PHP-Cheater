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

{{/*  HeaderNav.html  */}}
<!-- 网页头部 -->
<header> 
    <meta charset="UTF-8">
    <link rel="stylesheet"  type="text/css" href="style.css">
</header>
<!-- 导航区 -->
<nav>
  <ul>
    <li><a href="index.php">主    页</a></li>
    <li><a href="login.php">用户登录</a></li>
    <li><a href="register.php">学生注册</a></li>
    <li><a href="students.php">学生信息</a></li>
    <li><a href="results.php">成绩查询</a></li>   
  </ul>
</nav>

<!-- 内容区的开始 -->
<main>

{{/*  login.php  */}}
<?php
if(isset($_POST['btnsubmit']))
    {
       $stuno=$_POST['stuno'];
       $pwd=$_POST['pwd'];
       
       require_once 'conn.php';
        
       $sql="select * from student where studentid=$stuno and password=$pwd";

       $result=$db->query($sql);

       if($result->num_rows>=1)
        {    
            //使用Cookie保存登录的学号信息，7天后过期
            setcookie('stuno',$_POST['stuno'],time()+60*60*24*7);
            
            //使用session保存登录的学号信息
            session_start();
            $_SESSION['stuno']=$_POST['stuno'];

            $backurl='index.php';
            
            if(isset($_GET['frompage'])){
                $backurl=$_GET[frompage].'.php';
            }
            echo "<script>window.location='{$backurl}'</script>";
        }
        else{
            echo "<script>window.alert('用户名或密码错误！')</script>";
        }
        
    }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
#login{
    width:300px;
    border:1px solid blue;
    line-height:40px;
    margin:0 auto;
    padding-left:50px;
    padding-top:15px;
    padding-bottom:15px;
    text-align:left;
    font-size:14px;
}
.error{
    color:red;
}
</style>
</head>
<body>
<?php 
    include  'HeaderNav.html';  
?>
<h1>用户登录</h1>
<form action="" method="post">
<div id="login">
    <div>
学号：<input type="text" name="stuno"  value="
<?php
   if(isset($_COOKIE['stuno'])){
    echo $_COOKIE['stuno'];
   }
?>"><span class="error">*</span>
    </div>
    <div>
密码：<input type="password" name="pwd"><span class="error">*</span>
    </div>
    <div style="margin-left:85px;">
        <input type="submit" name="btnsubmit" value="登录">
    </div>
</div>
</form>
<?php 
    include  'Footer.html';    
?>
</body>
</html>

{{/*  logout.php  */}}
<?php
 session_start();
 session_destroy();
 header('Location:index.php');
?>

{{/*  register.php  */}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        body{
            margin:0px;
            text-align: center;
        }
        #reg{
            width:370px;
            border:1px solid blue;
            line-height:40px;
            margin:0 auto;
            padding-left:100px;
            padding-top:15px;
            padding-bottom:15px;
            text-align:left;
            font-size:14px;
        }
         .error{
            color:red;
        } 
    </style>
    <link rel="stylesheet" href="mystyle.css">
</head>
<body>
<?php 
    include  'HeaderNav.html';  
?> 
    <h1>用户注册</h1>
    <form method="post" action="registerdata.php" enctype="multipart/form-data">
    <div id="reg">
    <div>学号：<input type="text" name="stuno"><span class="error">*</span></div>
    <div>姓名：<input type="text" name="stuname"><span class="error">*</span></div>
    <div>密码：<input type="password" name="pwd"><span class="error">*</span></div>
    <div style="margin-left: -28px;">确认密码：<input type="password" name="confirmpwd"><span class="error">*</span></div>
    <div>
        班级：<select name="classname">
              <?php
              require_once 'conn.php';
              $db->query('SET NAMES UTF8');
              $sql='select * from class';
              if($result=$db->query($sql)){
                while($row=$result->fetch_assoc()){
                    echo "<option value='".$row['classno']."'>".$row['classname']."</option>";
                }              
              }
              $result->close();
              $db->close();
              ?>
            </select>
    </div>
    <div>
         性别：<input type="radio" name="sex" value="男" >男
             <input type="radio" name="sex" value="女" checked>女
    </div>
    <div> 
         爱好：<input type="checkbox" name="hobby[]" value="阅读">阅读
             <input type="checkbox" name="hobby[]" value="运动">运动
             <input type="checkbox" name="hobby[]" value="电影">电影
             <input type="checkbox" name="hobby[]" value="音乐">音乐
    </div>
    <div style="margin-left: 42px;margin-top: -12px;">
             <input type="checkbox" name="hobby[]" value="旅游">旅游
             <input type="checkbox" name="hobby[]" value="上网">上网
    </div>
    <div>
        手机：<input type="text" name="mobile"><span class="error">*</span>
    </div>
    <div>
        邮箱：<input type="text" name="email"><span class="error">*</span>
    </div>
    <div> 
         相片：<input type="file" name="photo">
         <br>*上传文件大小不要超过2M，必须是.jpg、.gif、.png类型
    </div>
    <div style="margin-left: 85px;">
        <input type="submit" name="btnsubmit" value="注册">
    </div>
</div>
</form>
<?php 
    include  'Footer.html';    
?>
</body>
</html>

{{/*  registerdata.php  */}}
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
   <?php
// 内容类型声明：Content-type: text/html 部分告知浏览器当前传输的内容是HTML格式，需要以网页形式进行解析和渲染
// 字符编码设置：charset=utf-8 部分将页面字符编码设置为UTF-8，确保中文字符及其他多语言字符能够正确显示而不会出现乱码，需要注意的是，等号两侧不能有空格，否则可能导致编码设置失效

header("Content-type:text/html;charset:UTF-8");//告诉浏览器当前页面使用 UTF-8 编码解析，避免中文字符显示为乱码

require_once 'conn.php';

$db->query('SET NAMES UTF8');//确保 PHP 与数据库之间的数据传输使用 UTF-8 编码，防止从数据库读取的中文内容出现乱码

function checkinput($data){
  $data=trim($data);
  $data=stripslashes($data);
  $data=htmlspecialchars($data);
  return $data;
}

$stuno=checkinput($_POST['stuno']);
if(empty($stuno)){
  echo "<script>alert('学号没有填写');history.go(-1);</script>";
  exit();
}

$sql="select * from student where studentid=$stuno";
$result=$db->query($sql);
if($result->num_rows>0){
  echo "<script>alert('该学号已存在！');history.go(-1);</script>";
  exit();
}

$stuname=checkinput($_POST['stuname']);
if(empty($stuname)){
  echo "<script>alert('姓名没有填写');history.go(-1);</script>";
  exit();
}

$password=checkinput($_POST['pwd']);
if(empty($password)){
  echo "<script>alert('密码没有填写');history.go(-1);</script>";
  exit();
}
   
   //获取班级
   $classname=checkinput($_POST['classname']);
   
  //获取性别
   $sex=checkinput($_POST['sex']);
   
  //获取爱好
  if(array_key_exists('hobby',$_POST))
  {
      $hobby=join(',',$_POST['hobby']);
  }else{
      $hobby='';
  }


  
//     //获取手机号
$mobile=checkinput($_POST['mobile']);

//      //获取邮箱
$email=checkinput($_POST['email']);

   switch($_FILES['photo']['error'])
  {
      case 0: //成功上传
          $ftypes=['image/gif','image/pjpeg','image/jpeg','image/png'];
          $type=$_FILES['photo']['type'];
          if(in_array($type,$ftypes))  //上传的文件是指定的类型
          {
             $fname=$_FILES['photo']['name'];  //上传的原始文件名
             $tmp=explode('.', $fname);//将文件名以"."分隔成两部分，分别为"php","jpg"  
              $newfname=$stuno.'.'.$tmp[1];//新的文件名为学号              
              $destination='upload/'.$newfname;
              move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
          }else
          {echo "<script>alert('上传文件类型不符合要求!');history.go(-1);</script>";
           exit();
        }
          break;
      case 1: //文件大小超过了PHP默认的限制2MB
          echo "<script>alert('上传文件出错，文件大小超过了限制！');history.go(-1);</script>";
          exit();
          break;
      case 4: //没有选择上传文件
          $destination='';
          break;
  }  
  $sql="insert into student values('$stuno','$stuname','$password','$classname','$sex',
  '$hobby','$mobile','$email','$destination')";

  $result=$db->query($sql);
  if($result){
    echo "<script>alert('注册成功');window.location='students.php';</script>";
  }else
  {
    echo "<script>alert('注册失败!');history.go(-1);</script>";
  }
?>

<?php 
    include  'Footer.html';    
?>
</body>
</html>

{{/*  results.php  */}}
<?php
session_start();
if(!isset($_SESSION['stuno'])){
    header("Location:login.php?frompage=results");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
.tb{
	width:80%;
	margin:auto;
	border:1px solid #0094ff;	
	border-collapse:collapse;
	font-size:14px;
}
/* 学生信息页面表格的单元格，边框为1px蓝色，高50px,内间距0 */
.tb tr th,.tb tr td{
	border:1px solid #0094ff;
	height: 50px;
	padding:0;
}
</style>
</head>
<body>
<?php 
    include  'HeaderNav.html';  
?> 

<h1>成绩查询</h1>
<form action=""  method="post">
学号：<input type="text" name="stuno"  >
      <input type="submit" name="btnsubmit"  value="查询">
</form>

<?php
 if(isset($_POST['btnsubmit'])){
    $stuno=$_POST['stuno'];
  
    // if($_SESSION['stuno']==$stuno){
    require_once 'conn.php';
    $db->query("SET NAMES UTF8");

    $sql="select result.studentid,studentname,coursename,mark 
          from student,result,course
          where result.studentid=student.studentid 
           and  result.courseid=course.courseid 
           and result.studentid=$stuno";

    $result=$db->query($sql);

    if($result->num_rows>=1){
          echo "<table class='tb'><tr><th>学号</th><th>姓名</th><th>课程名</th><th>成绩</th></tr>";
          while($row=$result->fetch_assoc()){
            echo "<tr><td>".$row['studentid'].
                "</td><td>".$row['studentname'].
                "</td><td>".$row['coursename'].
                "</td><td>".$row['mark'].
                "</td></tr>";
          }
          echo "</table>";
         
    }else{
        echo "<div style='color:red;margin-top:50px'>没有该生的成绩记录！</div>";
    }
     $result->close();
     $db->close();
// }else{
//     echo "<div style='color:red;margin-top:50px'>只能查询当前登录学号成绩！</div>";
// }
}
?>

  

<?php 
    include  'Footer.html';    
?>
</body>
</html>

{{/*  students.php  */}}
<?php
session_start();
if(!isset($_SESSION['stuno'])){
    header("Location:login.php?frompage=students");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
.tb{
	width:80%;
	margin:auto;
	border:1px solid #0094ff;	
	border-collapse:collapse;
	font-size:14px;
}
/* 学生信息页面表格的单元格，边框为1px蓝色，高50px,内间距0 */
.tb tr th,.tb tr td{
	border:1px solid #0094ff;
	height: 50px;
	padding:0;
}
</style>
</head>
<body>
<?php 
    include  'HeaderNav.html';  
?> 

<h1>学生信息</h1>
<?php
  require_once 'conn.php';

  $db->query('SET NAMES UTF8');

  $sql="select student.*,class.classname
        from student,class 
        where student.classno=class.classno";
   
    // $stuno=$_SESSION['stuno'];
    // $sql="select student.*,class.classname
    //     from student,class 
    //     where student.classno=class.classno and student.studentid=$stuno";

    if($result=$db->query($sql)){
        echo "<form action='upddel.php' method='post'>";
        echo "<input type='submit' name='btndel' value='删除' 
              onclick='return confirm(\"确定要删除选中的学生信息吗?\")'>";
        echo "<input type='submit' name='btnupdate' value='编辑'>";
        echo "<table class='tb'>
        <tr><th></th><th>学号</th><th>姓名</th><th>班级</th><th>性别</th><th>爱好</th><th>手机</th>
        <th>邮箱</th><th>照片</th></tr>";

        while($row=$result->fetch_assoc()){
            echo "<tr><td><input type='checkbox' name='sel[]' value='".$row['studentid']."'>".
                 "</td><td>".$row['studentid'].
                 "</td><td>".$row['studentname'].
                 "</td><td>".$row['classname'].
                 "</td><td>".$row['sex'].
                 "</td><td>".$row['hobby'].
                 "</td><td>".$row['mobile'].
                 "</td><td>".$row['email'].
                 "</td><td><img src='".$row['photo']."' width='35px'>".
                "</td></tr>";
        }   
        echo "</table>";
        echo "</form>";
        $result->close();
     }
     $db->close();
?>

<?php 
    include  'Footer.html';    
?>
</body>
</html>

{{/*  Style.css  */}}
  /* header头部宽960像素，高130像素，背景图片Images/banner.jpg，居中 */
header {
	width:960px;
	height:130px;
	background-image: url('Images/banner.jpg');
	margin: 0px auto;
}

/* nav导航区宽960像素，高30像素，背景颜色为#D2E9FF，居中  */
nav {
	width: 960px;
	height: 30px;
	background-color:#D2E9FF;
	margin: 0px auto;
}

/* nav导航区的列表去掉默认的圆点标记 */
nav ul{
	list-style-type:none;
	margin-left: -40px;
}

/* nav导航区的各列表项水平放置  */
nav ul li {
    width: 100px;
    height: 30px;
	line-height: 30px;
	text-align:center;
	float: left;
}

/* nav导航区的各链接去掉默认的下划线  */
nav a{
	display:block;
	width:100px;
	text-decoration:none;	
}

/* main内容区宽960像素，居中，银色细线边框  */
main{
	width:960px;
	margin: 0px auto;
	text-align:center;
	border:1px solid silver;
	padding-bottom:40px;
}
/* footer页脚宽960像素，高60像素，背景图片Images/footer.jpg，居中  */
footer{
    width:960px;
    height:60px;
    background-image: url('Images/footer.jpg');
    margin: 0px auto;
    line-height: 60px;
    text-align: center;
    color:white;
}

{{/*  update.php  */}}
<?php
header("Content-type:text/html;charset:UTF-8");
require_once 'conn.php';
$db->query('SET NAMES UTF8');

function checkinput($data){
    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}

$stuno=checkinput($_POST['stuno']);
$stuname=checkinput($_POST['stuname']);
if(empty($stuname)){
    echo "<script>alert('姓名没有填写');history.go(-1);</script>";
    exit();
}
//获取班级
$classno=checkinput($_POST['classname']);
//获取性别
$sex=checkinput($_POST['sex']);
//获取爱好
if(array_key_exists('hobby',$_POST))
{
    $hobby=join(',',$_POST['hobby']);
}
else{
    $hobby='';
}
//获取手机号
$mobile=checkinput($_POST['mobile']);
//获取邮箱
$email=checkinput($_POST['email']);
$destination='';
switch($_FILES['photo']['error'])
{
    case 0: //成功上传
    $ftypes=['image/gif','image/pjpeg','image/jpeg','image/png'];
            $type=$_FILES['photo']['type'];
if(in_array($type,$ftypes)) //上传的文件是指定的类型
{
    $fname=$_FILES['photo']['name']; //上传的原始文件名
    $tmp=explode('.',$fname);//将文件名以"."分隔成两部分，分别为"php","jpg"
    $newname=$stuno.'.'.$tmp[1];//新的文件名为学号
    $destination='upload/'.$newname;
    move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
}
else
{
echo "<script>alert('上传文件类型不符合要求!');history.go(-1);</script>";
exit();
}
break;
    case 1: //文件大小超过了PHP默认的限制2MB
echo "<script>alert('上传文件出错，文件大小超过了限制!');history.go(-1);</script>";
exit();
case 4: //没有选择上传文件
    $destination='';
    break;
}
// echo $stuno,$stuname,$classno,$sex,$hobby,$mobile,$email,$destination;
if($destination==''){
    $sql="update student set 
studentname='$stuname',classno='$classno',sex='$sex',hobby='$hobby',mobile='$mobile',
email='$email' where studentid='$stuno'";
}
else{
    $sql="update student set 
studentname='$stuname',classno='$classno',sex='$sex',hobby='$hobby',mobile='$mobile',
email='$email',photo='$destination' where studentid='$stuno'";
}
$result=$db->query($sql);
if($result){
    echo "<script>alert('更新成功!');window.location='students.php';</script>";
}
else{
    echo "<script>alert('更新失败!');window.location='students.php';</script>";
}
?>

{{/*  upddel.php  */}}
<?php
  require_once 'conn.php';

  $db->query('SET NAMES UTF8');

   if(count($_POST['sel'])==0){
        echo "<script>alert('请先选择需要删除或修改的学生信息！');history.go(-1);</script>";
   }
   else{
       if(isset($_POST['btndel']))
       {
        for($i=0;$i<count($_POST['sel']);$i++){
            $sqldelresult="delete from result where studentid='".$_POST['sel'][$i]."'";
            $db->query($sqldelresult);

            $sqldelstudent="delete from student where studentid='".$_POST['sel'][$i]."'";
            $db->query($sqldelstudent);
        }
        echo "<script>alert('删除成功！');window.location='students.php';</script>";
  }
    if($_POST['btnupdate']){
      $sqlstudent="select * from student where studentid='".$_POST['sel'][0]."'";
      $result=$db->query($sqlstudent);
      $row=$result->fetch_assoc();
      $stuno=$row['studentid'];
      $stuname=$row['studentname'];
      $classno=$row['classno'];
      $sex=$row['sex'];
      $hobby=$row['hobby'];
      $mobile=$row['mobile'];
      $email=$row['email'];
      $photo=$row['photo'];  
     // echo $stuno,$stuname,$classno,$sex,$hobby,$mobile,$email,$photo;    
      $result->close();
    ?>
  <?php 
   require_once 'HeaderNav.html';
  ?>
  <style type="text/css">
      body{
          margin:0px;
          text-align: center;
      }
      #reg{
          width:370px;
          border:1px solid blue;
          line-height:40px;
          margin:0 auto;
          padding-left:100px;
          padding-top:15px;
          padding-bottom:15px;
          text-align:left;
          font-size:14px;
      }
        .error{
          color:red;
      } 
    </style>
    <h1>编辑学生信息</h1>
    <form method="post" action="update.php" enctype="multipart/form-data">
    <div id="reg">
    <div>学号：<input type="text" name="stuno" value='<?php echo $stuno?>'></div>
    <div>姓名：<input type="text" name="stuname" value='<?php echo $stuname?>'></div>
    <div>
  班级：<select name="classname">
        <?php
        $sql="select * from class";
        if($result=$db->query($sql)){
          while($row=$result->fetch_assoc()){
            if($row['classno']==$classno){
      echo "<option value='".$row['classno']."' selected>".$row['classname']."</option>";
          } 
          else{
    echo "<option value='".$row['classno']."'>".$row['classname']."</option>";
          }
          }             
        }
        $result->close();
        $db->close();
        ?>
      </select>
  </div>
  <div>
    性别：
    <?php
    if($sex=='男'){
      echo '<input type="radio" name="sex" value="男" checked>男';
    }else{
      echo '<input type="radio" name="sex" value="男" >男';
    }
      if($sex=='女'){
      echo '<input type="radio" name="sex" value="女" checked>女';
    }else{
      echo '<input type="radio" name="sex" value="女">女';
    }
    ?>     
  </div>
  <div>
      爱好： 
      <?php
    if(stristr($hobby,'阅读'))
    {
      echo '<input type="checkbox" name="hobby[]" value="阅读" checked>阅读';
    }else{
      echo '<input type="checkbox" name="hobby[]" value="阅读">阅读';
    }
     if(stristr($hobby,'运动'))
    {
      echo '<input type="checkbox" name="hobby[]" value="运动" checked>运动';
    }else{
      echo '<input type="checkbox" name="hobby[]" value="运动">运动';
    }
     if(stristr($hobby,'电影'))
    {
      echo '<input type="checkbox" name="hobby[]" value="电影" checked>电影';
    }else{
      echo '<input type="checkbox" name="hobby[]" value="电影">电影';
    }
     if(stristr($hobby,'音乐'))
    {
      echo '<input type="checkbox" name="hobby[]" value="音乐" checked>音乐';
    }else{
      echo '<input type="checkbox" name="hobby[]" value="音乐">音乐';
    }
  ?>
  </div>
  <div style="margin-left: 42px;margin-top: -12px;">
    <?php
     if(stristr($hobby,'旅游'))
    {
      echo '<input type="checkbox" name="hobby[]" value="旅游" checked>旅游';
    }else{
      echo '<input type="checkbox" name="hobby[]" value="旅游">旅游';
    } 
    if(stristr($hobby,'上网'))
    {
      echo '<input type="checkbox" name="hobby[]" value="上网" checked>上网';
    }else{
      echo '<input type="checkbox" name="hobby[]" value="上网">上网';
    }
    ?>
    </div>
    <div>
        手机：<input type="text" name="mobile"><span class="error">*</span>
    </div>
    <div>
        邮箱：<input type="text" name="email"><span class="error">*</span>
    </div>
    <div> 
         相片：<input type="file" name="photo">
         <br>*上传文件大小不要超过2M，必须是.jpg、.gif、.png类型
    </div>
    <div style="margin-left: 85px;">
        <input type="submit" name="btnsubmit" value="注册">
    </div>
  </form>
<?php
    }
  }
?>

{{/*  计算器程序.php  */}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        body {
            margin: 0px;
            text-align: center;
        }
        #reg {
            width: 370px;
            border: 1px solid blue;
            line-height: 40px;
            margin: 0 auto;
            padding-left: 100px;
            padding-top: 15px;
            padding-bottom: 15px;
            text-align: left;
            font-size: 14px;
        }
        /* 以下使用的是属性选择器，具体来说是属性值选择器 */
        input[type="submit"] {
            padding: 10px 15px;
            margin: 5px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

    <form method="post" action="">
        <div id="reg">
            <div>
                数值1：<input type="number" name="num1" placeholder="输入第一个数字" step="any">
            </div>
            <div>
                数值2：<input type="number" name="num2" placeholder="输入第二个数字" step="any">
            </div>
            <div>
                <input type="submit" name="operation" value="+">
                <input type="submit" name="operation" value="-">
                <input type="submit" name="operation" value="*">
                <input type="submit" name="operation" value="/">
            </div>
        </div>
    </form>

    <?php
    // 如果你需要后端处理逻辑，可以参考以下简单的 PHP 实现
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $op = $_POST['operation'];
        $result = "";

        if (is_numeric($num1) && is_numeric($num2)) {
            switch ($op) {
                case '+': $result = $num1 + $num2; break;
                case '-': $result = $num1 - $num2; break;
                case '*': $result = $num1 * $num2; break;
                case '/': 
                    $result = ($num2 != 0) ? ($num1 / $num2) : "除数不能为0"; 
                    break;
            }
            echo "<h3 style='margin-top:20px;'>计算结果为：$result</h3>";
        }
    }
    ?>

</body>
</html>
