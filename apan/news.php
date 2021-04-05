<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'в запретной зоне';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{
if ($prava >= 8)
{

if (isset($_GET['act']) && !empty($_GET['act'])){
    $act = htmlspecialchars($_GET['act']);
    
    if ($act == 'add'){
        $set['title'] = 'Модуль Новостей::Создание новости';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Создание новости</div>';
        if (isset($_POST['title'],$_POST['msg']) && !empty($_POST['title']) && !empty($_POST['msg'])){
            $title = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['title'])));
            $msg = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['msg'])));
            
            $res = mysqli_num_rows(quer("SELECT `id` FROM `news` WHERE `title` = '". $title ."' && `msg` = '". $msg ."'"));
            
            if (mb_strlen($title) < 5){
                echo '<div class="c">Название новости не может содержать менее 5 символов</div>';
            }
            elseif (mb_strlen($title) > 150){
                echo '<div class="c">Название новости не может содержать более 150 символов</div>';
            }
            elseif (mb_strlen($msg) < 20){
                echo '<div class="c">Новость не может содержать менее 20 символов!</div>';
            }
            elseif ($res > 0){
                echo '<div class="c">Такая новость уже существует!</div>';
            }
            else {
                quer("INSERT INTO `news`(`title`,`msg`,`time`,`login`) VALUES('". $title ."','". $msg ."','". time() ."','". $login ."')");
                echo '<div class="c">Новость успешно добавлена!</div>';
            }
            
        }
        else{
        echo '<div class="c"><form action="news.php?act=add" method="post">
        Название новости:<br />
        <input name="title"/><br />
        Текст новости:<br />
        <textarea name="msg"></textarea><br />
        <br />
        <input type="submit" value="Добавить"/>
        </form></div>';
        }
    }
    elseif ($act == 'edit' && isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
    $id = (int)$_GET['id'];
    $query = quer("SELECT `title`,`msg` FROM `news` WHERE `id` = '". $id ."'");
    
    if (mysqli_num_rows($query) > 0){
        $n = mysqli_fetch_assoc($query);
        $set['title'] = 'Модуль Новостей::Изменение новости';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Изменение новости</div>';
        if (isset($_POST['title'],$_POST['msg']) && !empty($_POST['title']) && !empty($_POST['msg'])){
            $title = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['title'])));
            $msg = trim(htmlspecialchars(mysqli_escape_string($db,$_POST['msg'])));
            
            $res = mysqli_num_rows(quer("SELECT `id` FROM `news` WHERE `title` = '". $title ."' && `msg` = '". $msg ."'"));
            
            if (mb_strlen($title) < 5){
                echo '<div class="c">Название новости не может содержать менее 5 символов</div>';
            }
            elseif (mb_strlen($title) > 150){
                echo '<div class="c">Название новости не может содержать более 150 символов</div>';
            }
            elseif (mb_strlen($msg) < 20){
                echo '<div class="c">Новость не может содержать менее 20 символов!</div>';
            }
            elseif ($res > 0){
                echo '<div class="c">Такая новость уже существует!</div>';
            }
            else {
                quer("UPDATE `news` SET `title` = '". $title ."', `msg` = '". $msg ."' WHERE `id` = '". $id ."'");
                echo '<div class="c">Новость успешно изменена!</div>';
            }
            
        }
        else{
        echo '<div class="c"><form action="news.php?act=edit&amp;id='. $id .'" method="post">
        Название новости:<br />
        <input name="title" value="'. $n['title'] .'"/><br />
        Текст новости:<br />
        <textarea name="msg">'. $n['msg'] .'</textarea><br />
        <br />
        <input type="submit" value="Изменить"/>
        </form></div>';
        }
    }
    else{
        echo '<div class="c">Такой новости нет!</div>';
    }
    }
    elseif ($act == 'del' && isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
        $set['title'] = 'Модуль Новостей::Удаление новости';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title">Удаление новости</div>';
        $id = (int)$_GET['id'];
        $query = quer("SELECT `title` FROM `news` WHERE `id` = '". $id ."'");
        if (mysqli_num_rows($query) > 0){
            quer("DELETE FROM `news` WHERE `id` = '". $id ."'");
            echo '<div class="c">Новость успешно удалена!</div>';
        }
        else{
            echo '<div class="c">С таким ID новости нет!</div>';
        }
    }
    else{
        echo '<div class="c">Ошибка, что то не так!</div>';
    }
    echo '<div class="c"><a href="news.php">Новости</a></div>';   
}
else{
$set['title'] = 'Модуль Новостей';
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">';
echo 'Новости
</div>';
$all = mysqli_num_rows(quer("SELECT `id` FROM `news`"));
$num = 10;
$pag = (int)$_GET['p'];
if (empty($pag) OR $pag == 0)
{
	$start = 0;
} else
{
	$p = $pag - 1;
	$start = $p * $num;
}
if ($all > 0)
{
$query = quer("SELECT * FROM `news` ORDER BY `id` DESC LIMIT ".$start.",".$num."");
while($n = mysqli_fetch_assoc($query))
{
$id = $n['id'];
$title = $n['title'];
$msg = $n['msg'];
$login = $n['login'];
$time = $n['time'];	
echo '<div class="zag">';
echo $title.'['.get_time($time,5).']&nbsp;<a href="news.php?act=edit&amp;id='. $id .'">ред.</a>|<a href="news.php?act=del&amp;id='. $id .'">уд.</a>';
echo '</div>';
echo '<div class="c">
'.bb_code(nl2br(links($msg))).'
</div>';
echo '<small>Добавил:<b>'.$login.'</b></small><br />';	
}
nav($all,$num);
}
else
{
	echo 'Новостей нет!<br />';
}
echo '<br />';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/apan/news.php?act=add">Добавить новость!</a></div>';
}
echo '<div class="c"><a href="index.php">Админ-Панель</a></div>
</div></div>';
}
else
{
	echo '<div class="all">
<div class="title">
Ошибка
</div>
<div class="menu">';
	echo 'Ощибка<br />';
	echo '<META HTTP-EQUIV=refresh CONTENT="0;url=http://'.$_SERVER['HTTP_HOST'].'/index.php">';
}
}
else
{
	echo '<div class="all">
<div class="title">
Ошибка
</div>
<div class="menu">';
	echo 'Ощибка<br />';
	echo '<META HTTP-EQUIV=refresh CONTENT="0;url=http://'.$_SERVER['HTTP_HOST'].'/index.php">';
}
foot();
?>