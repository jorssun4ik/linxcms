<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */
  
$set['where'] = 'регистрируется на сайте';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Регистрация';


if ($aut == 0)
{
if (!isset($rega))
{
    $rega = 'on';
}


if ($rega == 'on')
{
if (!empty($_POST['login']) && !empty($_POST['pass']) && !empty($_POST['email']) && !empty($_POST['s_key']))
{
    $login = filtr($_POST['login']);
    $name = rus_to_lat($login);
	$pass = filtr($_POST['pass']);
	$email = $_POST['email'];
	$s_key = filtr($_POST['s_key']);
	$key = filtr($_POST['key']);
	
	if (!preg_match("/[a-zА-я0-9\-]/i", $login))
	{
     $set['title'] = 'Регистрация';
     head();

 
    echo '<div class="all"><div class="menu"><div class="text"><div class="title">
    Регистрация
    </div>
    ';

	    echo 'Неразрешенные символы в логине!<br />';
	    echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	}
	 elseif (!preg_match("/[a-z0-9\-]/i", $pass))
	{
    head();


    echo '<div class="all"><div class="menu"><div class="text"><div class="title">
    Регистрация
    </div>
    ';

		echo 'Неразрешенные символы в пароле!<br />';
		echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	}
	 elseif (!preg_match("#^[A-z0-9-\._]+@[A-z0-9]{2,}\.[A-z]{2,4}$#ui",$email))
	 {
     head();


echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';


	 	echo 'Неправильно введен e-mail!<br />';
	 	echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	 }
	 elseif (iconv_strlen($login) < 3)
	 {
head();


echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>
';


	 	echo 'Слишком маленький логин(min 3)!<br/ >';
	 	echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	 }
	 elseif (iconv_strlen($login) > 50)
	 {

head();


echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';


	 	echo 'Слишком большой логин(max 50)!<br />';
	 	echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	 }
	 elseif (iconv_strlen($pass) < 4)
	 {
head();


echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';


	 	echo 'Слишком маленький пароль(min 4)<br />';
	 	echo '<a href="'.URL.'registration/">Назад</a><br /></div>';
	 }
	 elseif (iconv_strlen($pass) > 50)
	 {
head();


echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';


	 	echo 'Слишком большой пароль(max 50)<br />';
	 	echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	 }
	 elseif ($key != $s_key)
	 {
head();


echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';


	 	echo 'Неправильный проверочный код!<br />';
	 	echo '<a href="'.URL.'registration/">Назад</a><br /></div></div>';
	 }
	  else
	 {
	 	
		$msg = "Спасибо что выбрали наш портал!\n Надеемся что не только мы, а и дружелюбные люди помогут решить вам множество вопросов, а также надеемся что вы сдесь проведете хорошо свое время! Ваши данные:\n Логин: ".$login." \n Пароль: ".$pass." \n Имя в игре: ".$name." \n\n С уважением администрация портала ". strtoupper($_SERVER['HTTP_HOST']) ."!";		
		
        $subject = 'Регистрация на '.$_SERVER['HTTP_HOST'];
        
		//Пароль переделываем в двойной кеш и обрезаем пароль до 10 символов 
		$pass = md5(md5($pass));
		$pass = substr($pass,0,10);
		
		#Проверяем или такой логин или имейл небыли зарегистрированны
	 	$pr_login = mysqli_num_rows(quer("SELECT * FROM `users` WHERE `name` = '".$name."'"));
	 	$pr_email = mysqli_num_rows(quer("SELECT * FROM `users` WHERE `email` = '".$email."'"));	


if ($pr_login > 0)
{
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';
echo 'Такой логин уже есть в нашей системе, пожалуйста выберите другой!<br /><a href="'.URL.'registration/">Назад</a><br /></div></div>';
} 
elseif($pr_email > 0)
{
head();
echo '<div class="all"><div class="menu"><div class="text">
<div class="title">
Регистрация
</div>';

	echo 'Такой email уже есть в нашей системе, пожалуйста выберите другой!<br />';
    echo '<a href="'. URL .'registration/">Назад</a><br /></div></div>';
} else
{

			$msg = "Спасибо за регистрацию на ". strtoupper($_SERVER['HTTP_HOST']) ."\n Надеемся вам понравится наш портал и вы в дальнейшем будете его чаще посещать!\n Ваши данные\n Логин: ".$nick."\n Пароль:".$passw."\n С уважением комманда ". strtoupper($_SERVER['HTTP_HOST']); 
			
        $subject = iconv("utf-8","windows-1251",$subject);
		$msg = iconv("utf-8","windows-1251",$msg);
		mail($email,$subject,$msg,"From: support@".$_SERVER['HTTP_HOST']."");
            

	$qr = mysqli_num_rows(quer("SELECT * FROM `users`"));
	
	$time = time();
	if ($qr > 0)
	{
        quer("INSERT INTO `users` SET `login` = '".$login."', `name` = '". $name ."', `pass` = '".$pass."', `email` = '".$email."', `time` = '". $time ."', `last_time` = '". $time ."'");		
	}
	else
	{
		quer("INSERT INTO `users` SET `login` = '".$login."', `name` = '". $name ."', `pass` = '".$pass."', `email` = '".$email."', `time` = '". $time ."', `last_time` = '". $time ."', `prava` = '9'");
	}
	$m_id = mysqli_insert_id($db);
	quer("INSERT INTO `anketa` SET `u_id` = '".$m_id."'");
	quer("INSERT INTO `counter` SET `u_id` = '". $m_id ."'");
	quer("INSERT INTO `user_set` SET `u_id` = '". $m_id ."', `count` = '10'");
    head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';

	echo 'Вы успешно зарегистрировались у нас на сайте!
    Предлагаем вам заполнить свою анкету!<br />';
	echo 'Чтобы зайти на сайт <a href="'. URL .'aut.php">авторизации</a><br /></div></div>';
}
}
}
else
{
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Регистрация
</div>';


echo '<form action="http://'.$_SERVER['HTTP_HOST'].'/registration/" method="post">';
echo 'Логин:<br />';
echo '<input name="login"/><br />';
echo 'Пароль:<br />';
echo '<input name="pass"/><br />';
$key = mt_rand(10000,99999);
echo 'E-mail:<br />';
echo '<input name="email"/><br />';
echo 'Проверочный код(<font color="red">'.$key.'</font>):<br />';
echo '<input type="hidden" name="key" value="'.$key.'"/>';
echo '<input name="s_key"/><br />';
echo '<input type="submit" value="Ok"/></form></div></div>';
}
}
else
{
    head();
    
    if (!isset($rega_msg))
    {
        $rega_msg = 'Извените регистрация временно закрыта, попробуйте зарегистрироваться позже!';
    }
    
    echo '<div class="all"><div class="menu"><div class="text"><div class="title">Регистрация</div>
    <div class="menu">'. nl2br($rega_msg) .'
    <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/?">назад</a></div>';
}
}
else
{
	Header("Location: http://". $_SERVER['HTTP_HOST'] ."/index.php"); //Уже авторизован
}
foot();
?>