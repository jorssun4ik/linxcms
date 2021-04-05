<?php

/**
* @author LinXuS
* @copyright 2009
*/

$gen = substr($gen,0,6);

echo '
</td></tr>
</table>
<table width="780" border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="footer"><br />';
//echo '<a href="http://azban.net/2254.go"><img src="http://azban.net/2254.img" alt="Azban.Net"/></a><br/><a href="http://waplog.net/c.shtml?182114"><img src="http://c.waplog.net/182114.cnt" alt="waplog" /></a>&nbsp;<a href="http://imtop.ru/1976/in/"><img src="http://imtop.ru/1976/small.png" alt="imTop.ru" /></a></br /><a href="http://ru.wapraiting.ru/126.go"><img src="http://wapraiting.ru/sp/126.gif" alt="WapRaiting.Ru" title="Лучший топ сайтов!" /></a>
//';
$allonl = $sql->query("SELECT * FROM `online` GROUP BY `ip`,`browser`")->num_rows;
$uonl = $sql->query("SELECT * FROM `online` WHERE `u_id` != ''")->num_rows;

echo '&copy; <a href="http://'.$_SERVER["SERVER_NAME"].'">LinX&trade;</a> 2009-2010
<a href="http://'. $_SERVER['HTTP_HOST'] .'/online_users.php">Online: '.$uonl.'/'.$allonl.'</a><br />
';

if ($_SERVER['SCRIPT_NAME'] == "/index.php")
{
$all = $sql->query("SELECT * FROM `users`")->num_rows;
$time = time();
if ($all > 0)
{
echo '<small><b><a href="http://'. $_SERVER['HTTP_HOST'] .'/all_users.php">Нас: '. $all .'</a>';
$day = $sql->query("SELECT * FROM `users` WHERE `time` >= '". last_24($time) ."'")->num_rows;
if ($day > 0)
{
echo '<font color="green"><b>+'. $day .'</b></font>';
}
echo '</b></small><br />';
}
}
echo '</td>
		</tr>
</table>
</body>
</html>';
?>
