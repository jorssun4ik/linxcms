<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */
  
  require $_SERVER['DOCUMENT_ROOT'].'/sys/kernel.php';
  
  if ($prava < 8){
      Header('Location: ../index.php');
      exit();
  }  
  
  $set['title'] = 'Администрирование::Управление рекламой';
  head();
  echo '<div class="all"><div class="menu"><div class="text">';

  
  switch ($act = isset($_GET['act']) ? $_GET['act'] : ''){
      case 'delete':
      if (isset($_GET['id'])){
          $id = (int)$_GET['id'];
          
          $query = quer("SELECT * FROM `reclame` WHERE `id` = '{$id}'");
          
          if (mysqli_num_rows($query)){
              quer("DELETE FROM `reclame` WHERE `id` = '{$id}'");
              echo '<div class="c">Сайт успешно удален из рекламы!</div>';
          }
          else{
              echo '<div class="c">Неправильный ID</div>';
          }          
      }
      else{
          echo '<div class="c">Неправильный ID</div>';
      }
      echo '<div class="c"><a href="reclame.php">Реклама</a><br />
      <a href="index.php">Админка</a></div>';
      break;
      case 'edit':
      if (isset($_GET['id'])){
          $id = (int)$_GET['id'];
          
          $query = quer("SELECT * FROM `reclame` WHERE `id` = '{$id}'");
          
          if (mysqli_num_rows($query)){
              $r = mysqli_fetch_assoc($query);
              
              if (isset($_POST['submit'])){
          $title1 = filtr($_POST['title1']);
          $title2 = filtr($_POST['title2']);
          $title3 = filtr($_POST['title3']);
          $site = filtr($_POST['site']);
          $where = filtr($_POST['where']);
          $page = filtr($_POST['page']);
          
          echo '<div class="c">';
          if (empty($title1)){
              echo 'Пустое название!';
          }
          elseif (iconv_strlen($title1) < 5 && iconv_strlen($title1) > 100){
              echo 'Неразрешимое кол. символов в названии(min 5,max 100)!';
          }
          elseif (!empty($title2) && iconv_strlen($title2) < 5 && iconv_strlen($title2) > 100){
              echo 'Неразрешимое кол. символов в названии(min 5,max 100)!';
          }
          elseif (!empty($title3) && iconv_strlen($title3) < 5 && iconv_strlen($title3) > 100){
              echo 'Неразрешимое кол. символов в названии(min 5,max 100)!';
          }
          elseif (!preg_match('#http://[a-z0-9\-]{3,30}\.[a-z]{2,4}#iU',$site)){
              echo 'Неразрешимые символы в ссылке!';              
          }
          elseif ($where != 'u' && $where != 'd'){
              echo 'Что что не так с позицией';
          }          
          elseif ($page != 'main' && $page != 'other' && $page != 'all'){
              echo 'Что то не так!';
          }
          else{
              //Какая позиция ссылки будет
                           
              $title = $title1 . (!empty($title2) ? '|'.$title2 : '') . (!empty($title3) ? '|'.$title3 : '');
              quer("UPDATE `reclame` SET `title` = '{$title}',`site` = '{$site}', `where` = '{$where}', `page` = '{$page}' WHERE `id` = '{$id}'");
              echo 'Данный сайт успешно изменен!';              
          }
          echo '</div>';          
      }
      else{
      $title = explode('|',$r['title']);
      echo '<div class="title">Добавление рекламы</div>
      <div class="zag">
      <form action="reclame.php?act=edit&amp;id='. $id .'" method="POST">
      Название 1:<br />
      <input name="title1" value="'. $title[0] .'"/><br />
      Название 2:<br />
      <input name="title2" value="'.(isset($title[1]) && !empty($title[1]) ? $title[1] : '').'"/><br />
      Название 3:<br />
      <input name="title3" value="'.(isset($title[2]) && !empty($title[2]) ? $title[2] : '').'"/><br />
      Сайт(с http://):<br />
      <input name="site" value="'. $r['site'] .'"/><br />
      Позиция:<br />
      <select name="where">
      <option value="u" '.( $r['where'] == 'u' ? 'selected="selected"' : '').'>Верх</option>
      <option value="d" '.( $r['where'] == 'd' ? 'selected="selected"' : '').'>Низ</option>
      </select><br />
      Где:<br />
      <select name="page">
      <option value="main" '.($r['page'] == 'main' ? 'selected="selected"' : '').'>На главной</option>
      <option value="other" '.($r['page'] == 'other' ? 'selected="selected"' : '').'>На остальных</option>
      <option value="all" '.($r['page'] == 'all' ? 'selected="selected"' : '').'>На всех</option>
      </select><br />
      <input name="submit" type="submit" value="Изменить"/>
      </form></div>';
      }
          }
          else{
              echo '<div class="c">Неправильный ID</div>';
          }          
      }
      else{
          echo '<div class="c">Неправильный ID</div>';
      }
      echo '<div class="c"><a href="reclame.php">Реклама</a><br />
      <a href="index.php">Админка</a></div>';
      break;
      case 'up':
      if (isset($_GET['id'])){
          $id = (int)$_GET['id'];
          $query = quer("SELECT `page`,`position`,`where` FROM `reclame` WHERE `id` = '{$id}'");
          
          if (mysqli_num_rows($query)){
              $r = mysqli_fetch_assoc($query);
              $all = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = '{$r['where']}' && `page` = '{$r['page']}'"));
              if ($r['position'] < 1 || $all > 1){
                  $p = $r['position'] - 1;
                  
                  $d = mysqli_fetch_assoc(quer("SELECT `id` FROM `reclame` WHERE `where` = '{$r['where']}' && `page` = '{$r['page']}' && `position` = '{$p}'"));
                  
                  quer("UPDATE `reclame` SET `position` = '{$p}' WHERE `id` = '{$id}'");
                  quer("UPDATE `reclame` SET `position` = '{$r['position']}' WHERE `id` = '{$d['id']}'");
                  echo '<div class="c">Вы успешно передвинули рекламу вверх!</div>';
              }
              else{
                  echo '<div class="c">Ошибка</div>';
              }
              
          }
          else{
              echo '<div class="c">Неправильный ID</div>';
          }          
      }   
      else{
          echo '<div class="c">Неправильный ID</div>';
      }
      echo '<div class="c"><a href="reclame.php">Реклама</a><br />
      <a href="index.php">Админка</a></div>';
      break;
      case 'down':
      if (isset($_GET['id'])){
          $id = (int)$_GET['id'];
          $query = quer("SELECT `page`,`position`,`where` FROM `reclame` WHERE `id` = '{$id}'");
          
          if (mysqli_num_rows($query)){
              $r = mysqli_fetch_assoc($query);
              $all = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = '{$r['where']}' && `page` = '{$r['page']}'"));
              if ($r['position'] >= 1 || $r['position'] < $all){
                  $p = $r['position'] + 1;
                  
                  $d = mysqli_fetch_assoc(quer("SELECT `id` FROM `reclame` WHERE `where` = '{$r['where']}' && `page` = '{$r['page']}' && `position` = '{$p}'"));
                  
                  quer("UPDATE `reclame` SET `position` = '{$p}' WHERE `id` = '{$id}'");
                  quer("UPDATE `reclame` SET `position` = '{$r['position']}' WHERE `id` = '{$d['id']}'");
                  echo '<div class="c">Вы успешно передвинули рекламу вниз!</div>';
              }
              else{
                  echo '<div class="c">Ошибка</div>';
              }
              
          }
          else{
              echo '<div class="c">Неправильный ID</div>';
          }          
      }   
      else{
          echo '<div class="c">Неправильный ID</div>';
      }
      echo '<div class="c"><a href="reclame.php">Реклама</a><br />
      <a href="index.php">Админка</a></div>';
      break;
      case 'add':
      if (isset($_POST['submit'])){
          $title1 = filtr($_POST['title1']);
          $title2 = filtr($_POST['title2']);
          $title3 = filtr($_POST['title3']);
          $site = filtr($_POST['site']);
          $days = intval($_POST['days']);
          $where = filtr($_POST['where']);
          $page = filtr($_POST['page']);
          
          echo '<div class="c">';
          if (empty($title1)){
              echo 'Пустое название!';
          }
          elseif (iconv_strlen($title1) < 5 && iconv_strlen($title1) > 100){
              echo 'Неразрешимое кол. символов в названии(min 5,max 100)!';
          }
          elseif (!empty($title2) && iconv_strlen($title2) < 5 && iconv_strlen($title2) > 100){
              echo 'Неразрешимое кол. символов в названии(min 5,max 100)!';
          }
          elseif (!empty($title3) && iconv_strlen($title3) < 5 && iconv_strlen($title3) > 100){
              echo 'Неразрешимое кол. символов в названии(min 5,max 100)!';
          }
          elseif (!preg_match('#http://[a-z0-9\-]{3,30}\.[a-z]{2,4}#iU',$site)){
              echo 'Неразрешимые символы в ссылке!';              
          }
          elseif ($days <= 0){
              echo 'Неправильное количество дней!';
          }
          elseif ($where != 'u' && $where != 'd'){
              echo 'Что что не так с позицией';
          }          
          elseif ($page != 'main' && $page != 'other' && $page != 'all'){
              echo 'Что то не так!';
          }
          else{
              //Какая позиция ссылки будет
              $pos = mysqli_num_rows(quer("SELECT * FROM `reclame` WHERE `where` = '{$where}' && `page` = '{$page}'")) + 1;              
              
              
              
              $days = time() + (60 * 60 * 24 * $days);
              $title = $title1 . (!empty($title2) ? '|'.$title2 : '') . (!empty($title3) ? '|'.$title3 : '');
              
              quer("INSERT INTO `reclame`(`title`,`site`,`time`,`position`,`where`,`page`) VALUES('{$title}','{$site}','{$days}','{$pos}','{$where}','{$page}')");
              echo 'Данный сайт успешно добавлен!';              
          }
          echo '</div>';          
      }
      else{
      echo '<div class="title">Добавление рекламы</div>
      <div class="c">
      <form action="reclame.php?act=add" method="POST">
      Название 1:<br />
      <input name="title1" /><br />
      Название 2:<br />
      <input name="title2" /><br />
      Название 3:<br />
      <input name="title3" /><br />
      Сайт(с http://):<br />
      <input name="site" value="http://"/><br />
      Дней:<br />
      <input name="days"/><br />
      Позиция:<br />
      <select name="where">
      <option value="u">Верх</option>
      <option value="d">Низ</option>
      </select><br />
      Где:<br />
      <select name="page">
      <option value="main">На главной</option>
      <option value="other">На остальных</option>
      <option value="all">На всех</option>
      </select><br />
      <input name="submit" type="submit" value="Добавить"/>
      </form></div>';
      }
      echo '<div class="c"><a href="reclame.php">Реклама</a><br />
      <a href="index.php">Админка</a></div>';
      break;
      default:
      echo '<div class="title">Реклама</div>';
      $all = mysqli_num_rows(quer("SELECT `id` FROM `reclame`"));
      
      if ($all){
          $query = quer("SELECT * FROM `reclame` WHERE `where` = 'u' ORDER BY `position` ASC");
          
          if (mysqli_num_rows($query)){
              $all = mysqli_num_rows($query);
              echo '<div class="title">Верх</div>';
              while($r = mysqli_fetch_assoc($query)){
                  if ($r['page'] == 'main'){
                      $pos = '[на главной]';
                  }elseif ($r['page'] == 'other'){
                      $pos = '[на остальных]';
                  } 
                  else{
                      $pos = '[на всех]';
                  }
                                   
                  echo '<div class="c">['. $r['position'] .']<a href="reclame.php?act=edit&amp;id='. $r['id'] .'">'. $r['title'] .'</a>'. ftime($r['time']) . $pos .'&nbsp;<a href="reclame.php?act=delete&amp;id='. $r['id'] .'">[x]</a>';
                  if ($r['position'] > 1 && $r['position'] <= $all){
                      echo '<a href="reclame.php?act=up&amp;id='. $r['id'] .'">[up]</a>';
                  }
                  if ($r['position'] >= 1 && $r['position'] < $all){
                      echo '<a href="reclame.php?act=down&amp;id='. $r['id'] .'">[down]</a>';
                  }
                  echo '</div>';                  
              }              
          }
          
          $query = quer("SELECT * FROM `reclame` WHERE `where` = 'd' ORDER BY `position` ASC");
          
          if (mysqli_num_rows($query)){
              $all = mysqli_num_rows($query);
              echo '<div class="title">Низ</div>';
              while($r = mysqli_fetch_assoc($query)){
                  if ($r['page'] == 'main'){
                      $pos = '[на главной]';
                  }elseif ($r['page'] == 'other'){
                      $pos = '[на остальных]';
                  } 
                  else{
                      $pos = '[на всех]';
                  }
                   
                  
                  echo '<div class="c">['. $r['position'] .']<a href="reclame.php?act=edit&amp;id='. $r['id'] .'">'. $r['title'] .'</a>'. ftime($r['time']) . $pos .'&nbsp;<a href="reclame.php?act=delete&amp;id='. $r['id'] .'">[x]</a>';
                  if ($r['position'] > 1 && $r['position'] <= $all){
                      echo '<a href="reclame.php?act=up&amp;id='. $r['id'] .'">[up]</a>';
                  }
                  if ($r['position'] >= 1 && $r['position'] < $all){
                      echo '<a href="reclame.php?act=down&amp;id='. $r['id'] .'">[down]</a>';
                  }
                  echo '</div>';                  
              }              
          }          
      }
      else{
          echo '<div class="c">Рекламы нет</div>';
      }      
      echo '<div class="c"><a href="reclame.php?act=add">Добавить</a><br />
      <a href="index.php">Админка</a></div>';           
  }

echo '</div></div>'; 
foot();  
?>