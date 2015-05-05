<?php
if(!EMPTY($_GET["user"]) && !EMPTY($_GET["key"]) && strlen($_GET["user"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_GET["user"]))
{   
  ?>
  <div class="col-sm-6">
  <ol class="breadcrumb">
    <li><a href='index.php'>Domov</a></li>
    <li class="active">Stránky</li>
    <li class="active">Aktivace účtu</li>
  </ol>
  <div class='panel panel-primary'>
    <div class='panel-heading'>Aktivace účtu</div>
    <div class='panel-body'>
  <?php   
  $select_user = $db->prepare("SELECT USER_ID FROM USER WHERE USER_NAME = ? LIMIT 1");
  $select_user->bindValue(1, $_GET["user"]);
  $select_user->execute();
  $user_id = $select_user->fetch();
   
  $is_ok = $db->prepare("SELECT * FROM USER_KEYS WHERE USER_ID = ? AND KEY_VALUE = ? LIMIT 1");
  $is_ok->bindValue(1, $user_id["USER_ID"]);
  $is_ok->bindValue(2, $_GET["key"]); 
  $is_ok->execute();
  $get_data = $is_ok->fetch();
  if($get_data > 0)
  {
    if($get_data["KEY_TYPE"] == "REGISTER")
    {
      if($get_data["KEY_TIME"] + 600 > time())
      {
        $del_key = $db->prepare("DELETE FROM `USER_KEYS` WHERE KEY_ID = ? LIMIT 1");
        $del_key->bindValue(1, $get_data["KEY_ID"]);
        $del_key->execute();
        
        $activate_user = $db->prepare("UPDATE `USER` SET `USER_LEVEL` = ? WHERE USER_ID = ? LIMIT 1");
        $activate_user->bindValue(1, "1");
        $activate_user->bindValue(2, $get_data["USER_ID"]);
        $activate_user->execute();
        echo "<div class='alert alert-success' role='alert'>Účet <b>".$_GET["user"]."</b> byl úspěšně aktivován! Můžete se přihlásit.</div>";
      }
      else echo "<div class='alert alert-danger' role='alert'>Tento klíč již není platný. <a href='index.php?s=new_active_mail&user=".$_GET["user"]."&old_key=".$_GET["key"]."'>Zaslat nový</a></div>";
    }
    else echo "<div class='alert alert-danger' role='alert'>Kombinace klíče a jména nebyla nalezena.</div>";  
  }
  else echo "<div class='alert alert-danger' role='alert'>Kombinace klíče a jména nebyla nalezena.</div>"; 
  ?>
    </div>
  </div>
</div><!--/center-->
  <?php
}
else echo "<meta http-equiv='refresh' content='0;url=index.php'>";
?>