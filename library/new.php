<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит библиотеку';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Библиотека::Новые статьи';
head();

if ($aut > 0)
{
mc();
}
echo '<div class="all"><div class="menu"><div class="text">';
echo '<div class="title"><a href="index.php">Библиотека</a>::Новые статьи</div>';

$pag = abs($_GET['p']);
    
    if (empty($pag) OR $pag == 0)
    {
	   $start = 0;
    } else
    {
	   $p = $pag - 1;
	   $start = $p*$num;
    }
    $q = quer("SELECT * FROM `library_art` WHERE `mod` = '1' ORDER BY `id` DESC LIMIT ". $start .",". $num ."");
    $res = mysqli_num_rows($q);
if ($res > 0){
    while($qq = mysqli_fetch_assoc($q))
    {
        $aid = $qq['id'];
        $title = $qq['title'];
        $time = $qq['time'];
        $author = $qq['author'];
        
        
        $u = mysqli_fetch_assoc(quer("SELECT `login`,`prava` FROM `users` WHERE `id` = '". $author ."'"));
        $ulogin = $u['login'];
        $uprav = $u['prava'];
        echo '<div class="zag"><a href="article.php?id='. $aid .'">'. $title .'</a>[<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $author .'">'.$ulogin.'</a>('.status($uprav).')]'. ftime($time) .'</div>';        
    }
    nav($res,$num,"id=".$id);
}
else
{
    echo '<div class="c">В данной категории нет статей</div>';
}

echo '<div class="title"><a href="index.php">Библиотека</a>::Новые статьи</div>';
echo '</div></div>';
foot();
?>