<?php
if(!EMPTY($_GET["tag"]) && is_numeric($_GET["tag"]))
{
  $count_query = $db->prepare("SELECT * FROM  `TAGS` WHERE TAGS_ID = ? LIMIT 1");
  $count_query->bindValue(1, $_GET["tag"]);
  $count_query->execute();                            
  $count = $count_query->rowCount();  
  if($count > 0)
  {
    ?>
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li><a href='index.php'>Domov</a></li>
        <li class="active">Stránky</li>
        <li><a href='index.php?s=tags'>Tagy</a></li>
        <li class="active"><?php echo TagName($_GET["tag"]);?></li>
      </ol>
      <div class='panel panel-primary'>
        <div class='panel-heading'>Tag - <?php echo TagName($_GET["tag"]);?></div>
        <div class='panel-body'>
        <?php
        $tag_id = TagID(TagName($_GET["tag"]));
        echo TagPopis($tag_id)."<br /><hr />";
        $count_query = $db->prepare("SELECT * FROM  `NEWS` WHERE `NEWS_ALLOW` = 1 ORDER BY `NEWS_ID` DESC");
        $count_query->execute();  
        $count = $count_query->rowCount();
        if($count > 0)
        {
          echo "<h3>Novinky v tomto tagu</h3>";
          $news_query = $db->prepare("SELECT * FROM  `NEWS` WHERE `NEWS_ALLOW` = 1 ORDER BY `NEWS_ID` DESC");
          $news_query->execute();  
          while ($news_info = $news_query->fetch(PDO::FETCH_ASSOC))
          {
            $tagy = explode("#", $news_info["NEWS_TAGS"]);
            $is_in = array_search($tag_id ,$tagy);
            if(!empty($is_in)) echo "<a href='index.php?s=news&new=".$news_info["NEWS_ID"]."'>".$news_info["NEWS_NAME"]."</a><br />";
          }
        }
        else echo "Na webu nejsou žádné novinky, které by mohli mít tag!";
        ?>          
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
        <li><a href='index.php?s=tags'>Tagy</a></li>
        <li class="active">404</li>
      </ol>
      <div class='panel panel-primary'>
        <div class='panel-heading'>Tag - 404</div>
        <div class='panel-body'>
          Tento tag neexistuje.
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
      <li class="active">Tagy</li>
    </ol>
    <div class='panel panel-primary'>
      <div class='panel-heading'>Seznam tagů do novinek</div>
      <div class='panel-body'>
        <table class="table table-bordered">
        <tr><th>Název Tagu</th></tr>
        <?php 
          $count_query = $db->prepare("SELECT * FROM  `TAGS`");
          $count_query->execute();  
          $count = $count_query->rowCount();
          if($count > 0)
          {
            $tag_query = $db->prepare("SELECT * FROM TAGS"); 
            $tag_query->execute();  
            while ($tag_info = $tag_query->fetch(PDO::FETCH_ASSOC)) echo "<tr><td><a class='grou_label' href='index.php?s=tags&tag=".$tag_info["TAGS_ID"]."'>".$tag_info["TAGS_NAME"]."</a></td></tr>";
          }
          else echo "<tr><td rowspan='2'><center>Nejdou vytvořeny žádné tagy!</center></td></tr>";
        ?>
        </table>
      </div>
    </div>    
  </div>
  <?
}
?>