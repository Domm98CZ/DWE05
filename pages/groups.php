<?php
if(!EMPTY($_GET["group"]) && is_numeric($_GET["group"]))
{
  $count_query = $db->prepare("SELECT * FROM  `GROUPS` WHERE GROUP_ID = ? LIMIT 1");
  $count_query->bindValue(1, $_GET["group"]);
  $count_query->execute();                            
  $count = $count_query->rowCount();  
  if($count > 0)
  {
    ?>
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li><a href='index.php'>Domov</a></li>
        <li class="active">Stránky</li>
        <li><a href='index.php?s=groups'>Skupiny</a></li>
        <li class="active"><?php echo GroupName($_GET["group"]);?></li>
      </ol>
      <div class='panel panel-primary'>
        <div class='panel-heading'>Skupina - <?php echo GroupName($_GET["group"]);?></div>
        <div class='panel-body'>
          <?php echo GroupPopis($_GET["group"]);?>
        </div>
      </div>    
    </div>
    <?php 
  } 
  else
  {
    ?>
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li><a href='index.php'>Domov</a></li>
        <li class="active">Stránky</li>
        <li><a href='index.php?s=groups'>Skupiny</a></li>
        <li class="active">404</li>
      </ol>
      <div class='panel panel-primary'>
        <div class='panel-heading'>Skupina - 404</div>
        <div class='panel-body'>
          Tato skupina neexistuje.
        </div>
      </div>      
    </div>
    <?php
  }
}
else
{
  ?>
  <div class="col-sm-6">
    <ol class="breadcrumb">
      <li><a href='index.php'>Domov</a></li>
      <li class="active">Stránky</li>
      <li class="active">Skupiny</li>
    </ol>
    <div class='panel panel-primary'>
      <div class='panel-heading'>Seznam uživatelských skupin</div>
      <div class='panel-body'>
        <table class="table table-bordered">
        <tr><th>Název skupiny</th></tr>
        <?php 
          $count_query = $db->prepare("SELECT * FROM  `GROUPS`");
          $count_query->execute();  
          $count = $count_query->rowCount();
          if($count > 0)
          {
            $group_query = $db->prepare("SELECT * FROM GROUPS"); 
            $group_query->execute();  
            while ($group_info = $group_query->fetch(PDO::FETCH_ASSOC)) echo "<tr><td><a class='grou_label' href='index.php?s=groups&group=".$group_info["GROUP_ID"]."'>".$group_info["GROUP_NAME"]."</a></td></tr>";
          }
          else echo "<tr><td rowspan='2'><center>Nejdou vytvořeny žádné uživatelské skupiny!</center></td></tr>";
        ?>
        </table>
      </div>
    </div>    
  </div>
  <?
}
?>