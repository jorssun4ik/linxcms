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


 
$pr = mysqli_fetch_assoc(quer("SELECT `prava` FROM `users` WHERE `login` = '".$login."'"));
$prava = strip_tags($pr['prava']);

if ($prava >= 7)
{
    if (isset($_GET['id']))
    {
    $set['title'] = 'Модер Панель: Удаление бана';
    head();
    $id = (int)$_GET['id'];
    
    $m = mysqli_fetch_assoc(quer("SELECT * FROM `ban` WHERE `id` = '". $id ."'"));     
    if ($m > 0)
    {
        quer("DELETE FROM `ban` WHERE `id` = '". $id ."'");
        echo '<div class="all"><div class="text"><div class="menu">Вы успешно разблокировали данного человека!</div></div>';
    }
    else
    {
        echo '<div class="all"><div class="text"><div class="menu">Неверный id
        </div></div>';
    }
    foot();
}
    else
    {
        Header("Location: ../index.php");
    }

    
    
    
}
else
{
    header("Location: ../index.php");
}
}
else
{
    Header("Location: ../index.php");    
}

?>