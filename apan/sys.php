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

if ($prava >= 8)
{
$set['title'] = 'Системные настройки';
head();

echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Админ Панель::Системные настройки
</div>';

if (!isset($mbann) OR empty($mbann))
{
    $mbann = '';
}
if (!isset($obann) OR empty($obann))
{
    $obann = '';
}
if (isset($_POST['site']) && !empty($_POST['site']) && isset($_POST['rega']) && !empty($_POST['rega']) OR !empty($_POST['rega_msg']))
{
    $rega = htmlspecialchars(stripslashes($_POST['rega']));
    $rega_msg = htmlspecialchars(stripslashes($_POST['rega_msg']));
    $site = htmlspecialchars(stripslashes($_POST['site']));
    $mbann = str_ireplace("'","\"",$_POST['mbann']);
    $obann = str_ireplace("'","\"",$_POST['obann']);
    
    if ($rega == 'on' OR $rega == 'off' && !empty($rega_msg))
    {
        $file = '../sys/sys.inc'; //Файл записи
        
        $f1 = fopen($file,'w');
        
$msg = "<?php
 /*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

\$site = '". $site ."';
\$rega = '". $rega ."';
\$rega_msg = '". $rega_msg ."';
\$mbann = '". $mbann ."';
\$obann = '". $obann ."';

?>";        
        
        fwrite($f1,$msg);
        fclose($f1);
        @chmod($file,0666); //на всякий случай 
        echo '<font color="green">Данные успешно сохранены!</font><br /><br />';
    }
    else
    {
        echo '<font color="red">Ошибка сохранения! неправильные данные</font><br /><br />';
    }
}
echo '
<form action="sys.php" method="post">
Баннеры на главной:<br />
<textarea name="mbann">'. $mbann .'</textarea><br />
Баннеры на остальных страницах:<br />
<textarea name="obann">'. $obann .'</textarea><br />
Сайт:<br />
<select name="site">
<option value="on">Открыть</option>
<option value="off">Закрыть</option>
</select><br />
Регистрация:<br />
<select name="rega">
<option value="on">Открыта</option>
<option value="off">Закрыта</option>
</select><br />
Сообщение о закрытой регистрации<br /><small>(если открыта вводить не надо)</small>:<br />
<textarea name="rega_msg"></textarea>
<br />
<input type="submit" value="Сохранить"/>
</form>
<div class="c"><a href="index.php">Админ Панель</a></div>
</div></div>';


foot();
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']."/?err");
}
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']."/?err");
}
?>