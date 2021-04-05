<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

$set['where'] = 'Читает статью';

require_once $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
{
    $id = (int)$_GET['id'];
    //Проверяем или такая статья есть
    $res = mysqli_num_rows(quer("SELECT `id` FROM `library_art` WHERE `id` = '". $id ."'"));
    
    if ($res > 0)
    {
        $ar = mysqli_fetch_assoc(quer("SELECT `title` FROM `library_art` WHERE `id` = '". $id ."'"));
        $set['title'] = 'Комментарии::'.$ar['title'];
        head();
        echo '<div class="all"><div class="menu"><div class="text"><div class="title">Комментарии::'.$ar['title'].'</div>';
        
        if ($aut > 0)
        {
            if (isset($_POST['msg']) && !empty($_POST['msg']))
            {
                $msg = htmlspecialchars(mysqli_escape_string($db, $_POST['msg']));
                
                $r = mysqli_num_rows(quer("SELECT `id` FROM `library_comm` WHERE `id` = '". $id ."' && `msg` = '". $msg ."'"));
                
                if ($r > 0)
                {
                    echo '<div class="c"><font color="red">Такое сообщение уже сдесь существует!</font></div>';
                }
                else
                {
                    quer("INSERT INTO `library_comm`(`art_id`,`u_id`,`msg`,`time`,`ip`,`browser`) VALUES('". $id ."','". $u_id ."','". $msg ."','". time() ."','". $ip ."', '". $browser ."')");
                    echo '<div class="c"><font color="green">Сообщение успешно добавленно</font></div>';
                }
                
            }
            echo '<div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/faq/bb_code.html">BB-Коды</a>|<a href="http://'. $_SERVER['HTTP_HOST'] .'/faq/smiles.php">Смайлы</a></div>
            <form action="http://'. $_SERVER['HTTP_HOST'] .'/library/comm.php?id='. $id .'" method="post">
            Сообщение:<br />
            <textarea name="msg"></textarea><br /><br />
            <input type="submit" value="Добавить"/>
            </form>
            ';
        }
        $all = mysqli_num_rows(quer("SELECT `id` FROM `library_comm` WHERE `art_id` = '". $id ."'")); //Смотрим скока комментов
        
        if ($all > 0)
        {
            $pag = abs($_GET['p']);
            if (empty($pag) OR $pag == 0)
        {
         	$start = 0;
        }
        else
        {
        	$p = $pag - 1;
        	$start = $p*$num;
        }
            $query = quer("SELECT * FROM `library_comm` WHERE `art_id` = '". $id ."' ORDER BY `id` DESC LIMIT ".$start.",".$num."");
            
            while ($q = mysqli_fetch_assoc($query))
            {
                $l_id = $q['u_id'];
                $msg = smiles($q['msg']);
                $time = $q['time'];
                $ip = $q['ip'];
                $browser = $q['browser'];
                
                $u = mysqli_fetch_assoc(quer("SELECT `login`,`prava` FROM `users` WHERE `id` = '". $l_id ."'"));
                $ulogin = $u['login'];
                $uonl = mysqli_num_rows(quer("SELECT `id` FROM `online` WHERE `u_id` = '".$l_id."'"));
                
                if ($uonl > 0)
          	    {
	                $status = '&nbsp;<font color="green">[on]</font>';
	            } 
                else
                {
                    $status = '&nbsp;<font color="red">[off]</font>';
	            }
                
                echo '<div class="zag"><a href="http://'. $_SERVER['HTTP_HOST'] .'/upan/info.php?id='. $l_id .'">'. $ulogin .'</a>'. status($u['prava']) . $status .'['. get_time($time,5) .']</div>
                <div class="c">'. $msg .'</div><br />';
            }
            nav($all,$num,'id='.$id);
        }
        else
        {
            echo '<div class="c"><b>Никто ничего не написал по поводу данной статьи, ты можешь быть первым!</b></div>';
        }
        echo '</div></div>';
        foot();        
    }
    else
    {
        Header("Location: index.php"); //ошибка
    }       
}
else
{
    Header("Location: index.php"); //Перенаправляем
}


?>