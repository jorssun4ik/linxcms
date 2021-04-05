<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'Добавляет статью';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Библиотека::Добавить статью';
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">Библиотека</div>';

if ($aut > 0)
{
if (isset($_POST['title']) && !empty($_POST['title']) && !empty($_POST['cat']) && isset($_POST['cat'])
 && isset($_POST['text']) && !empty($_POST['text']))
{
    $title = htmlspecialchars(trim(mysqli_escape_string($db, $_POST['title'])));    
    $cat = (int)$_POST['cat'];
    $text = htmlspecialchars(trim(mysqli_escape_string($db, $_POST['text'])));
    
    $rcat = mysqli_num_rows(quer("SELECT `id` FROM `library_cat` WHERE `id` = '". $cat ."'"));
    $rtitle = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `title` = '". $title ."'"));
    
    if (mb_strlen($title) < 3 OR mb_strlen($title) > 150)
    {
        echo '<div class="c"><font color="red">Название статьи слишком маленькое или за длинное(мин 3, макс 150 символов))</font></div>
        <div class="c"><a href="add_article.php">назад</a></div>';
    }
    elseif ($rcat == 0)
    {
        echo '<div class="c"><font color="red">Такой категории нет!</font></div>
        <div class="c"><a href="add_article.php">назад</a></div>';
    }
    elseif (mb_strlen($text) < 50)
    {
        echo '<div class="c"><font color="red">Текст статьи не может быть меньше 50 символов</font></div>
        <div class="c"><a href="add_article.php">назад</a></div>';
    }
    elseif ($rtitle > 0)
    {
        echo '<div class="c"><font color="red">Статья с таким названием уже существует</font></div>
        <div class="c"><a href="add_article.php">назад</a></div>';
    }   
    else
    {
        //Записуем
        if ($prava >= 6)
        {
            quer("INSERT INTO `library_art`(`cat_id`,`title`,`text`,`time`,`author`,`mod`) VALUES('". $cat ."','". $title ."','". $text ."','". time() ."','". $u_id ."','1')");
            echo '<div class="c"><font color="green">Статья успешно добавлена</font></div>
            <div class="c"><a href="add_article.php">назад</a></div>';
       
        }
        else
        {
            quer("INSERT INTO `library_art`(`cat_id`,`title`,`text`,`time`,`author`,`mod`) VALUES('". $cat ."','". $title ."','". $text ."','". time() ."','". $u_id ."','0')");            
            echo '<div class="c"><font color="green">Статья добавлена. В библиотеке она будет добавлено после модерирования!</font></div>
            <div class="c"><a href="add_article.php">назад</a></div>';
        }
    }
}
else
{
echo '<form action="add_article.php" method="post">
Название статьи:<br /><input name="title"/><br />
Категория: <br />
<select name="cat">';
$rc = quer("SELECT * FROM `library_cat`");
while($rr = mysqli_fetch_assoc($rc))
{
    $id = $rr['id'];
    $title = $rr['title'];
    
    echo '<option value="'. $id .'">'. $title .'</option>';    
}
echo '</select><br />
Текст статьи: <br /><textarea name="text"></textarea><br />
<input type="submit" value="Добавить статью"/></form>';
echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/bb_code.html">BB-коды</a></div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/smiles.html">Смайлы</a></div>';
}
}
else
{
    echo '<div class="c">Добавлять статьи могут только зарегистрированные</div>';
}
echo '<div class="c"><a href="index.php">В библиотеку</a></div></div></div>';
foot();
?>