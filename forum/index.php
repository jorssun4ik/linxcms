<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'смотрит Форум';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

$set['title'] = 'Форум';
head();

if ($aut > 0)
{
mc();
}
$t = mysqli_num_rows(quer("SELECT `id` FROM `forum_t`"));
if ($t > 0){
echo '<div class="all"><div class="menu"><div class="text">
<div class="c">
<a href="new.php?act=npost">Новые посты</a>|<a href="new.php?act=ntopic">Новые темы</a>|<img src = "'. URL .'images/icons/find.png" alt = "search"/><a href="search.php">Поиск</a>
</div></div></div></div><br />';
}
echo '<div class="all"><div class="menu"><div class="text">';
if ($prava >= 8)
{
$all = mysqli_num_rows(quer("SELECT `id` FROM `forum_p`"));
}


if (isset($_GET['act']) && !empty($_GET['act']) && $prava >= 8)
{
    $act = htmlspecialchars($_GET['act']);
    
    $pos = (int)$_GET['id'];
    
    $req = mysqli_num_rows(quer("SELECT `id` FROM `forum_p` WHERE `position` = '". $pos ."'"));
    
    if ($req > 0)
    {
        
        if ($act == 'up' && $pos > 1 && ($pos - 1) >= 1)
        {
            $pp = $pos - 1;
            $i = mysqli_fetch_assoc(quer("SELECT `id` FROM `forum_p` WHERE `position` = '". $pos ."'"));
            $ii = mysqli_fetch_assoc(quer("SELECT `id` FROM `forum_p` WHERE `position` = '". $pp ."'"));

            quer("UPDATE `forum_p` SET `position` = '". $pp ."' WHERE `id` = '". $i['id'] ."'");
            quer("UPDATE `forum_p` SET `position` = '". $pos ."' WHERE `id` = '". $ii['id'] ."'");
            echo '<div class="c"><font color="green">Перемещенно вверх</font></div>';
        }
        elseif ($act == 'down' && $pos < $all && ($pos + 1) <= $all)    
        {
            $pp = $pos + 1;
            $i = mysqli_fetch_assoc(quer("SELECT `id` FROM `forum_p` WHERE `position` = '". $pos ."'"));
            $ii = mysqli_fetch_assoc(quer("SELECT `id` FROM `forum_p` WHERE `position` = '". $pp ."'"));

            quer("UPDATE `forum_p` SET `position` = '". $pp ."' WHERE `id` = '". $i['id'] ."'");
            quer("UPDATE `forum_p` SET `position` = '". $pos ."' WHERE `id` = '". $ii['id'] ."'");
            echo '<div class="c"><font color="green">Перемещенно вниз</font></div>';
        }
        elseif ($act == 'edit')
        {
            $pf = mysqli_fetch_assoc(quer("SELECT * FROM `forum_p` WHERE `position` = '". $pos ."'"));
            if (isset($_POST['title']) && !empty($_POST['title']))
            {
                $title = htmlspecialchars(mysqli_escape_string($db,$_POST['title']));
                $desc = htmlspecialchars(mysqli_escape_string($db,$_POST['desc']));
                
                $res = mysqli_num_rows(quer("SELECT `id` FROM `forum_p` WHERE `title` = '". $title ."'"));
                
                if ($res > 0)
                {
                    echo '<div class="c">Подфорум с таким названием уже существует!</div>';
                }
                else
                {
                    quer("UPDATE `forum_p` SET `title` = '". $title ."', `desc` = '". $desc ."' WHERE `position` = '". $pos ."'");
                    echo '<div class="c">Подфорум изменен!</div>';
                }                
            }
            else
            {
            echo '<div class="c">
            <form action="index.php?act=edit&amp;id='. $pos .'" method="post">
            Название:<br />
            <input name="title" size="20" value="'. $pf['title'] .'"/>
            <br />
            Описание(желательно):<br />
            <textarea name="desc" rows="6" cols="20">'. $pf['desc'] .'</textarea>
            <br /><input type="submit" value="Изменить"/></form>
            </div>';
            }
            echo '<div class="c"><a href="index.php">Форум</a>
            </div></div></div>';
            foot();
            exit();
        }
     }   
     if ($act == 'edit_r'){
            echo '<div class="c">';
            if ($prava >= 8){
            
            if (isset($_GET['id'])){
                $id = (int)$_GET['id'];
                
                $query = quer("SELECT * FROM `forum_r` WHERE `id` = '{$id}'");
                
                if (mysqli_num_rows($query)){
                    $r = mysqli_fetch_assoc($query);
                    if (isset($_POST['submit'])){
                        $title = filtr($_POST['title']);
                        
                        if (iconv_strlen($title) < 3 || iconv_strlen($title) > 40){
                            echo 'Неразрешимое кол. символов в названии(min 3, max 40)!';
                        }
                        else{
                            quer("UPDATE `forum_r` SET `title` = '{$title}' WHERE `id` = '{$id}'");
                            echo 'Название успешно изменено';
                        }
                        
                    }
                    else{
                        echo '<form action="" method="POST">
                        Название:<br />
                        <input name="title" value="'. $r['title'] .'"/><br />
                        <input name="submit" type="submit" value="Изменить" /><br />
                        </form>';
                    }                   
                }
                else{
                    echo 'Неправильный ID';
                }
            }
            else{
                echo 'Неправильный ID';
            }
            }
            else{
                echo 'У вас нет прав';
            }
            echo '</div>
            <div class="c"><a href="index.php">Форум</a></div>';
foot();
exit();
}

}
$p = quer("SELECT `id`,`title`,`position` FROM `forum_p` ORDER BY `position` ASC");

if (mysqli_num_rows($p) > 0)
{
while ($pp = mysqli_fetch_assoc($p))
{
    echo '<div class="zag"><img src="'. URL .'images/icons/for.png" alt="for"/>'. $pp['title'];
    if ($prava >= 8)
    {
        //|<a href="index.php?act=del&amp;id='. $pp['position'] .'">[del]</a>
        echo '<a href="index.php?act=edit&amp;id='. $pp['position'] .'"><img src="'. URL .'images/icons/pencil.png" alt="pencil"/></a>&nbsp;';
        if ($pp['position'] > 1)
        {
            echo '<a href="index.php?act=up&amp;id='. $pp['position'] .'">[up]</a>';
        }
        if ($pp['position'] > 1 && $all > $pp['position'])
        {
            echo '|';
        }
        if ($pp['position'] < $all)
        {
            echo '<a href="index.php?act=down&amp;id='. $pp['position'] .'">[down]</a>';
        }
    }
    echo '</div>';
    $r = quer("SELECT `id`,`title` FROM `forum_r` WHERE `pid` = '". $pp['id'] ."' ORDER BY `position` ASC");
    
    while ($rr = mysqli_fetch_assoc($r))
    {
        //Постов в темах и тем в разделах подфорумов
        $tc = mysqli_num_rows(quer("SELECT `id` FROM `forum_t` WHERE `rid` = '". $rr['id'] ."'"));
        $pc = mysqli_num_rows(quer("SELECT `id` FROM `forum_m` WHERE `pid` = '". $rr['id'] ."'"));
        
        echo '<div class="c"><img src="'. URL .'images/icons/note.png" alt="foe"/><a href="viewtopic.php?id='. $rr['id'] .'">'. $rr['title'] .'</a>['. $tc .'/'. $pc .'] '.($prava >= 8 ? '<a href="index.php?act=edit_r&amp;id='. $rr['id'] .'"><img src="'. URL .'images/icons/pencil.png" alt="pencil"/></a>' : '').'</div>';
    }
        
}
}
else
{
    echo '<div class="c">Подфорумов не существует!</div>';
}
if ($prava >= 7)
{
    echo '<img src="'. URL .'images/icons/add.png" alt="add"/><a href="add.php">Добавить подфорум/раздел</a>';
}
$p = mysqli_fetch_assoc(quer("SELECT COUNT(`id`) AS `pcount` FROM `forum_p`"));
$r = mysqli_fetch_assoc(quer("SELECT COUNT(`id`) AS `rcount` FROM `forum_r`"));
$t = mysqli_fetch_assoc(quer("SELECT COUNT(`id`) AS `tcount` FROM `forum_t`"));
$m = mysqli_fetch_assoc(quer("SELECT COUNT(`id`) AS `mcount` FROM `forum_m`"));

$counter .= $p['pcount'] > 0 ? 'Подфорумов: '.$p['pcount'].'<br />' : '';
$counter .= $r['rcount'] > 0 ? 'Разделов: '.$r['rcount'].'<br />' : '';
$counter .= $t['tcount'] > 0 ? 'Тем: '.$t['tcount'].'<br />' : '';
$counter .= $m['mcount'] > 0 ? 'Сообщений: '.$m['mcount'].'<br />' : '';

$onforum = mysqli_fetch_assoc(quer("SELECT COUNT(`id`) AS `con` FROM `online` WHERE `page` LIKE '/forum/%'"));

$counter .= $onforum['con'] > 0 ? 'На форуме: '.$onforum['con'].' чел.<br />' : '';


echo '<div class="e"><img src="'. URL .'images/icons/stat.png" alt="stat"/>'. $counter .'</div>';
echo '</div></div>';
foot();
?>