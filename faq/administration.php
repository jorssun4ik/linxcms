<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит FAQ';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';


$set['title'] = 'FAQ::Администрация';
head();

echo '<div class="all"><div class="menu"><div class="text">';

$acount = mysqli_num_rows(quer("SELECT * FROM `users` WHERE `prava` >= '7'"));

if ($acount > 0)
{
echo '<div class="zag">Состав Администрации</div>';
$soz = quer("SELECT * FROM `users` WHERE `prava` >= '7'");

if (mysqli_num_rows($soz) > 0)
{
    $i = 1;
while ($ss = mysqli_fetch_assoc($soz))
{
    $iid = $ss['id'];
    $llogin = $ss['login'];
    $pprava = $ss['prava'];
    
    echo '<div class="c">'.$i.'.&nbsp;';
    if ($aut > 0)
    {
        if ($iid == $id)
        {
            echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">'.$llogin.'</a>';
        }
        else
        {
            echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $iid .'">'.$llogin.'</a>';
        }
    }
    else
    {
        echo $llogin;
    }
    
    //Онлайн пользователь или нет
 	 $uonl = mysqli_num_rows(quer("SELECT * FROM `online` WHERE `u_id` = '".$iid."'"));
     if ($uonl > 0)
	 {
	 	echo '&nbsp;<font color="green">[on]</font>';
	 } else
	 {
	 	echo '&nbsp;<font color="red">[off]</font>';
	 }
     if ($pprava == 9)
     {
        echo ' - Создатель портала';
     }
     elseif ($pprava == 8)
     {
        echo ' - Администратор';
     }
     elseif ($pprava == 7)
     {
        echo ' - Супермодератор';
     }
     elseif ($pprava == 6)
     {
        echo ' - Модератор';
     }
    echo '</div>';
    $i++;
}
}


}
else
{
    echo '<div class="c">Администрации пока еще нет на этом портале!</div>';
}

echo '<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/faq/index.html">FAQ</a></div>
<div class="c"><a href="http://'.$_SERVER['HTTP_HOST'].'/">Главная</a></div>
</div></div>';

foot();

?>