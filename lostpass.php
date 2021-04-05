<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */
  
$set['where'] = 'напоминание пароля';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Напоминание пароля';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/online.php';

if ($aut == 0)
{

head();

echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Забыл пароль?
</div>';

if (isset($_GET['send']))
{
$send = (int)$_GET['send'];
$name = mysqli_escape_string($db, htmlspecialchars($_POST['login']));
if ($send == 2)
{
echo '<form action="http://'. $_SERVER['HTTP_HOST'] .'/lostpass.php?send=3" method="post">
Логин:<br />
<input name="login" /><br />
Код:<br />
<input name="code" /><br />
<input type="submit" value="Ok"></form>
<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>
';
}
elseif ($send == 3)
{	
$code = mysqli_escape_string($db, htmlspecialchars($_POST['code']));
$res = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `login` = '".$name."' && `code` = '".$code."'"));
if ($res > 0)
{

$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n',
'o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F',
'G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9');

for($i=0; $i < 8; $i++)
{

$rand = rand(0,count($arr)-1);
$result.= $arr[$rand];
}

//Вытягиваем email
$eem = mysqli_fetch_assoc(quer("SELECT `email` FROM `users` WHERE `login` = '".$name."'"));
$email = $eem['email'];	

$msg = "Вы успешно прошли восстановление пароля!\n Ваши данные: \n Логин: ".$name." \n Пароль: ".$result."\n\n С уважением Администрация ".ucfirst($_SERVER['HTTP_HOST'])." 
	";		

$subject = 'Восстановление пароля['. $_SERVER['HTTP_HOST'] .']';
//$header='From: '. ADMNAME .' <'. ADMMAIL .">\nX-sender: ".$adm_name.' <'.adm_mail.">\nContent-Type: text/plain; charset=utf-8\n";  
             
        $subject = iconv("utf-8","windows-1251",$subject);
		$msg = iconv("utf-8","windows-1251",$msg);
		mail($email,$subject,$msg,"From: support@".$_SERVER['HTTP_HOST']."");
             
			//mb_language ('uni');
			//mb_send_mail($email,$subject,$msg,$header);
	
	$password = md5(md5($result));
    $password = substr($password,0,10);
    
	quer("UPDATE `users` SET `pass` = '". $password ."' WHERE `login` = '".$name."'");
  
    
    echo 'Новый пароль был отправлен на вашу почту!<br />
	<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>';
}
else
{
	echo 'Ошибка!<br />';
}
} elseif ($send == 1)
{
$res = mysqli_num_rows(quer("SELECT `id` FROM `users` WHERE `login` = '".$name."'"));
if ($res > 0)
{
//Вытягиваем email
$eem = mysqli_fetch_assoc(quer("SELECT `email` FROM `users` WHERE `login` = '".$name."'"));
$email = $eem['email'];	
$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n',
'o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F',
'G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9');

for($i=0; $i < 6; $i++)
{

$rand = rand(0,count($arr)-1);
$result.= $arr[$rand];
}

	$msg = "От вашего аккаунта был запрос на восстановление пароля! Если это не вы запросили новый пароль, то проигноируйте сообщение!\n\n
	Чтобы получить новый пароль нужно пройти по ссылке: http://".$_SERVER['HTTP_HOST']."/lostpass.php?send=2 и ввести свой логин и полученный код!\n
	Логин: ".$name."\n
	Полученный код: ".$result."
	";		


        $subject = 'Восстановление пароля['. $_SERVER['HTTP_HOST'] .']';
		$subject = iconv("utf-8","windows-1251",$subject);
		$msg = iconv("utf-8","windows-1251",$msg);
		mail($email,$subject,$msg,"From: support@".$_SERVER['HTTP_HOST']."");
            //mb_language ('uni');
			//mb_send_mail($email,$subject,$msg,$header);
    quer("UPDATE `users` SET `code` = '".$result."' WHERE `login` = '". $name ."'");
	echo 'Данные успешно отправлены!<br/ > В письме были указаны дальнейшая инструкция по восстановлению пароля!<br />
	<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>';
}
else
{
	echo 'Такого логина нет!<br />
	<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>
	';
}
}
else
{
	Header("Location: index.php");
}
} else
{
echo '<form action="http://'. $_SERVER['HTTP_HOST'] .'/lostpass.php?send=1" method="post">
Логин:<br />
<input name="login" /><br />
<input type="submit" value="Ok"></form>
<a href="http://'. $_SERVER['HTTP_HOST'] .'/?">На главную</a>
';

}
echo '</div></div>';
foot();
}
else
{
	Header("Location: index.php");
}

?>