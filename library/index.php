<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'Смотрит библиотеку';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Библиотека';
head();

if ($aut > 0)
{
    mc();
}

$a = mysqli_num_rows(quer("SELECT `id` FROM `library_art`"));
if ($a > 0){
echo '<div class="all"><div class="menu"><div class="text">
<div class="c">
<a href="new.php">Новые статьи</a>|<img src = "'. URL .'images/icons/find.png" alt = "search"/><a href="search.php">Поиск</a>
</div></div></div></div><br />';
}

echo '<div class="all"><div class="menu"><div class="text"><div class="title">Библиотека</div>';

$result = mysqli_num_rows(quer("SELECT `id` FROM `library_cat`"));

if ($result > 0)
{
     $Query = quer("SELECT * FROM `library_cat` ORDER BY `position` ASC");
      
     while($ctg = mysqli_fetch_assoc($Query))
     {
        $id = $ctg['id']; //ид категории
        $title = $ctg['title']; //Название категории
        $desc = $ctg['description']; //Описание категории
        
        $counter = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `cat_id` = '". $id ."'"));
                   
        echo '<div class="zag"><a href="view.php?id='. $id .'">'. $title .'</a>['. $counter .']</div>';
        if (!empty($desc))
        {
            echo '<div class="c"><small>'. $desc .'</small></div>';
        }
            
     }
}
else
{
    echo '<div class="c">Нет ниодной категории в библиотеке!</div>';
}

$art = mysqli_num_rows(quer("SELECT `id` FROM `library_art`"));
$mart = mysqli_num_rows(quer("SELECT * FROM `library_art` WHERE `mod` = '0'"));


echo '<div class="e">Всего статей: '.$art.'<br />На модерировании: '. $mart;
if ($prava>= 7 && $mart > 0)
{
    echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/mpan/library_mod.php">[модерирование]</a>';
}
echo '</div>';
$cat = mysqli_num_rows(quer("SELECT `id` FROM `library_cat`"));
if ($aut > 0 && $cat > 0)
{
echo '<div class="c"><a href="add_article.php">Добавить статью</a></div>';
}
if ($prava >= 7){
    echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/library.php?id=add">Добавить категорию</a></div>';
}
echo '<br /><div class="c">Библиотеку просматривают: '. onlc("library") .'</div>';
echo '</div></div>';
foot();
?>