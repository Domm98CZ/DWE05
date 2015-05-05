<?php
include "_Core/database.php";
include "_Core/functions.php";
session_start();
if(!EMPTY($_SESSION["USER_ID"]))
{
  if($_SERVER['REMOTE_ADDR'] == $_SESSION["USER_IP"]) LoadUser($_SESSION["USER_ID"]);
  else echo "<meta http-equiv='refresh' content='2;url=index.php?s=logout&reason=bezp'>";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title><?php echo ConfigInfo("NAME");?></title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="themes/<?php echo SetTheme();?>/bootstrap.min.css" rel="stylesheet">
    <link href="themes/<?php echo SetTheme();?>/bootstrap.css" rel="stylesheet">
    <link href="themes/<?php echo SetTheme();?>/bootstrap.min.js" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="themes/<?php echo SetTheme();?>/bootstrap.min.js"></script>
    <script src="themes/<?php echo SetTheme();?>/bootstrap.js"></script>

		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="themes/<?php echo SetTheme();?>/styles.css" rel="stylesheet">
	</head>
	<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="navbar-header">
    <a class="navbar-brand" rel="home" href="<?php echo ConfigInfo("URL");?>"><?php echo ConfigInfo("NAME");?></a>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">		
			<?php
      $link_counter = $db->prepare("SELECT * FROM LINKS");
      $link_counter->execute();
      $links = $link_counter->rowCount();
      if($links > 0)
      {
        $link_query = $db->prepare("SELECT * FROM LINKS ORDER BY LINK_ORDER ASC");
        $link_query->execute();
        while ($link_info = $link_query->fetch(PDO::FETCH_ASSOC)) echo "<li><a href='".$link_info["LINK_URL"]."'>".$link_info["LINK_NAME"]."</a></li>";
      }
      else echo "<li><a>Nebyl definován žádný odkaz v menu.</a></li>";
      ?>
		</ul>
	</div>
</nav>

<div class='header'></div>

<div class="container-fluid">
  
  <!--left-->
  <div class="col-sm-3">
    	<div class="panel panel-default">
        <div class="panel-heading">Statistiky</div>
        <div class="panel-body">
        <?php
        $user_count = $db->prepare("SELECT * FROM USER WHERE USER_LEVEL > 0");
        $user_count->execute();
        $users = $user_count->rowCount();
        
        $user_new = $db->prepare("SELECT USER_DISPLAYNAME FROM USER WHERE USER_LEVEL > 0 ORDER BY  `USER`.`USER_ID` DESC ");
        $user_new->execute();
        $user = $user_new->fetch();  
        
        $active_user_count = $db->prepare("SELECT * FROM USER WHERE USER_LASTA > ?");
        $active_user_count->bindValue(1, time()-120);
        $active_user_count->execute();
        $active_user = $active_user_count->rowCount();
        
        $news_count = $db->prepare("SELECT * FROM NEWS WHERE NEWS_ALLOW = 1");
        $news_count->execute();
        $news = $news_count->rowCount();
        ?>
          <ul class="list-group">
            <li class="list-group-item"><span class="badge"><?php echo $users;?></span>Registrovaných uživatelů</li>
            <li class="list-group-item"><span class="badge"><?php echo $active_user;?></span>Aktivních uživatelů</li>
            <li class="list-group-item"><span class="badge"><?php echo "<a class='grou_label' href='index.php?s=profile&user=".$user["USER_DISPLAYNAME"]."' style='color:#fff;'>".$user["USER_DISPLAYNAME"]."</a>";?></span>Nejnovější uživatel</li>
            <li class="list-group-item"><span class="badge"><?php echo $news;?></span>Novinek</li>
          </ul> 
        </div>
      </div>
      
      <?php
      if($_SESSION["USER_LEVEL"] > 0) echo RenderAdminMenu($_SESSION["USER_ID"]);       
      ?>
      
  </div><!--/left-->
  
  <!--center-->
  <?php
  if(!EMPTY($_GET["s"]) && isset($_GET["s"]))
  {
    if(file_exists("pages/".$_GET["s"].".php")) include "pages/".$_GET["s"].".php";
    else include "pages/news.php"; 
  }
  else include "pages/news.php";  
  ?> 

  <!--right-->
  <div class="col-sm-3">     
        <div class="panel panel-default">
        <?php 
        if(EMPTY($_SESSION["USER_ID"]))
        {
        ?>
         	<div class="panel-heading">Přihlášení</div>
         	<div class="panel-body">
            <form method='post'>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                <input type="text" name="user_name" class="form-control" placeholder="Uživ. jméno">
              </div>
              <br />
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
                <input type="password" name='user_pass' class="form-control" placeholder="Uživ. heslo">
              </div>
              <br />
              <input type="submit" class="btn btn-default" name='LOGIN' value="Přihlásit se">
            </form> 
            <br />
            <?php
            if(@$_POST["LOGIN"])
            {
              if(!EMPTY($_POST["user_name"]) && !EMPTY($_POST["user_pass"]))
              {
                if(strlen($_POST["user_name"]) > 2 && preg_match("/^[_a-zA-Z0-9-]+$/", $_POST["user_name"]))
                {
                  $user = $db->prepare("SELECT * FROM USER WHERE USER_NAME = ? AND USER_PASS = PASSWORD(?) LIMIT 1");
                  $user->bindValue(1, $_POST["user_name"]);
                  $user->bindValue(2, $_POST["user_pass"]);
                  $user->execute();
                  $user_info = $user->fetch(); 
                  if($user_info > 0)
                  { 
                    LoadUser($user_info["USER_ID"]);
                    $_SESSION["USER_IP"] = $_SERVER['REMOTE_ADDR'];
                    echo "<div class='alert alert-success' role='alert'>Úspěšně přihlášen na účet <b>".$user_info["USER_NAME"]."</b>.</div>";
                    echo "<meta http-equiv='refresh' content='1;url=index.php'>";
                  }  
                  else echo "<div class='alert alert-danger' role='alert'>Nesprávné jméno nebo heslo.</div>";   
                }
                else echo "<div class='alert alert-danger' role='alert'>Nesprávný formát přihlašovacího jména.</div>";
              }
              else echo "<div class='alert alert-danger' role='alert'>Nevyplnil jsi všechna pole.</div>"; 
            }
            ?>
            <p class="text-muted">
            Nemáš účet? <a href='index.php?s=register'>Zaregistruj se</a>.<br />
            Zapomněl jsi heslo? <a href='index.php?s=password'>Obnov si jej</a>.
            </p>
          </div>
        <?php 
        }
        else if(!EMPTY($_SESSION["USER_ID"]))
        {
          ?>
          <div class="panel-heading">Profil - <?php echo $_SESSION["USER_DISPLAYNAME"];?></div>
         	<div class="panel-body">
          <img src="<?php echo $_SESSION["USER_AVATAR"];?>" alt="Avatar - <?php echo $_SESSION["USER_DISPLAYNAME"];?>" class="img-thumbnail" style='float:left;margin-right:5px;' width='90px' height='90px'>
          <a href="index.php?s=profile">Můj profil</a><br />
          <a href="index.php?s=settings">Nastavení</a><br />
          <a href="index.php?s=messages">Soukromé zprávy <span class="badge"><?php echo CountUserMessages($_SESSION["USER_ID"]);?></span></a><br />             
          <a href='index.php?s=logout'>Odhlásit se</a>
          </div>
          <?php  
        }
        ?>
        </div>
        
        <div class="panel panel-default">
         	<div class="panel-heading">Shoutbox</div>
         	<div class="panel-body">
            <?php if(EMPTY($_SESSION["USER_ID"])) echo '<div class="alert alert-danger" role="alert">Pouze přihlášení uživatelé, mohou psát do shoutboxu.</div>';
            else if(!EMPTY($_SESSION["USER_ID"])) 
            {
              ?>
              <form method='post'>
              <textarea name='shout_message' class="form-control" rows="3" placeholder="Zde můžete napsat svou zprávu do shoutboxu.."></textarea>             
              <input type="submit" class="btn btn-default form-control" name='SEND' value="Odeslat">                 
              </form>
              <br />
              <?php 
              
              if(@$_POST["SEND"])
              {
                if(!EMPTY($_POST["shout_message"]))
                {     
                  $_POST["shout_message"] = strip_tags($_POST["shout_message"]);
                  $_POST["shout_message"] = str_replace('\n', '<br />', $_POST["shout_message"]);
                  $insert_shout = $db->prepare("INSERT INTO `SHOUTBOX`(`SHOUT_ID`, `USER_ID`, `MESSAGE`, `TIME`, `SHOWED`) VALUES (NULL, ?,?,?,?)"); 
                  $insert_shout->bindValue(1, $_SESSION["USER_ID"]);
                  $insert_shout->bindValue(2, $_POST["shout_message"]);
                  $insert_shout->bindValue(3, time());
                  $insert_shout->bindValue(4, "1");
                  $insert_shout->execute();
                  echo "<div class='alert alert-success' role='alert'>Zpráva byla úspěšně odeslána.</div>";
                  echo "<meta http-equiv='refresh' content='1;url=#'>";
                }
                else echo "<div class='alert alert-danger' role='alert'>Nemůžeš odeslat prázdnou zprávu.</div>";
              }
            }
            $message_counter = $db->prepare("SELECT * FROM SHOUTBOX WHERE SHOWED = 1");
            $message_counter->execute();
            $count = $message_counter->rowCount();
            if($count > 0)
            {
              $message_query = $db->prepare("SELECT * FROM SHOUTBOX ORDER BY SHOUT_ID DESC LIMIT ".ConfigInfo("SHOUTBOX_PANEL_MSG"));
              $message_query->execute();
              while ($message_info = $message_query->fetch(PDO::FETCH_ASSOC))
              {
                $user_info = UserInfo($message_info["USER_ID"]);
                ?>
                <img src='<?php echo $user_info["USER_AVATAR"];?>' width='50px' height='50px' alt="Avatar - <?php echo $user_info["USER_DISPLAYNAME"];?>" class="img-thumbnail" style='float:left;margin-right:5px;'>
                <span class='label label-primary'><?php echo "<a class='grou_label' href='index.php?s=profile&user=".$user_info["USER_DISPLAYNAME"]."' style='color:#fff;'>".$user_info["USER_DISPLAYNAME"]."</a>";?></span>
                <span class='label label-info'><?php echo ShowTime($message_info["TIME"]);?></span>
                <p class="text-default"><?php echo StrMagic($message_info["MESSAGE"]);?></p>
                <hr />
                <?php
              }
            }
            else 
            {
            ?>
            <img src='http://files.domm98.cz/loki_infiltrator.jpg' width='50px' height='50px' alt="Avatar - <?php echo $_SESSION["USER_DISPLAYNAME"];?>" class="img-thumbnail" style='float:left;margin-right:5px;'>
            <span class='label label-primary'>Domm</span>
            <span class='label label-info'>xx. xx. xxxx xx:xxx</span>
            <p class="text-info">Nebyla napsána žádná zpráva do shoutboxu.</p>
            <?php
            }
            $msg_counter_q = $db->prepare("SELECT * FROM SHOUTBOX WHERE SHOWED = 1");  
            $msg_counter_q->execute();
            $msg_counter = $msg_counter_q->rowCount();
            $shouts_on_page = ConfigInfo("SHOUTBOX_PAGE_MSG");  
            $pages = $msg_counter / $shouts_on_page;
            $pages = round_up($pages);
            ?>
            <p class="text-muted" style='text-align:center;'><a href='index.php?s=shoutbox&in_page=<?php echo $pages;?>'>Archiv Shoutboxu</a></p> 
          </div>
        </div>
  </div><!--/right-->
  <hr>
  <!--footer-->
  <div class="row">
    <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body">
        <p style='text-align:right;'>
        Powered by <a href='http://dwe.domm98.cz'>Domm's Web Engine</a><br />
        Licence <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/cz/" rel="license">Creative Commons</a><br />
        © <?php echo date("Y")." ".ConfigInfo("NAME");?> 
        </p>
      </div>
    </div>
    </div>
  </div><!--/footer-->
</div><!--/container-fluid-->
	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>