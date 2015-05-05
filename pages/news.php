<?php
if(!EMPTY($_GET["new"]) && is_numeric($_GET["new"]))
{
  $count_query = $db->prepare("SELECT * FROM  `NEWS` WHERE NEWS_ID = ? LIMIT 1");
  $count_query->bindValue(1, $_GET["new"]);
  $count_query->execute();                            
  $count = $count_query->rowCount();  
  if($count > 0)
  {
    $news_query = $db->prepare("SELECT * FROM  `NEWS` WHERE `NEWS_ID` = ? LIMIT 1");
    $news_query->bindValue(1, $_GET["new"]);
    $news_query->execute();
    $news_info = $news_query->fetch();  
    $tagy = explode("#", $news_info["NEWS_TAGS"]);
    $tag_str = null;
    for($i = 0; $i < count($tagy); $i++) $tag_str .= "<a class='grou_label' href='index.php?s=tags&tag=".$tagy[$i]."'><span class='label label-default'>".TagName($tagy[$i])."</span></a> ";
    ?>
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li><a href='index.php'>Domov</a></li>
        <li class="active">Stránky</li>
        <li><a href='index.php?s=news'>Novinky</a></li>
        <li class="active"><?php echo $news_info["NEWS_NAME"];?></li>
      </ol>
        <div class='panel panel-primary'>
          <div class='panel-heading'><?php echo $news_info["NEWS_NAME"];?></div>
          <div class='panel-body'>
            <?php echo StrMagic($news_info["NEWS_TEXT"]);?>
            <hr><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Tagy: <?php echo $tag_str;?>
            <br><span class='glyphicon glyphicon-time' aria-hidden='true'></span> Zveřejněno: <span class='label label-info'><?php echo ShowTime($news_info["NEWS_TIME"]);?></span> 
            <br><span class='glyphicon glyphicon-user' aria-hidden='true'></span> Autor: <a class='grou_label' href='index.php?s=profile&user=<?php echo UserName($news_info["USER_ID"]);?>'><span class='label label-primary'><?php echo UserName($news_info["USER_ID"]);?></span></a>
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
        <li><a href='index.php?s=news'>Novinky</a></li>
        <li class="active">404</li>
      </ol>
      <div class='panel panel-primary'>
        <div class='panel-heading'>Novinka - 404</div>
        <div class='panel-body'>
          Tato novinka neexistuje.
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
      <li class="active">Novinky</li>
    </ol>
    <?php
    if(!EMPTY($_GET["in_page"]) && isset($_GET["in_page"]) && is_numeric($_GET["in_page"])) $page = $_GET["in_page"];
    else $page = 1;
    $count_query = $db->prepare("SELECT * FROM  `NEWS` ORDER BY `NEWS_ID` DESC");
    $count_query->execute();  
    $count = $count_query->rowCount();
    if($count > 0)
    {             
      $news_str = array();
      $news_on_page = ConfigInfo("NEWS_PAGE");  
      $pages = $count / $news_on_page;
      $pages = round_up($pages);
      if($page > $pages) $page = $pages;
      if($page < 0 || $page == 0 || $page == "0") $page = 1;
      if($pages > 0)
      {
        if($page == 1 || $page == "1") $start_prispevku = 0;
        else $start_prispevku = $news_on_page * ($page-1);
        $konec_prispevku = $start_prispevku + $news_on_page;
        
        $news_strings = array();
        $news_query = $db->prepare("SELECT * FROM `NEWS` WHERE `NEWS_ALLOW` = 1 ORDER BY `NEWS_ID` DESC");
        $news_query->execute();  
        while ($news_info = $news_query->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_LAST))
        {
          $tagy = explode("#", $news_info["NEWS_TAGS"]);
          $tag_str = null;
          for($i = 0; $i < count($tagy); $i++) $tag_str .= "<a class='grou_label' href='index.php?s=tags&tag=".$tagy[$i]."'><span class='label label-default'>".TagName($tagy[$i])."</span></a> ";
          if(strlen($news_info["NEWS_TEXT"]) > 500) $news_info["NEWS_TEXT"] = substr($news_info["NEWS_TEXT"], 0, 500);  
          $news_strings[] = "
          <div class='panel panel-primary'>
            <div class='panel-heading'>".$news_info["NEWS_NAME"]."</div>
            <div class='panel-body'>
              ".StrMagic($news_info["NEWS_TEXT"])." <br /><a class='grou_label' href='index.php?s=news&new=".$news_info["NEWS_ID"]."'><span class='label label-primary'>Zobrazit více</span></a>
              <hr><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Tagy: ".$tag_str."
              <br><span class='glyphicon glyphicon-time' aria-hidden='true'></span> Zveřejněno: <span class='label label-info'>".ShowTime($news_info["NEWS_TIME"])."</span> 
              <br><span class='glyphicon glyphicon-user' aria-hidden='true'></span> Autor: <a class='grou_label' href='index.php?s=profile&user=".UserName($news_info["USER_ID"])."'><span class='label label-primary'>".UserName($news_info["USER_ID"])."</span></a>
            </div>
          </div>";
        }
        
        for($big_counter = $start_prispevku;$big_counter < $konec_prispevku;$big_counter++) echo $news_strings[$big_counter];
        
        $page_back = $page - 1;
        $page_next = $page + 1;
        echo "<center>";
        echo "<ul class='pagination pagination-sm'>";
        if($page == 1 || $page == "1") echo "<li class='disabled'><a>«</a></li>";
        else echo "<li><a href='?s=news&in_page=".$page_back."'>«</a></li>";
        for($i = 1;$i < $pages+1;$i++)
        {
          if($i == $page) echo "<li class='active'><a href='?s=news&in_page=".$i."'>".$i."</a></li>";
          else echo "<li><a href='?s=news&in_page=".$i."'>".$i."</a></li>"; 
        }
        if($page == $pages) echo "<li class='disabled'><a>»</a></li>";
        else echo "<li><a href='?s=news&in_page=".$page_next."'>»</a></li>";
        echo "</ul>";
        echo "</center>"; 
      }
    } 
    else 
    {
      echo "
      <div class='panel panel-primary'>
        <div class='panel-heading'>Žádné novinky</div>
        <div class='panel-body'>
          V systému nejsou žádné novinky
          <hr><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Tagy: <span class='label label-default'>Web</span>
          <br><span class='glyphicon glyphicon-time' aria-hidden='true'></span> Zveřejněno: <span class='label label-info'>x. x. xxxx xx:xx</span> 
          <br><span class='glyphicon glyphicon-user' aria-hidden='true'></span> Autor: <span class='label label-primary'>Domm</span> 
        </div>
      </div>";
    }
    ?>
  </div><!--/center-->
  <?php
}