<div class="col-sm-6">
  <ol class="breadcrumb">
    <li><a href='index.php'>Domov</a></li>
    <li class="active">Stránky</li>
    <li class="active">Registrace</li>
  </ol>
  <div class='panel panel-primary'>
    <div class='panel-heading'>Registrace</div>
    <div class='panel-body'>
        <form method='post'>
          <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
            <input type="text" class="form-control" placeholder="Uživ. jméno" name='nick'>
          </div>
          <br />
          
          <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
            <input type="password" class="form-control" placeholder="Uživ. heslo" name='pass1'>
          </div>
          <br />
          
          <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
            <input type="password" class="form-control" placeholder="Uživ. heslo znovu" name='pass2'>
          </div>
          <br />
          
          <div class="input-group">
            <span class="input-group-addon">@</span>
            <input type="text" class="form-control" placeholder="Email" name='mail'>
          </div>
          <br />
          
          <div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></span>
            <input type="text" class="form-control" placeholder="Kolik je 5+5?" name='otazka'>
          </div>
          <br />
          <input type="submit" class="btn btn-default" value="Registrovat se" name="REG">
        </form>         
        <br />
        <?php
        if(@$_POST["REG"])
        {
          if(!EMPTY($_POST["nick"]) && !EMPTY($_POST["pass1"]) && !EMPTY($_POST["pass2"]) && !EMPTY($_POST["mail"]) && !EMPTY($_POST["otazka"]))
          {
            if(strlen($_POST["nick"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["nick"]))
            {
              if($_POST["pass1"] == $_POST["pass2"])
              {
                if(filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) 
                {
                  if($_POST["otazka"] == "10")
                  {    
                    $user = $db->prepare("SELECT * FROM USER WHERE USER_NAME = ? LIMIT 1");
                    $user->bindValue(1, $_POST["nick"]);
                    $user->execute();
                    $user_info = $user->fetch(); 
                    if($user_info == 0)
                    {              
                      $new_user = $db->prepare("INSERT INTO `USER`(`USER_ID`, `USER_NAME`, `USER_PASS`, `USER_DISPLAYNAME`, `USER_IP`, `USER_MAIL`, `USER_AVATAR`, `USER_REGD`, `USER_LASTA`, `USER_LEVEL`, `USER_PODPIS`) VALUES (NULL, ?, PASSWORD(?),?,?,?,?,?,?,?,?)");
                      $new_user->bindValue(1, $_POST["nick"]);
                      $new_user->bindValue(2, $_POST["pass1"]);
                      $new_user->bindValue(3, $_POST["nick"]);
                      $new_user->bindValue(4, $_SERVER['REMOTE_ADDR']);
                      $new_user->bindValue(5, $_POST["mail"]);
                      $new_user->bindValue(6, "http://files.domm98.cz/noav.png");
                      $new_user->bindValue(7, time());
                      $new_user->bindValue(8, "0");
                      $new_user->bindValue(9, "0");
                      $new_user->bindValue(10, "Podpis uživatele nebyl zadán.");
                      $new_user->execute();
                      
                      $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_NAME = ? LIMIT 1");
                      $user_query->bindValue(1, $_POST["nick"]);
                      $user_query->execute();  
                      $user_info = $user_query->fetch();
                      
                      $abc1 = range("A", "Z");
                      $abc2 = range("a", "z");
                      $typ = rand(1, 3);
                      $code = NULL;
                      if($typ == 1) $code = $abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9);
                      else if($typ == 2) $code = rand(0,9).rand(0,9).$abc1[rand(0, count($abc1))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].rand(0,9).$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))];
                      else if($typ == 3) $code = $abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].rand(0,9).$abc2[rand(0, count($abc2))].$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))].rand(0,9).rand(0,9).$abc2[rand(0, count($abc2))].$abc1[rand(0, count($abc1))].$abc1[rand(0, count($abc1))];
                     
                      $insert_key = $db->prepare("INSERT INTO `USER_KEYS` (`KEY_ID`, `USER_ID`, `KEY_TYPE`, `KEY_TIME`, `KEY_VALUE`) VALUES (NULL, ?,?,?,?)");
                      $insert_key->bindValue(1, $user_info["USER_ID"]);
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
                      
                      echo "<div class='alert alert-success' role='alert'>Na mail <b>".$_POST["mail"]."</b> byl zaslán aktivační odkaz účtu.</div>";
                    }
                    else echo "<div class='alert alert-danger' role='alert'>Uživatelské jméno je již zabrané.</div>";
                  }
                  else echo "<div class='alert alert-danger' role='alert'>Špatně vypočítaná bezpečnostní otázka.</div>";
                }
                else echo "<div class='alert alert-danger' role='alert'>Nesprávný formát mailové adresy.</div>";
              }
              else echo "<div class='alert alert-danger' role='alert'>Zadaná hesla se neshodují.</div>";
            }
            else echo "<div class='alert alert-danger' role='alert'>V přihlašovacím jméně nesmíš použít speciální znaky, zároveň musí být delší než 2 znaky. (Povolené znaky a-z A-Z 0-9)</div>";
          }
          else echo "<div class='alert alert-danger' role='alert'>Nebyli vyplněny všechny hodnoty.</div>";
        }
        ?>
    </div>
  </div>
</div><!--/center-->