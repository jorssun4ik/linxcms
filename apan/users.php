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
  
  switch ($act = isset($_GET['act']) ? $_GET['act'] : ''){
      case 'edit':
      $set['title'] = 'Администрирование::Редактирование пользователя';
      head();
      echo '<div class="all"><div class="menu"><div class="text">';
      if (isset($_GET['id'])){
          $id = (int)$_GET['id'];
          
          $query = quer("SELECT * FROM `users` WHERE `id` = '{$id}'");
          
          if (mysqli_num_rows($query)){
              $u = mysqli_fetch_assoc($query);
              $a = mysqli_fetch_assoc(quer("SELECT * FROM `anketa` WHERE `u_id` = '{$u['id']}'"));
              
              if (isset($_POST['submit'])){
                  // фильтруем
                  $login = filtr($_POST['login']);
                  $nam = rus_to_lat($login);
                  $email = filtr($_POST['email']);
                  $prav = filtr($_POST['prava']);
                  $name = filtr($_POST['name']);
                  $fname = filtr($_POST['fname']);
                  $sex = filtr($_POST['sex']);
                  $about = filtr($_POST['about']);
                  $icq = filtr($_POST['icq']);
                  $skype = filtr($_POST['skype']);
                  $jabber = filtr($_POST['jabber']);
                  $model = filtr($_POST['model']);
                  $operator = filtr($_POST['operator']);
                  $country = filtr($_POST['country']);
                  $city = filtr($_POST['city']);
                  $inters = filtr($_POST['interes']);
                  $rzan = filtr($_POST['rzan']);
                  $site = filtr($_POST['site']);
                  
                  echo '<div class="c">';
                  
                  $result = mysqli_num_rows(quer("SELECT * FROM `users` WHERE `name` = '{$nam}'"));
                  
                  if (empty($login) || iconv_strlen($login) < 3 || iconv_strlen($login) > 50){
                      echo 'Неразрешимое кол. символов в логине(min 3, max 50)!';                      
                  }
                  elseif (empty($email) || iconv_strlen($email) < 5 || iconv_strlen($mail) > 255){
                      echo 'Неразрешимое кол. символов в e-mail\'е(min 5, max 255)!';
                  }
                  elseif ($prav < 0 || $prav > 9){
                      echo 'Неправильные права!';
                  }
                  elseif ($prava <= $u['prava'] && $u['login'] != LOGIN){
                      echo 'Права данному пользователю менять нельзя!';
                  }
                  elseif ($prava <= $prav && $u['login'] != LOGIN){
                      echo 'Нельзя ставить данному человеку такие же права как и у вас!';
                  }
                  elseif ($sex != 'm' && $sex != 'f'){
                      echo 'Не найден пол';
                  }
                  elseif ($result && $u['name'] != $name){
                      echo 'Такой логин уже зарегистрирован! Попробуйте использовать другой!';
                  }
                  else{
                      if ($u['login'] == LOGIN){
                          $_SESSION['login'] = $login;
                          $_SESSION['pass'] = $u['pass'];                          
                      }
                      quer("UPDATE `users` SET `login` = '{$login}', `name` = '{$nam}', `email` = '{$email}', `prava` = '{$prav}'  WHERE `id` = '{$id}'");
                      quer("UPDATE `anketa` SET `name` = '{$name}',`fname` = '{$fname}',`sex` = '{$sex}', `about` = '{$about}', `icq` = '{$icq}', `skype` = '{$skype}', `jabber` = '{$jabber}', `model` = '{$model}', `operator` = '{$operator}', `country` = '{$country}', `city` = '{$city}', `interes` = '{$interes}', `rzan` = '{$rzan}', `site` = '{$site}' WHERE `u_id` = '{$id}'");
                      
                      echo 'Вы успешно изменили профайл пользователя '. $u['login'];
                  }
                  
                  
                  echo '</div>';
              }
              else{
              echo '<div class="zag">
              '.( $u['id'] == $u_id ? '<b><span style="color:red">ЭТО ВАШ ПРОФАЙЛ</span></b>' : '').'
              <form action="users.php?act=edit&amp;id='. $id .'" method="POST">
              Логин:<br />
              <input name="login" value="'. $u['login'] .'"/><br />
              E-mail:<br />
              <input name="email" value="'. $u['email'] .'"/><br />
              Права:<br />
              <select name="prava">                         
              <option value="0" '. ($u['prava'] == 0 ? 'selected="selected"' : '' ).'>Пользователь</option>
              <option value="6" '. ($u['prava'] == 6 ? 'selected="selected"' : '' ).'>Модератор</option>
              <option value="7" '. ($u['prava'] == 7 ? 'selected="selected"' : '' ).'>С. Модератор</option>
              <option value="8" '. ($u['prava'] == 8 ? 'selected="selected"' : '' ).'>Админ</option>
              <option value="9" '. ($u['prava'] == 9 ? 'selected="selected"' : '' ).'>Создатель сайта</option>
              </select><br />
              Имя:<br />
              <input name="name" value="'. $a['name'] .'"/><br />
              Фамилия:<br />
              <input name="fname" value="'. $a['fname'] .'"/><br />
              Пол:<br />
              <select name="sex">
              <option value="m" '.($a['sex'] == 'm' ? 'selected="selected"' : '').'>Парень</option>
              <option value="f" '.($a['sex'] == 'f' ? 'selected="selected"' : '').'>Девушка</option>
              </select><br />
              Обо мне:<br />
              <textarea name="about">'. $a['about'] .'</textarea><br />
              ICQ: <br />
              <input name="icq" maxlength="10" size="10" value="'. $a['icq'] .'"/><br />
              Skype:<br />
              <input nam="skype" value="'. $a['skype'] .'"/><br />
              Jabber:<br />
              <input nam="jabber" value="'. $a['jabber'] .'"/><br />
              Модель телефона:<br />
              <input nam="model" value="'. $a['model'] .'"/><br />
              Оператор:<br />
              <input nam="operator" value="'. $a['operator'] .'"/><br />
              Страна:<br />
              <input nam="country" value="'. $a['country'] .'"/><br />
              Город:<br />
              <input nam="city" value="'. $a['city'] .'"/><br />
              Интересы:<br />
              <input nam="interes" value="'. $a['interes'] .'"/><br />
              Род Занятий:<br />
              <input nam="rzan" value="'. $a['rzan'] .'"/><br />
              Сайт:<br />
              <input name="site" value="'. $a['site'] .'"/><br />
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
      echo '<div class="c"><a href="users.php">Назад</a><br />
      <a href="index.php">Админка</a></div>';
      break;
      default:
      # Список пользователей
      $set['title'] = 'Администрирование::Редактирование пользователей';
      head();
      echo '<div class="all"><div class="menu"><div class="text">';
      
      $all = mysqli_num_rows(quer("SELECT `id` FROM `users`"));
      
      if ($all){
          
          $query = quer("SELECT * FROM `users` ORDER BY `id` DESC LIMIT {$start},{$num}");
          
          while ($u = mysqli_fetch_assoc($query)){
              echo '<div class="zag"><a href="users.php?act=edit&amp;id='. $u['id'] .'">'. $u['login'] .'</a>'. status($u['prava']) . online($u['id']) .'</div>';
          }          
          nav($all,$num);                    
      }
      else{
          # ну это на крайняк :D
          echo '<div class="c">Пользователей не обнаружено</div>';
      } 
      echo '<div class="c"><a href="index.php">Админка</a></div>';           
  }

echo '</div></div>'; 
foot();  
?>