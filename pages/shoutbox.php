<div class="col-sm-6">
  <ol class="breadcrumb">
    <li><a href='index.php'>Domov</a></li>
    <li class="active">Stránky</li>
    <li class="active">Archiv Shoutboxu</li>
  </ol>
  <div class='panel panel-primary'>
    <div class='panel-heading'>Archiv Shoutboxu</div>
    <div class='panel-body'>  
    <?php
    if(!EMPTY($_GET["in_page"]) && isset($_GET["in_page"]) && is_numeric($_GET["in_page"])) $page = $_GET["in_page"];
    else $page = 1;
    $msg_counter_q = $db->prepare("SELECT * FROM SHOUTBOX WHERE SHOWED = 1");  
    $msg_counter_q->execute();
    $msg_counter = $msg_counter_q->rowCount();
    if($msg_counter > 0)
    {
      $shouts_on_page = ConfigInfo("SHOUTBOX_PAGE_MSG");  
      $pages = $msg_counter / $shouts_on_page;
      $pages = round_up($pages);
      if($page > $pages) $page = $pages;
      if($page < 0 || $page == 0 || $page == "0") $page = 1;
      if($pages > 0)
      {
        if($page == 1 || $page == "1") $start_prispevku = 0;
        else $start_prispevku = $shouts_on_page * ($page-1);
        $konec_prispevku = $start_prispevku + $shouts_on_page;
        $message_query = $db->prepare("SELECT * FROM SHOUTBOX WHERE SHOUT_ID >= ".$start_prispevku." AND SHOUT_ID < ".$konec_prispevku." ORDER BY SHOUT_ID ASC LIMIT ".$shouts_on_page);
        $message_query->execute();
        while ($message_info = $message_query->fetch(PDO::FETCH_ASSOC))
        {
          $user_info = UserInfo($message_info["USER_ID"]);
          echo "
          <table>
            <tr>
              <td><span class='label label-primary'><a class='group_label' href='index.php?s=profile&user=".$user_info["USER_DISPLAYNAME"]."' style='color:#fff;text-decoration:none;'>".$user_info["USER_DISPLAYNAME"]."</a></span><span class='label label-info'>".ShowTime($message_info["TIME"])."</span></td>
            </tr>
            <tr><td><p class='text-default'>".StrMagic($message_info["MESSAGE"])."</p></td></tr>
          </table>
          ";
        }
        $page_back = $page - 1;
        $page_next = $page + 1;
        echo "<center>";
        echo "<ul class='pagination pagination-sm'>";
        if($page == 1 || $page == "1") echo "<li class='disabled'><a>«</a></li>";
        else echo "<li><a href='?s=shoutbox&in_page=".$page_back."'>«</a></li>";
        for($i = 1;$i < $pages+1;$i++)
        {
          if($i == $page) echo "<li class='active'><a href='?s=shoutbox&in_page=".$i."'>".$i."</a></li>";
          else echo "<li><a href='?s=shoutbox&in_page=".$i."'>".$i."</a></li>"; 
        }
        if($page == $pages) echo "<li class='disabled'><a>»</a></li>";
        else echo "<li><a href='?s=shoutbox&in_page=".$page_next."'>»</a></li>";
        echo "</ul>";
        echo "</center>"; 
      }
    }
    else echo "<div class='alert alert-waring' role='alert'>Nebyla poslána žádná správa do shoutboxu.</div>";                                                         
    ?>
    </div>
  </div>
</div><!--/center-->