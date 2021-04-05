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
if (isset($_GET['act']) && !empty($_GET['act'])){
    $act = htmlspecialchars(stripslashes(trim($_GET['act'])));
    
    if ($act == 'contacts'){
        $set['title'] = 'ЛС::Контакт Лист';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::Контакт Лист</div>';
        
        $pag = abs($_GET['p']);
        if (empty($pag) && $pag == 0)
        {
            $start = 0;
        } else
        {
        	$p = $pag - 1;
        	$start = $p*$num;
        }
        
        $q = quer("SELECT `c`.`cid` AS `ccid`,`u`.`login` AS `ulogin` 
                   FROM `msg_cont` AS `c`
                   JOIN `users` AS `u`
                   ON `c`.`uid` = '". $u_id ."' && `c`.`cid` = `u`.`id` && `c`.`type` = '1' 
                   GROUP BY `c`.`cid` ORDER BY `c`.`time` DESC LIMIT $start,$num");
        
        $all = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `type` = '1'"));
        if ($all > 0){
            while($cont = mysqli_fetch_assoc($q)){
                 
                $cv = mysqli_num_rows(quer("SELECT `uid` FROM `msg` WHERE `uid` = '". $u_id ."' && `cid` = '". $cont['ccid'] ."'"));
                $ic = mysqli_num_rows(quer("SELECT `uid` FROM `msg` WHERE `uid` = '". $cont['ccid'] ."' && `cid` = '". $u_id ."'"));   
                $icn = mysqli_num_rows(quer("SELECT `uid` FROM `msg` WHERE `uid` = '". $cont['ccid'] ."' && `cid` = '". $u_id ."' && `read` = '1'"));   
                
                $new = $icn > 0 ? '<font color="red">+'.$icn.'</font>' : '';
                          
                echo '<div class="c"><a href="msg.php?act=chat&amp;id='. $cont['ccid'] .'">'. $cont['ulogin'] .'</a>['. $cv .'/'. $ic .']'. $new .'</div>';
            }
            nav($all,$num,"act=contacts");
        }
        else{
            echo '<div class="c">Никого в контакте нет!</div>';
        }
        echo '<div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::Контакт Лист</div>';
    }
    elseif ($act == 'ignore'){
        $set['title'] = 'ЛС::Игнор Лист';
        head();
        echo '<div class="all"><div class="menu"><div class="text">
        <div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::Игнор Лист</div>';
        
        $pag = abs($_GET['p']);
        if (empty($pag) && $pag == 0)
        {
            $start = 0;
        } else
        {
        	$p = $pag - 1;
        	$start = $p*$num;
        }
        
        $q = quer("SELECT `c`.`cid` AS `ccid`,`u`.`login` AS `ulogin` 
                   FROM `msg_cont` AS `c`
                   JOIN `users` AS `u`
                   ON `c`.`uid` = '". $u_id ."' && `c`.`cid` = `u`.`id` && `c`.`type` = '0' 
                   GROUP BY `c`.`cid` ORDER BY `c`.`time` DESC LIMIT $start,$num");
        
        $all = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `type` = '0'"));
        if ($all > 0){
            while($cont = mysqli_fetch_assoc($q)){
                 
                echo '<div class="c">'. $cont['ulogin'] .' - <a href="msg.php?act=add_contact&amp;id='. $cont['ccid'] .'">добавить в контакт</a></div>';
            }
            nav($all,$num,"act=contacts");
        }
        else{
            echo '<div class="c">Никого в контакте нет!</div>';
        }
        echo '<div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::Игнор Лист</div>';
    }
    elseif ($act == 'add_contact'){
        if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
            $id = (int)$_GET['id'];
            $u = quer("SELECT `login` FROM `users` WHERE `id` = '". $id ."'");
            $uc = mysqli_num_rows($u);
            $c = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `type` = '1' && `cid` = '". $id ."'"));
            
            $set['title'] = 'ЛС::Контакт Лист';
            head();
            echo '<div class="all"><div class="menu"><div class="text">
            <div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::<a href="msg.php?act=contacts">Контакт Лист</a></div>';
            
            if ($uc == 0){
                echo '<div class="c">Пользователя с таким ID не существует!</div>';
            }
            elseif ($id == $u_id){
                echo '<div class="c">Самого себя в контакт добавить нельзя!</div>';
            }
            elseif ($c > 0){
                echo '<div class="c">Данный контакт итак уже существует у вас!</div>';
            }            
            else{
                $ul = mysqli_fetch_assoc($u);
                $r = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."' && `type` = '0'"));
                if ($r > 0){
                    quer("UPDATE `msg_cont` SET `type` = '1',`time` = '". time() ."' WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."'");
                }
                else{
                    quer("INSERT INTO `msg_cont`(`uid`,`cid`,`type`,`time`) VALUES('{$u_id}','{$id}','1','". time() ."')");
                }                
                echo '<div class="c"><a href="info.php?id='. $id .'">'. $ul['login'] .'</a> успешно добавлен в контакты</div>';
            }
            echo '<div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::<a href="msg.php?act=contacts">Контакт Лист</a></div>';
        }
        else{
            Header("Location: ../index.php?err");
            exit();
        }
    }
    elseif ($act == 'add_ignore'){
        if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
            $id = (int)$_GET['id'];
            $u = quer("SELECT `login` FROM `users` WHERE `id` = '". $id ."'");
            $uc = mysqli_num_rows($u);
            $q = quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `type` = '0' && `cid` = '". $id ."'");
            $c = mysqli_num_rows($q);
            
            $set['title'] = 'ЛС::Игнор Лист';
            head();
            echo '<div class="all"><div class="menu"><div class="text">
            <div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::<a href="msg.php?act=ignore">Игнор Лист</a></div>';
            
            if ($uc == 0){
                echo '<div class="c">Пользователя с таким ID не существует!</div>';
            }
            elseif ($id == $u_id){
                echo '<div class="c">Самого себя в игнор добавить нельзя!</div>';
            }
            elseif ($c > 0){
                echo '<div class="c">Данный контакт итак уже в игноре!</div>';
            }            
            else{
                $ul = mysqli_fetch_assoc($u);
                $r = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."' && `type` = '1'"));
                if ($r > 0){
                    quer("UPDATE `msg_cont` SET `type` = '0',`time` = '". time() ."' WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."'");
                }
                else{
                    quer("INSERT INTO `msg_cont`(`uid`,`cid`,`type`,`time`) VALUES('{$u_id}','{$id}','0','". time() ."')");
                }
                echo '<div class="c"><a href="info.php?id='. $id .'">'. $ul['login'] .'</a> успешно добавлен в игнор</div>';
            }
            echo '<div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::<a href="msg.php?act=ignore">Игнор Лист</a></div>';
        }
        else{
            Header("Location: ../index.php?err");
            exit();
        }
    }
    elseif ($act == 'chat'){
        if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
            $id = (int)$_GET['id'];
            $u = quer("SELECT `login` FROM `users` WHERE `id` = '". $id ."'");
            $uc = mysqli_num_rows($u);
            $ign = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $id ."' && `cid` = '". $u_id ."' && `type` = '0'")); //Проверяем или ты в игноре у чела, если да то писать не можешь
            $ygn = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."' && `type` = '0'"));
            $set['title'] = 'ЛС::Чат';
            head();
            echo '<div class="all"><div class="menu"><div class="text">
            <div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::<a href="msg.php?act=contacts">Контакт Лист</a>::Общение</div>';
            
            if ($uc == 0){
                echo '<div class="c">Пользователя с таким ID нет!</div>';               
            }
            elseif ($ign > 0){
                $ul = mysqli_fetch_assoc($u);
                echo '<div class="c"><a href="info.php?id='. $id .'">'. $ul['login'] .'</a> добавил вас в игнор! Писать ему вы не можете!</div>';
            }
            elseif ($ygn > 0){
                $ul = mysqli_fetch_assoc($u);
                echo '<div class="c">Вы добавили <a href="info.php?id='. $id .'">'. $ul['login'] .'</a> в игнор, поэтому ему писать не можете!</div>';
            }
            else{
                
                if (isset($_POST['msg']) && !empty($_POST['msg'])){
                    $msg = mysqli_escape_string($db,(htmlspecialchars(trim($_POST['msg']))));
                    $time = time()-3600;
                    $res = mysqli_num_rows(quer("SELECT `id` FROM `msg` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."' && `msg` = '". $msg ."' && `time` >= '". $time ."'"));
                    if (mb_strlen($msg) < 2){
                        echo '<div class="c"><font color="red">Сообщение слишком короткое(min 2 символа)</font></div>';
                    }
                    elseif ($res > 0){
                        echo '<div class="c"><font color="red">Такое сообщение существует!(такое же сообщение можно писать раз в 1 час)</font></div>';
                    }
                    else{
                        $r = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $id ."' && `cid` = '". $u_id ."'"));
                        if ($r == 0){
                            quer("INSERT INTO `msg_cont`(`uid`,`cid`,`time`) VALUES('". $id ."','". $u_id ."','". time() ."')");
                        }                    
                        quer("INSERT INTO `msg`(`uid`,`cid`,`msg`,`time`,`read`) VALUES('". $u_id ."','". $id ."','". $msg ."','". time() ."','1')");
                        echo '<div class="c"><font color="green">Сообщение успешно добавлено!</font></div>';
                    }
                }
                
                echo '<div class="c"><form action="msg.php?act=chat&amp;id='. $id .'" method="post">
                Текст сообщения:<br />
                <textarea name="msg" cols="20" rows="4"></textarea><br />
                <input type="submit" value="Написать"/>
                </form></div>
                <div class="c"><a href="http://'. $_SERVER['HTTP_HOST'] .'/faq/smiles.html">Смайлы</a>|<a href="http://
                '. $_SERVER['HTTP_HOST'] .'/faq/bb_code.html">BB-Коды</a></div><br />';
                
                $ul = mysqli_fetch_assoc($u);
                $pag = abs($_GET['p']);
                if (empty($pag) && $pag == 0)
                {
                	$start = 0;
                } else
                {
                	$p = $pag - 1;
                	$start = $p*$num;
                }
                $m = quer("SELECT * FROM `msg` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."' OR `uid` = '". $id ."' && `cid` = '". $u_id ."' ORDER BY `time` DESC LIMIT {$start},{$num}");
                $all = mysqli_num_rows(quer("SELECT * FROM `msg` WHERE `uid` = '". $u_id ."' && `cid` = '". $id ."' OR `uid` = '". $id ."' && `cid` = '". $u_id ."'"));
                if ($all > 0){
                    while($ms = mysqli_fetch_assoc($m))
                    {
                        if ($ms['uid'] == $u_id){
                            echo '<div class="zag">&raquo;Я'. ftime($ms['time']);
                            if ($ms['read'] == 1){
                                echo '<font color="red">(!)</font>';
                            }
                            echo'</div>
                            <div class="c">'. antimat(smiles(bb_code(nl2br(links($ms['msg']))))) .'</div>';
                        }
                        else{
                            if ($ms['read'] == 1){
                                quer("UPDATE `msg` SET `read` = '0' WHERE `id` = '". $ms['id'] ."'");
                            }
                            echo '<div class="zag">&raquo;<a href="info.php?id='. $id .'">'. $ul['login'] .'</a>'. online($id) . ftime($ms['time']) .'</div>
                            <div class="c">'. antimat(smiles(bb_code(nl2br(links($ms['msg']))))) .'</div>';
                        }
                    }
                    nav($all,$num,'act=chat&amp;id='.$id);
                }
                else{
                    echo '<div class="c">Сообщений нет!<br /></div>';
                }
                }
            echo '<div class="title"><a href="index.php">Личное меню</a>::<a href="msg.php">ЛС</a>::<a href="msg.php?act=contacts">Контакт Лист</a>::Общение</div>';
        }
        else{
            Header("Location: ../index.php?err");
            exit();
        }        
    }
    else{
        Header("Location: ../index.php?err");
        exit();
    }        
}
else{
$set['title'] = 'Личные сообщения';
head();
echo '<div class="all"><div class="menu"><div class="text">
<div class="title"><a href="index.php">Личное меню</a>::ЛС</div>';
$c = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `type` = '1'"));
$i = mysqli_num_rows(quer("SELECT `uid` FROM `msg_cont` WHERE `uid` = '". $u_id ."' && `type` = '0'"));
$n = mysqli_num_rows(quer("SELECT `id` FROM `msg` WHERE `cid` = '". $u_id ."' && `read` = '1'"));

echo '<div class="c"><img src="'. URL .'images/icons/contact.png" alt="contact"/><a href="msg.php?act=contacts">Контакт Лист</a>['. $c .']';
if ($n > 0){
    echo '<font color="red">+'. $n .'</font>';
}
echo '</div>
<div class="c"><img src="'. URL .'images/icons/minus.png" alt="minus"/><a href="msg.php?act=ignore">Игнор Лист</a>['. $i .']</div>';

echo '<div class="title"><a href="index.php">Личное меню</a>::ЛС</div>';
}
echo '</div></div>';
foot();
} 
else
{
	Header("Location: ../index.php");
}
?>