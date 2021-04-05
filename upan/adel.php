<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'изменяет аватар';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{

$set['title'] = 'Удаления аватара';
head();
    	
echo '<div class="all"><div class="menu"><div class="text"><div class="title">Удаление</div>'; 
if(file_exists("avatars/".$login.".gif"))
{
	unlink("avatars/".$login.".gif");
	echo '<div class="c">Аватар успешно удален</div>';
}
else
{
	echo '<div class="c">У вас итак нет аватара</div>';
}

echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/anketa.php">&#171; анкета '. $login .'</a></div>';


echo '</div></div>';

foot();
} 
else
{
	Header("Location: ../index.php");
}
?>