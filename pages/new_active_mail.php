<?php
if(!EMPTY($_GET["user"]) && !EMPTY($_GET["old_key"]) && strlen($_GET["user"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_GET["user"]))
{   
  ?>
  <div class="col-sm-6">
  <ol class="breadcrumb">
    <li><a href='index.php'>Domov</a></li>
    <li class="active">Stránky</li>
    <li class="active">Nový registrační klíč</li>
  </ol>
  <div class='panel panel-primary'>
    <div class='panel-heading'>Nový registrační klíč</div>
    <div class='panel-body'>
  <?php   
  $select_user = $db->prepare("SELECT * FROM USER WHERE USER_NAME = ? LIMIT 1");
  $select_user->bindValue(1, $_GET["user"]);
  $select_user->execute();
  $user_info = $select_user->fetch();
   
  $is_ok = $db->prepare("SELECT * FROM USER_KEYS WHERE USER_ID = ? AND KEY_VALUE = ? LIMIT 1");
  $is_ok->bindValue(1, $user_info["USER_ID"]);
  $is_ok->bindValue(2, $_GET["old_key"]); 
  $is_ok->execute();
  $get_data = $is_ok->fetch();
  if($get_data > 0)
  {
    if($get_data["KEY_TYPE"] == "REGISTER")
    {
      if($get_data["KEY_TIME"] + 600 < time())
      {
        $del_key = $db->prepare("DELETE FROM `USER_KEYS` WHERE KEY_ID = ? LIMIT 1");
        $del_key->bindValue(1, $get_data["KEY_ID"]);
        $del_key->execute();
        
        $abc1 = range("A", "Z");
        $abc2 = range("a", "z");
        $typ = rand(1, 3);
        $code = NULL;
        if($typ == 1) $code = $abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9);
        else if($typ == 2) $code = rand(0,9).rand(0,9).$abc1[rand(0, count($abc1))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))];
        else if($typ == 3) $code = $abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))];
                     
        $insert_key = $db->prepare("INSERT INTO `USER_KEYS` (`KEY_ID`, `USER_ID`, `KEY_TYPE`, `KEY_TIME`, `KEY_VALUE`) VALUES (NULL, ?,?,?,?)");
        $insert_key->bindValue(1, $get_data["USER_ID"]);
        $insert_key->bindValue(2, "REGISTER");
        $insert_key->bindValue(3, time());
        $insert_key->bindValue(4, $code);
        $insert_key->execute(); 
        
        /* EMAIL SETTINGS */
        $user_name = $user_info["USER_DISPLAYNAME"];
        $user_mail = $user_info["USER_MAIL"];
        $from = ConfigInfo("MAIL");
        $from_name = ConfigInfo("NAME");
        $subject = ConfigInfo("NAME")." - Registrace";
                      
        $odkaz = ConfigInfo("URL")."/index.php?s=activate&user=".$user_name."&key=".$code;
                      
        $message = "
        <!DOCTYPE>
          <html>
            <head>
            <meta http-equiv='content-type' content='text/html; charset=utf-8'>
            </head>
            <body>
            Drahý ".$user_name.",<br />
            Pro dokončení registrace na stránkách ".$from_name.", musíš aktivovat svůj účet,</br>
            kliknutím na následující odkaz.<br />
            <b>Pozor, odkaz má omezenou dobu platnosti, použijte jej co nejdříve.</b><br />
            <a href='".$odkaz."' target='_blank'>".$odkaz."</a><br />
            <br />
            Hezký zbytek dne přeje ".$from_name.".<br /> 
            </body>
          </html>  
        ";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: '.$user_name.' <'.$user_mail.'>' . "\r\n";
        $headers .= 'From: '.$from_name.' <'.$from.'>' . "\r\n";
        $headers .= 'Cc: '.$from.'' . "\r\n";
        $headers .= 'Bcc: '.$from.'' . "\r\n";
        mail($user_mail, $subject, $message, $headers);        
        
        echo "<div class='alert alert-success' role='alert'>Byl zaslán nový aktivační klíč na mail <b>".$user_mail."</b>.</div>";
      }
      else echo "<div class='alert alert-danger' role='alert'>Tento klíč je stále platný. <a href='index.php?s=activate&user=".$_GET["user"]."&old_key=".$_GET["key"]."'>Aktivujte účet</a></div>";
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