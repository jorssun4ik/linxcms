<?php
/*
  * Автор двига: Jorssun4ik(LinX)
  * ICQ: 433990
  * E-mail: linxus@inbox.ru
  */

function perm($file) {
            return @ decoct(@ fileperms("$file")) % 1000;
        }
        
if (perm($_SERVER['DOCUMENT_ROOT']."/sys/") == 777){
    echo '<div class="c"><font color="green">Папка sys - ОК</font></div>';
}
else{
    echo '<div class="c"><font color="red">Папка sys - FAILED(поставьте права 777)</font></div>';
} 

?>