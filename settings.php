<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в настройках';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Настройки';

//Подтверждение смены пароля

if ($aut > 0)
{
if (isset($_GET['changepass']))
{
	if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['rnew_pass']) && strlen($_POST['new_pass'])< 50)
	{
         $old_pass = htmlspecialchars(mysqli_escape_string($db, $_POST['old_pass']));
	     $new_pass = htmlspecialchars(mysqli_escape_string($db, $_POST['new_pass']));
	     $rnew_pass = htmlspecialchars(mysqli_escape_string($db, $_POST['rnew_pass']));
	     $old_pass = md5(md5($old_pass));
	     $old_pass = substr($old_pass,0,10);
     	 //Проверка или правильный пароль ввел!
		 $pr_pass = mysqli_num_rows(quer("SELECT * FROM `users` WHERE `pass` = '". $old_pass ."'"));
		 if ($pr_pass == 0)
		 {
            head();
            echo '<div class="c"><font color="red">Вы ввели неправильный теперяшний пароль!</font></div>
            <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'">На главную</a></div>
             ';
		    foot();
            exit();
         }
		 else
		 {
		 	if ($rnew_pass != $new_pass)
		 	{
		 	    head();
                echo '<div class="c"><font color="red">Пароли не совпадают!</font></div>
                 <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'">На главную</a></div>';
		 	    foot();
                exit();
             }
		 	else
		 	{
		 		$new_pass = md5(md5($new_pass));
		 		$new_pass = substr($new_pass,0,10);

                $user = mysqli_fetch_assoc(quer("SELECT * FROM `users` WHERE `id` = '". $u_id ."'"));
                $log = $user['login'];


		 		setcookie('login',$log, time()+60*60*24*365);
                setcookie('pass',$new_pass, time()+60*60*24*365);

		 		$_SESSION['login'] = $log;
		 		$_SESSION['pass'] = $new_pass;

                head();
                quer("UPDATE `users` SET `pass` = '".$new_pass."' WHERE `id`='". $u_id ."'");
		 		echo '<div class="c"><font color="green">Пароль изменен</font></div>
                 <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'">На главную</a></div>';
		 		foot();
                exit();
		 	}
		 }

	}
}
}
else
{
    Header("Location: ../index.php");
    exit();
}
if ($aut > 0)
{
head();
mc();
echo '<div class="all"><div class="menu"><div class="text">';
$prov = mysqli_num_rows(quer("SELECT * FROM `user_set` WHERE `u_id` = '". $u_id ."'"));
if ($prov == 0)
{
	//Вписываемся в бд настройки
	quer("INSERT INTO `user_set` SET `u_id` = '". $u_id ."', `count` = '10'");
	$rand = mt_rand(1000,9999);
	echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/settings/'. $rand .'">Обновить</a>';
}
else
{
//обновление настроек
if (isset($_GET['update']))
{
	if (!empty($_POST['count']) && is_numeric($_POST['count']) && !empty($_POST['style']))
	{
		$count = (int)$_POST['count'];
        $style = htmlspecialchars(stripslashes($_POST['style']));

        $dr = $_SERVER['DOCUMENT_ROOT'].'/styles/wap/'. $style ;

		if ($count == NULL || $count == 0)
		{
			$count = 10;
		}
        elseif (!is_dir($dr)){
            echo '<div class="c">Такого дизайна нет!</div>';
        }
        quer("UPDATE `users` SET `pstyle` = '". $style ."' WHERE `id` = '". $u_id ."'");
		quer("UPDATE `user_set` SET `count` = '". $count ."' WHERE `u_id` = '". $u_id ."'");
		echo '<div class="c"><font color="green">Настройки успешно сохранены!</font></div>';
	}

}

$settings = mysqli_fetch_assoc(quer("SELECT * FROM `user_set` WHERE `u_id` = '". $u_id ."'"));
$count = strip_tags($settings['count']);
$dir = $_SERVER['DOCUMENT_ROOT'].'/styles/wap/';
echo '<div class="title">Настройки пользователя</div><form action="http://'.$_SERVER['HTTP_HOST'].'/settings/?update" method="post">
<div class="c">Количество сообщ. на стр.:<br />
<input name="count" value="'.$count.'"/><br />';
if ($d = opendir($dir)){
echo 'Дизайн:<br /><select name="style">';
$p = mysqli_fetch_assoc(quer("SELECT `pstyle` FROM `users` WHERE `id` = '". $u_id ."'"));
while ($r = readdir($d)){
    if ($r != '.' && $r != '..' && is_dir($_SERVER['DOCUMENT_ROOT'].'/styles/wap/'.$r)){
        if ($r == $p['pstyle']){
            echo '<option value="'. $r .'" selected="selected">'. $r .'</option>';
        }
        else{
            echo '<option value="'. $r .'">'. $r .'</option>';
        }
    }
}
echo '</select><br />';
}
echo '<input type="submit" value="Обновить" /></div></form>';
# Смена пароля
echo '<form action="http://'. $_SERVER['HTTP_HOST'] .'/settings/?changepass" method="post"><div class="title">Смена пароля</div>
<div class="c">Старый пароль: <br /><input name="old_pass" /><br />Новый пароль:<br /><input name="new_pass" /><br />Повторить пароль: <br /><input name="rnew_pass"/><br /><input type="submit" value="Сменить пароль" /></div></form>
</div>';


}
echo '</div>';
foot();
}
else
{
	Header("Location: ../index.php");
}
?>
