<?php
if(!EMPTY($_GET["user"]) && strlen($_GET["user"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_GET["user"]))
{
  $user_info = UserInfo(UserID($_GET["user"]));
  ?>
  <div class="col-sm-6">
    <ol class="breadcrumb">
      <li><a href='index.php'>Domov</a></li>
      <li class="active">Stránky</li>
      <li><a href='index.php?s=profile'>Profil</a></li>
      <li class="active"><?php echo $_GET["user"];?></li>
    </ol>
    <div class='panel panel-primary'>
      <div class='panel-heading'>Profil uživatele <?php echo $_GET["user"];?></div>
      <div class='panel-body'>
      <!--PANELS IN PANEL-->
        <div class="panel panel-default">
          <div class="panel-heading">Základní informace</div>
          <div class="panel-body">
            <table class="table table-bordered">
            <tr>
                <td style='width:10%;' rowspan="3"><img src="<?php echo $user_info["USER_AVATAR"];?>" alt="Avatar - <?php echo $user_info["USER_DISPLAYNAME"];?>" class="img-thumbnail" style='float:left;margin-right:5px;' width='90px' height='90px'></td>
                <td style='width:45%;' class="active">Zobrazované jméno</td>
                <td style='width:45%;'class="active"><?php echo $user_info["USER_DISPLAYNAME"];?></td>
            </tr>
            <tr>
                <td>Emailová adresa</td>
                <td><?php echo $user_info["USER_MAIL"];?></td>
            </tr>
            <tr>
                <td>Oprávnění</td>
                <td><?php echo ShowUserRights($user_info["USER_ID"]);?></td>
            </tr>
        </table>
          
            
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">Uživatelské skupiny</div>
          <div class="panel-body">
            <table class="table table-bordered">
              <tr>
                <td style='width:20%;'>Primární skupina</td>
                <td style='width:80%;text-align:right;'><?php echo ShowGroup($user_info["USER_GROUP"]);?></td>
              </tr>
              <tr>
                <td style='width:20%;'>Ostatní skupiny</td>
                <td style='width:80%;text-align:right;'><?php echo ShowUserGroups($user_info["USER_ID"]);?></td>
              </tr>
            </table>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">Uživatelský podpis</div>
          <div class="panel-body">
            <?php echo StrMagic($user_info["USER_PODPIS"]);?>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">Statistiky</div>
          <div class="panel-body">
            <ul class="list-group">
              <li class="list-group-item"><span class="badge"><?php echo ShowTime($user_info["USER_REGD"]);?></span> Registrován</li>
              <li class="list-group-item"><span class="badge"><?php echo ShowTime($user_info["USER_LASTA"]);?></span> Poslední aktivita</li>
              <li class="list-group-item"><span class="badge"><?php echo CountUserShouts($user_info["USER_ID"]);?></span> Zpráv v shoutboxu</li>
              <li class="list-group-item"><span class="badge">14</span> Příspěvků na fóru</li>
            </ul>
          </div>
        </div>
      <!--END PANELS IN PANEL-->
      </div>
    </div>
  </div><!--/center-->
  <?php
}
else if(!EMPTY($_SESSION["USER_ID"]))
{
  ?>
  <div class="col-sm-6">
    <ol class="breadcrumb">
      <li><a href='index.php'>Domov</a></li>
      <li class="active">Stránky</li>
      <li class="active">Můj Profil</li>
    </ol>
    <div class='panel panel-primary'>
      <div class='panel-heading'>Můj Profil</div>
      <div class='panel-body'>
      <!--PANELS IN PANEL-->
        <div class="panel panel-default">
          <div class="panel-heading">Základní informace</div>
          <div class="panel-body">
            <table class="table table-bordered">
            <tr>
                <td style='width:10%;' rowspan="3"><img src="<?php echo $_SESSION["USER_AVATAR"];?>" alt="Avatar - <?php echo $_SESSION["USER_DISPLAYNAME"];?>" class="img-thumbnail" style='float:left;margin-right:5px;' width='90px' height='90px'></td>
                <td style='width:45%;' class="active">Zobrazované jméno</td>
                <td style='width:45%;'class="active"><?php echo $_SESSION["USER_DISPLAYNAME"];?></td>
            </tr>
            <tr>
                <td>Emailová adresa</td>
                <td><?php echo $_SESSION["USER_MAIL"];?></td>
            </tr>
            <tr>
                <td>Oprávnění</td>
                <td><?php echo ShowUserRights($_SESSION["USER_ID"]);?></td>
            </tr>
        </table>
          
            
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">Uživatelské skupiny</div>
          <div class="panel-body">
            <table class="table table-bordered">
              <tr>
                <td style='width:20%;'>Primární skupina</td>
                <td style='width:80%;text-align:right;'><?php echo ShowGroup($_SESSION["USER_GROUP"]);?></td>
              </tr>
              <tr>
                <td style='width:20%;'>Ostatní skupiny</td>
                <td style='width:80%;text-align:right;'><?php echo ShowUserGroups($_SESSION["USER_ID"]);?></td>
              </tr>
            </table>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">Uživatelský podpis</div>
          <div class="panel-body">
            <?php echo StrMagic($_SESSION["USER_PODPIS"]);?>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading">Statistiky</div>
          <div class="panel-body">
            <ul class="list-group">
              <li class="list-group-item"><span class="badge"><?php echo ShowTime($_SESSION["USER_REGDATE"]);?></span> Registrován</li>
              <li class="list-group-item"><span class="badge"><?php echo ShowTime($_SESSION["USER_LASTAKTIV"]);?></span> Poslední aktivita</li>
              <li class="list-group-item"><span class="badge"><?php echo CountUserShouts($_SESSION["USER_ID"]);?></span> Zpráv v shoutboxu</li>
              <li class="list-group-item"><span class="badge">14</span> Příspěvků na fóru</li>
            </ul>
          </div>
        </div>
      <!--END PANELS IN PANEL-->
      </div>
    </div>
  </div><!--/center-->
  <?php
}
else echo "<meta http-equiv='refresh' content='0;url=index.php'>";
?>