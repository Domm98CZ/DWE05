<div class="col-sm-6">
  <ol class="breadcrumb">
    <li><a href='index.php'>Domov</a></li>
    <li class="active">Stránky</li>
    <li class="active">Nastavení účtu</li>
  </ol>
  <div class='panel panel-primary'>
    <div class='panel-heading'>Nastavení účtu</div>
    <div class='panel-body'>  
    <?php
    if(!EMPTY($_SESSION["USER_ID"]))
    {
      ?>
      <form method='post'>
        <table class="table table-bordered">
        <tr>
          <td style='width:20%;'>Účet</td>
          <td style='width:80%;text-align:right;'><input type='text' class="form-control" value='<?php echo $_SESSION["USER_NAME"];?>' readonly></td>
        </tr>
        <tr>
          <td style='width:20%;'>Aktuální Avatar</td>
          <td style='width:80%;text-align:right;'><img src="<?php echo $_SESSION["USER_AVATAR"];?>" alt="Avatar - <?php echo $_SESSION["USER_DISPLAYNAME"];?>" class="img-thumbnail" style='float:left;margin-right:5px;' width='64px' height='64px'></td>
        </tr>    
        <tr>
          <td style='width:20%;'>Aktuální Heslo</td>
          <td style='width:80%;text-align:right;'><input type='password' class="form-control" name='pass'><font color='#ff0000'>*Heslo je potřeba k úpravě jakých koliv informací.</font></td>
        </tr>
        <tr>
          <td style='width:20%;'>Nové heslo</td>
          <td style='width:80%;text-align:right;'><input type='password' class="form-control" name='pass2'><font color='#ff0000'></font></td>
        </tr>
        <tr>
          <td style='width:20%;'>Nové heslo</td>
          <td style='width:80%;text-align:right;'><input type='password' class="form-control" name='pass3'><font color='#ff0000'>*Nové heslo je potřeba napsat 2x, kdyby se náhodou v hesle vyskytla chyba.</font></td>
        </tr> 
        <tr>
          <td style='width:20%;'>E-Mail</td>
          <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='mail' value='<?php echo $_SESSION["USER_MAIL"];?>'></td>
        </tr>      
        <tr>
          <td style='width:20%;'>Zobrazované jméno</td>
          <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='display_name' value='<?php echo $_SESSION["USER_DISPLAYNAME"];?>'></td>
        </tr>        
        <tr>
          <td style='width:20%;'>Avatar</td>
          <td style='width:80%;text-align:right;'><input type='text' class="form-control" name='avatar' value='<?php echo $_SESSION["USER_AVATAR"];?>'></td>
        </tr>
        <tr>
          <td style='width:20%;'>Primární skupina</td>
          <?php
          $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
          $user_query->bindValue(1, $_SESSION["USER_ID"]);
          $user_query->execute();  
          $user_info = $user_query->fetch();
          
          $skupiny = null;
          $skupiny .= "<select name='groups' class='form-control'>";
        
          $groups_id = explode("#", $user_info["USER_GROUPS"]);
          for($i = 0; $i < count($groups_id); $i++) 
          {
            $group_query = $db->prepare("SELECT * FROM `GROUPS` WHERE `GROUP_ID` = ? LIMIT 1");
            $group_query->bindValue(1, $groups_id[$i]);
            $group_query->execute();
            $group_info = $group_query->fetch();
            if($group_info["GROUP_COLOR"] == 0) $color = "label-default";
            else if($group_info["GROUP_COLOR"] == 1) $color = "label-primary";
            else if($group_info["GROUP_COLOR"] == 2) $color = "label-success";
            else if($group_info["GROUP_COLOR"] == 3) $color = "label-info";
            else if($group_info["GROUP_COLOR"] == 4) $color = "label-warning";
            else if($group_info["GROUP_COLOR"] == 5) $color = "label-danger";
            else $color = "label-default";
            if($groups_id[$i] == $user_info["USER_GROUP"]) $skupiny .= "<option value='".$group_info["GROUP_ID"]."' selected>".$group_info["GROUP_NAME"]."</option>";  
            else $skupiny .= "<option value='".$group_info["GROUP_ID"]."'>".$group_info["GROUP_NAME"]."</option>";  
          }
          $skupiny .= "</select>";
          if(count($groups_id) == 1 && $groups_id[0] == $user_info["USER_GROUP"]) $skupiny = "Nepatříš do žádné skupiny."; 
          ?>
          <td style='width:80%;text-align:right;'><?php echo $skupiny;?></td>
        </tr>
        <tr>
          <td style='width:20%;'>Popis</td>
          <td style='width:80%;text-align:right;'><textarea class="form-control" name='popis'><?php echo $_SESSION["USER_PODPIS"];?></textarea></td>
        </tr>
        <tr>
          <td colspan='2'><center><input type='submit' class="btn btn-default" name='save_settings' value='Uložit nastavení'></center></td>
        </tr>
        </table>
      </form>
      <?php
    }
    else echo "<div class='alert alert-waring' role='alert'>Musíš být přihlášen.</div>";   
    ?>
    </div>
  </div>
</div>