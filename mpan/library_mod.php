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

if ($prava >= 6)
{
    $set['title'] = 'Модер панель::Библиотека';
    head();
    echo '<div class="all"><div class="menu"><div class="text"><div class="title">Модерирование статей</div>';
    
    if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    {
        $id = (int)$_GET['id'];
        
        $res = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `id` = '". $id ."' && `mod` = '0'"));
        
        if ($res > 0)
        {
            if (isset($_GET['act']) && !empty($_GET['act']))
            {
                $act = htmlspecialchars($_GET['act']);
                
                if ($act == 'save')
                {
                    quer("UPDATE `library_art` SET `mod` = '1' WHERE `id` = '". $id ."'");
                    echo '<div class="c">Статья успешно добавлена в библиотеку</div>
                    <div class="c"><a href="library_mod.php">назад</a></div>';
                    foot();
                    exit();
                }
                elseif ($act == 'del')
                {
                    quer("DELETE FROM `library_art` WHERE `id` = '". $id ."'");
                    echo '<div class="c">Статья успешно удалена</div>
                    <div class="c"><a href="library_mod.php">назад</a></div>';
                    foot();
                    exit();
                }
                                
            }
            $ar = mysqli_fetch_assoc(quer("SELECT * FROM `library_art` WHERE `id` = '". $id ."' && `mod` = '0'"));
            
            $title = $ar['title'];
            $text = nl2br($ar['text']);
            $time = $ar['time'];
            $autid = $ar['author'];
            
            $u = mysqli_fetch_assoc(quer("SELECT * FROM `users` WHERE `id` = '". $autid ."'"));
            $ulogin = $u['login'];
            $uprav = $u['prava'];
            
            echo '<div class="zag">'.$title.'['. get_time($time,5) .']<a href="library_mod.php?id='. $id .'&amp;act=save">[разрешить]</a><a href="library_mod.php?id='.$id.'&amp;act=del">[отклонить]</a></div>
            <div class="c"><small>Автор: <a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $autid .'">'. $ulogin .'</a>'. status($uprav) .'</small></div>
            <div class="c">'. bb_code(smiles($text)) .'</div>';
            
        }
        else
        {
            echo '<div class="c"><font color="red">Такой статьи нет или такая статья модерирования не требует</font></div>
            <div class="c"><a href="index.php">назад</a></div>';
        }
        
    }
    else
    {
    $all = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `mod` = '0'"));
    if ($all > 0)
    {
        if (empty($_GET['p']))
        {
	        $start = 0;
        } else
        {
            $p = (int)$_GET['p']-1;
        	$start = $p*$num;
        }
        
        $ma = quer("SELECT * FROM `library_art` WHERE `mod` = '0' ORDER BY `id` DESC LIMIT ". $start .",". $num ."");
                
        while($mm = mysqli_fetch_assoc($ma))
        {
            $id = $mm['id'];
            $title = $mm['title'];            
            $time = $mm['time'];
            
            echo '<div class="zag"><a href="library_mod.php?id='. $id .'">'.$title.'['. get_time($time,5) .']</a></div>';
                        
        }
        nav($all,$num);
    }
    else
    {
        echo '<div class="c">На модерации статей нет</div>';
    }
    }
    echo '<div class="c"><a href="index.php">В модерку</a></div>
    </div></div>';
    foot();
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']);
}
}
else
{
    Header("Location: http://".$_SERVER['HTTP_HOST']);
}    
?>