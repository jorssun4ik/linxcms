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
$set['title'] = 'Админ панель::Управление друзьями';
head();
echo '<div class="all"><div class="menu"><div class="text"><div class="title">
Админ Панель::Управление друзьями
</div>';

if (isset($_GET['id']) && !empty($_GET['id']))
{
    $id = htmlspecialchars($_GET['id']);
    
    if ($id == 'add')
    {
        if (isset($_POST['title'],$_POST['site']) && !empty($_POST['title']) && !empty($_POST['site'])){
        $title = htmlspecialchars(mysqli_escape_string($db,$_POST['title']));
        $site = $_POST['site'];
        
        if (mb_strlen($title) > 150){
            echo '<div class="c">Слишком большое название(max 150 символов)!</div>';
        } elseif (!filter_var($site,FILTER_VALIDATE_URL)) {
            echo 'Неправильно введен адрес(пример: http://'. $_SERVER['HTTP_HOST'] .')';
        } else {
            $res = mysqli_num_rows(quer("SELECT `id` FROM `friends` WHERE `site` = '". $site ."'"));
            if ($res > 0) {
                echo '<div class="c">Такой сайт уже итак в друзьях!</div>';
            } else {
                quer("INSERT INTO `friends`(`title`,`site`) VALUES('". $title ."','". $site ."')");
                echo '<div class="c">Сайт успешно добавлен!</div>';
            }
        }
        } else {
        echo '<div class="title">Добавить друга/партнера</div>
        <div class="c"><form action="friends.php?id=add" method="post">
        Название ссылки:<br />
        <input name="title"/><br />
        Адрес(с http://):<br />
        <input name="site" value="http://"/><br />
        <input type="submit" value="Добавить"/>
        </form></div>';
        }
        echo '<div class="c"><a href="friends.php">Модуль друзей</a></div>';
    } elseif ($id == 'edit') {
        echo '<div class="title">Редактирование ссылки</div>';
        if (isset($_GET['fid']) && !empty($_GET['fid'])){
            
        }
    }
    else
    {
        echo '<div class="c">Ошибка!</div>';
    }
}
else
{
$query = quer("SELECT `id`,`title` FROM `friends`");    
$res = mysqli_num_rows($query);

if ($res > 0)
{
    while($fr = mysqli_fetch_assoc($query))
    {
        echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/friends.php?id='. $fr['id'] .'">'. $fr['title'] .'</a>[<a href="friends.php?id=edit&amp;fid='. $fr['id'] .'">ed</a>]</div>';
    }    
}
else
{
    echo '<div class="c">Нет ниодного друга у данного портала '. smiles(":D") .'</div>';
}
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/friends.php?id=add">Добавить сайт</a></div>';
}
echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/apan/?">Админ-Панель</a></div>
</div></div>';

foot();
}
else
{
	Header("Location: http://". $_SERVER['HTTP_HOST'] ."?err");
}
}
else
{
	Header("Location: http://". $_SERVER['HTTP_HOST'] ."?err");
}
?>