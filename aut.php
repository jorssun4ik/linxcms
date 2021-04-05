<?php

 /*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'вход на сайт';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Авторизация';

if ($aut == 0)
{

if (isset($_GET['conn']))
{
if (isset($_GET['login'],$_GET['password'])){
    $log = mysqli_escape_string($db, htmlspecialchars($_GET['login']));
    $password = mysqli_escape_string($db, htmlspecialchars($_GET['password']));
}   
else{ 
$conn = stripslashes(htmlspecialchars($_GET['conn']));
$log = mysqli_escape_string($db, htmlspecialchars($_POST['log']));
$password = mysqli_escape_string($db, htmlspecialchars($_POST['password']));
}
if (empty($log))
{
head(false);
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Авторизация
</div>';
	echo 'Пустое поле логина!';
}
elseif(empty($password))
{
head(false);
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Авторизация
</div>
Пустое поле пароля';
} elseif(strlen($log) > 50 || strlen($log) < 2)
{
    head(false);
    echo '
    <div class="all"><div class="menu"><div class="text"><div class="title">
    Авторизация
    </div>';
	echo 'Логин за короткий либо за длинный!';
}
else
{
$pass = $password;    
//$login = iconv("utf-8","windows-1251",$login);
$password = md5(md5($password));
$password = substr($password,0,10);

$result = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `login` = '".$log."' && `pass` = '".$password."'"));
if ( $result > 0)
{
if (isset($_POST['zap']))
{
	$zap = (int)$_POST['zap'];
}
if ($zap == 1)
{ 	
setcookie('login',$log, time()+60*60*24*365);
setcookie('pass',$password, time()+60*60*24*365);
}
$_SESSION['login'] = $log;
$_SESSION['pass'] = $password;


head(false);
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Авторизация
</div>';
echo '<div class="c">Вы успешно прошли авторизацию!</div>';
if (!isset($_GET['login'],$_GET['password'])){
echo '<div class="c">Автологин:<br />
<input value="http://'. $_SERVER['HTTP_HOST'] .'/aut.php?conn&amp;login='. $log .'&amp;password='. $pass .'"/></div>';
}
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'">На главную</a></div>';
} else
{
head(false);
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Авторизация
</div>';	
echo 'Ошибка авторизации!<br />
<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/aut/">назад</a></div>
<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/?">на главную</a></div>';	
}
}	
}
else
{
head(false);
echo '<div class="all"><div class="menu"><div class="text">
<div class="title">
Авторизация
</div>';
echo '<form action="http://'.$_SERVER['HTTP_HOST'].'/aut.php?conn" method="post">';
echo 'Логин:<br />';
echo '<input type="text" name="log"/><br />';
echo 'Пароль: <br />';
echo '<input type="password" name="password" /><br />';
echo 'Запомнить меня: <input type="checkbox" name="zap" value="1" /><br />';
echo '<input type="submit" value="Войти" /></form>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/lostpass/">Забыл пароль?</a></div>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/registration/">Регистрация</a></div>';
}
}
else
{
	Header("Location: index.php"); // Чел уже авторизован
    exit();
}
echo '</div></div>';
foot();
?>