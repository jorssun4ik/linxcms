<?php

/**
 * @author LinXs
 * @copyright 2010
 */
include 'tpl.php';

if (file_exists($_SERVER['DOCUMENT_ROOT']."/sys/db.php")){
    include $_SERVER['DOCUMENT_ROOT']."/sys/db.php";
    
    $res = mysqli_num_rows(mysqli_query($db,"SELECT `login` FROM `users`"));
}

if (isset($res) && $res > 0){
    Header("Location: ../index.php?err"); //Ошибка:)
    exit();
}
$set['title'] = 'Инсталляция';
head();
echo '<div class="all"><div class="menu"><div class="text">';
if (isset($_GET['step']) && !empty($_GET['step'])){
    $step = (int)$_GET['step'];
    if ($step == 1)
    {
        echo '<div class="title">Этап 1::Работа с БД</div>';
        if (isset($_POST['host'])){
            $host = trim(htmlspecialchars(stripslashes($_POST['host'])));
            $user = trim(htmlspecialchars(stripslashes($_POST['user'])));
            $pass = trim(htmlspecialchars(stripslashes($_POST['pass'])));
            $name = trim(htmlspecialchars(stripslashes($_POST['name'])));
            
            $db = mysqli_connect($host,$user,$pass);
            mysqli_select_db($db,$name);
            
            if (!$db){
                echo 'Ошибка соединения!';
            }
            else{
                if (!file_exists($_SERVER['DOCUMENT_ROOT']."/sys/db.php")){
                    $f = fopen($_SERVER['DOCUMENT_ROOT']."/sys/db.php","w");
                    $file = "<?php
/**
 * @author LinXuS
 * @copyright 2009
 */
 
// error_reporting(0);
 
\$mysql_host = '". $host ."';
\$mysql_name = '". $name ."';
\$mysql_user = '". $user ."';
\$mysql_pass = '". $pass ."';

\$db = mysqli_connect(\$mysql_host,\$mysql_user,\$mysql_pass,\$mysql_name);
mysqli_query(\$db,\"SET NAMES 'utf8'\");

if (mysqli_connect_error())
{
    die(\"Нет соединения с базой данных\");
}

?>";
                fwrite($f,$file);   
                fclose($f);
                }

                $file = fopen("sql.sql","r");
                $res = fread($file,filesize("sql.sql"));
                
                $q = split_db($res);
                $count = count($q) - 1;
                
                for ($i = 0; $i < $count;$i++){
                    if (!mysqli_query($db,($q[$i]))){
                        echo 'Ошибка:'.mysql_error($db).'<br />';
                    }                    
                }
                echo '<div class="c">Импорт баз успешно завершен!</div>
                <div class="c"><a href="?step=2">Добавление администратора</a></div>';
            }          
        }
        else{
        echo '<div class="title">Этап 1::Установка соединения с бд</div>';
        echo '<div class="c"><form action="?step=1" method="post">
        Хост:<br />
        <input name="host" value="localhost"/><br />
        Пользователь от БД:<br />
        <input name="user" value="root"/><br />
        Пароль от БД<br />
        <input name="pass"/><br />
        База:<br />
        <input name="name"/><br />
        <input type="submit" value="Сохранить"/><br />
        </form></div>';
        }
    }
    elseif ($step == 2 && $res == 0){
        echo '<div class="title">Этап 2::Регистрация Главного администратора</div>';
        $r = mt_rand(10000,99999);
        echo '<form action="?step=3" method="post">';
        echo 'Логин:<br />';
        echo '<input name="login" value="Admin"/><br />';
        echo 'Пароль:<br />';
        echo '<input name="pass" value="'. $r .'"/><br />';
        echo 'E-mail:<br />';
        echo '<input name="email" value="admin@'. $_SERVER['HTTP_HOST'] .'"/><br />';
        echo '<input type="submit" value="Закончить установку"></form></div></div>';
    }
    elseif ($step == 3 && $res == 0){
        echo '<div class="title">Этап 3::Конец регистрации</div>';
        
        if (isset($_POST['login'],$_POST['pass'],$_POST['email']) && !empty($_POST['login']) && !empty($_POST['pass']) && !empty($_POST['email'])){
        
        $login = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['login'])));
        $name = rus_to_lat($login);
        $pass = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['pass']))); 
        $email = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['email']))); 
        
        $passw = $pass;
        $pass = md5(md5($pass));
		$pass = substr($pass,0,10);
        
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)){
            echo '<div class="c">Неправильно введен e-mail(Пример: admin@'. $_SERVER['HTTP_HOST'] .')</div>
            <div class="c"><a href="?step=2">назад</a></div>';
        }
        else{
        $time = time();    
        mysqli_query($db,"INSERT INTO `users` SET `login` = '".$login."', `name` = '{$name}', `pass` = '".$pass."', `email` = '".$email."', `time` = '". $time ."', `last_time` = '". $time ."', `prava` = '9'");
    	$m_id = mysqli_insert_id($db);
    	mysqli_query($db,"INSERT INTO `anketa` SET `u_id` = '".$m_id."'");
    	mysqli_query($db,"INSERT INTO `user_set` SET `u_id` = '". $m_id ."', `count` = '10'");
        echo '<div class="c">Регистрация успешно завершена! Незабудьте удалить папку install, изменить права на sys(777->755), sys/db.php(666->644).
        А также прочитайте ReadMe.txt и license.txt, чтобы не было разногласий!
        </div>
        <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/aut.php?conn&amp;login='. $login .'&amp;password='. $passw .'">Войти на сайт</a></div>';
        
        }
        }
        else{
            echo '<div class="c">Вы не ввели какие то данные!</div>
            <div class="c"><a href="?step=2">назад</a></div>';
        }
    }
}
else{
echo '<div class="title">Этап 0::Проверка</div>';
if (extension_loaded("mbstring")){
    echo '<div class="c"><font color="green">mb_string на данном сервере работает!</font></div>';
}
else{
    echo '<div class="c"><font color="red">mb_string на данном сервере не работает!</font></div>';
}
if (perm($_SERVER['DOCUMENT_ROOT']."/sys/") == 777){
    echo '<div class="c"><font color="green">Папка sys - ОК</font></div>';
}
else{
    echo '<div class="c"><font color="red">Папка sys - FAILED(поставьте права 777)</font></div>';
}
if (perm($_SERVER['DOCUMENT_ROOT']."/library/temp/") == 777){
    echo '<div class="c"><font color="green">Папка library/temp - ОК</font></div>';
}
else{
    echo '<div class="c"><font color="red">Папка library/temp - FAILED(поставьте права 777)</font></div>';
}
if (perm($_SERVER['DOCUMENT_ROOT']."/forum/temp/") == 777){
    echo '<div class="c"><font color="green">Папка forum/temp - ОК</font></div>';
}
else{
    echo '<div class="c"><font color="red">Папка forum/temp - FAILED(поставьте права 777)</font></div>';
}
if (perm($_SERVER['DOCUMENT_ROOT']."/sys/sys.inc") == 666){
    echo '<div class="c"><font color="green">Файл sys/sys.inc - ОК</font></div>';
}
else{
    echo '<div class="c"><font color="red">Файл sys/sys.inc - FAILED(поставьте права 666)</font></div>';
}
if (perm($_SERVER['DOCUMENT_ROOT']."/sys/sys.inc") == 666 && perm($_SERVER['DOCUMENT_ROOT']."/sys/") == 777 && extension_loaded("mbstring") &&
 perm($_SERVER['DOCUMENT_ROOT']."/library/temp/") == 777 && perm($_SERVER['DOCUMENT_ROOT']."/forum/temp/") == 777)
{
    echo '<div class="c">Перед продолжением прочитайте <a href="ReadMe.txt">ReadMe</a><br />
    Нажав пройти дальше вы соглашаетесь с <a href="license.txt">условием использования</a> данной CMS!</div>
    <div class="c"><font color="green"><a href="index.php?step=1">Пройти дальше</a></font></div>';
}
else{
    echo '<div class="c"><font color="red"><a href="index.php">Обновить</a></font></div>';
}
}
foot();
?>