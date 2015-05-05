<?php
if(!EMPTY($_SESSION["USER_ID"]))
{
  if($_SESSION["USER_LEVEL"] > 0)
  {
    if(EMPTY($_GET["in_page"]) && !isset($_GET["in_page"])) 
    {
    ?>
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li><a href='index.php'>Domov</a></li>
        <li class="active">Stránky</li>
        <li class="active">Administrace</li>
      </ol>
          <?php echo RenderAdminMenu($_SESSION["USER_ID"]); ?>
    </div>
    <?php
    }
    else 
    {
      if($_GET["in_page"] == "main" && $_SESSION["USER_LEVEL"] == 3)
      {
        ?>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li><a href='index.php'>Domov</a></li>
            <li class="active">Stránky</li>
            <li><a href='?s=admin'>Administrace</a></li>
            <li class="active">Hlavní nastavení</li>
          </ol>
          <div class="panel panel-primary">
            <div class="panel-heading">Hlavní nastavení</div>
            <div class="panel-body">
              <form method='post'>
                <table class="table table-bordered">
                  <tr>
                    <td style='width:20%;'>Název Webu</td>
                    <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='web_name' value="<?php echo ConfigInfo("NAME");?>"></td>
                  </tr>
                  <tr>
                    <td style='width:20%;'>URL Adresa Webu</td>
                    <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='web_url' value="<?php echo ConfigInfo("URL");?>"></td>
                  </tr>
                  <tr>
                    <td style='width:20%;'>Mail Stránek</td>
                    <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='web_mail' value="<?php echo ConfigInfo("MAIL");?>"></td>
                  </tr>
                  <tr>
                    <td style='width:20%;'>Design stránek</td>
                    <?php
                    $theme_dir = "themes/";
                    $themes = null;
                    $themes .= "<select class='form-control' name='web_theme'>";
                    if ($dh = opendir($theme_dir)) 
                    {
                      while (($file = readdir($dh)) !== false) 
                      {                           
                        if ($file != "." && $file != ".." && $file != "index.php" && $file != "fonts")
                        {
                          if(file_exists($theme_dir.$file."/bootstrap.css") 
                          && file_exists($theme_dir.$file."/bootstrap.min.css")
                          && file_exists($theme_dir.$file."/bootstrap.min.js") 
                          && file_exists($theme_dir.$file."/styles.css")
                          && file_exists($theme_dir.$file."/index.php")
                          && file_exists($theme_dir.$file."/theme.php"))
                          {
                            if($file == ConfigInfo("THEME")) $themes .= "<option value='".$file."' selected>".$file."</option>";
                            else if($file == "default") $themes .= "<option value='".$file."' style='color:#ff0000;'>".$file."</font></b></option>"; 
                            else $themes .= "<option value='".$file."'>".$file."</option>";
                          }
                        }
                      }
                      closedir($dh);
                    }
                    $themes .= "</select>";
                    ?>
                    <td style='width:80%;text-align:right;'><?php echo $themes;?></td>
                  </tr>
                  <tr>
                    <td style='width:20%;'>Počet novinek na stránku</td>
                    <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='web_news_count' value="<?php echo ConfigInfo("NEWS_PAGE");?>"></td>
                  </tr>
                  <tr>
                    <td style='width:20%;'>Počet zpráv na stránku</td>
                    <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='web_shout_page_count' value="<?php echo ConfigInfo("SHOUTBOX_PAGE_MSG");?>"></td>
                  </tr>
                  <tr>
                    <td style='width:20%;'>Počet zpráv do panelu</td>
                    <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='web_shout_panel_count' value="<?php echo ConfigInfo("SHOUTBOX_PANEL_MSG");?>"></td>
                  </tr>
                  <tr>
                    <td colspan='2'><center><input type='submit' class="btn btn-default" name='save_settings' value='Uložit nastavení'></center></td>
                  </tr>
              </table>
              </form>
              <?php
              if(@$_POST["save_settings"])
              {
                if(!EMPTY($_POST["web_name"]) && !EMPTY($_POST["web_url"]) && !EMPTY($_POST["web_mail"]) && !EMPTY($_POST["web_theme"]) && !EMPTY($_POST["web_news_count"]) && !EMPTY($_POST["web_shout_page_count"]) && !EMPTY($_POST["web_shout_panel_count"]))
                {
                  $web_url_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =1");
                  $web_url_q->bindValue(1, $_POST["web_url"]);
                  $web_url_q->execute();
                  
                  $web_name_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =2");
                  $web_name_q->bindValue(1, $_POST["web_name"]);
                  $web_name_q->execute();
                  
                  $web_mail_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =3");
                  $web_mail_q->bindValue(1, $_POST["web_mail"]);
                  $web_mail_q->execute();
                  
                  $web_theme_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =4");
                  $web_theme_q->bindValue(1, $_POST["web_theme"]);
                  $web_theme_q->execute();
                  
                  $web_nc_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =7");
                  $web_nc_q->bindValue(1, $_POST["web_news_count"]);
                  $web_nc_q->execute();
                  
                  $web_spv_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =5");
                  $web_spv_q->bindValue(1, $_POST["web_shout_panel_count"]);
                  $web_spv_q->execute();
                  
                  $web_spc_q = $db->prepare("UPDATE `CONFIG` SET `VALUE` = ? WHERE `ID` =6");
                  $web_spc_q->bindValue(1, $_POST["web_shout_page_count"]);
                  $web_spc_q->execute();
                  echo "<div class='alert alert-success' role='alert'><b>Úspěch!</b> - Nastavení webu byla úspěšně uložena!</div>";
                  echo "<meta http-equiv='refresh' content='2;url=?s=admin&in_page=main'>";
                }
              }
              ?>
            </div>
          </div>
         </div>
        <?php
      }
    }
  } 
  else
  {
    ?>
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li><a href='index.php'>Domov</a></li>
        <li class="active">Stránky</li>
        <li class="active">Administrace</li>
      </ol>
      <div class='panel panel-primary'>
        <div class='panel-heading'>Administrace</div>
        <div class='panel-body'>  
            <div class='alert alert-warning' role='alert'>K prohlížení této stránky nemáš dostatečná oprávění.</div>
        </div>
      </div>
    </div>
    <?php
  }
}
?>