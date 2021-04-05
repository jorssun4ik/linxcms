<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит лс';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if ($aut > 0)
{
$set['title'] = 'Удаление сообщения';
head();

echo '<div class="all"><div class="menu"><div class="text">
<div class="title">
Написание сообщение
</div>';

$did = $_POST['did'];
if (count($did) > 0)
{
$cnt = count($did);	
foreach($did as $key => $value)
{
    quer("UPDATE `msg` SET `in` = '1' WHERE `id` = '". intval($value) ."'");
}
echo '<div class="c"><font color="green">Вы успешно удалили '.$cnt.' сообщ.</font></div>';
}
else
{
	echo '<div class="c">Вы ничего не выбрали</div>';
}
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/msg.php">Сообщения</a></div>
</div></div>';

foot();
} 
else
{
	Header("Location: ../index.php");
}
?>