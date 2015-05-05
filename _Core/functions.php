<?php
function ConfigInfo($config)
{
  $db = $GLOBALS["db"];
  $config_query = $db->prepare("SELECT * FROM `CONFIG` WHERE OPT = ? LIMIT 1");
  $config_query->bindValue(1, $config);
  $config_query->execute();  
  $config_value = $config_query->fetch();
  $config_query = NULL;
  return $config_value["VALUE"];
}

function SetTheme()
{
  $value = null;
  $db = $GLOBALS["db"];
  $config_query = $db->prepare("SELECT `VALUE` FROM `CONFIG` WHERE `OPT` = 'THEME' LIMIT 1");
  $config_query->execute();  
  $config_value = $config_query->fetch();
  $config_query = NULL;
  if(file_exists("themes/".$config_value["VALUE"]."/bootstrap.css") 
  && file_exists("themes/".$config_value["VALUE"]."/bootstrap.min.css")
  && file_exists("themes/".$config_value["VALUE"]."/bootstrap.min.js") 
  && file_exists("themes/".$config_value["VALUE"]."/styles.css")
  && file_exists("themes/".$config_value["VALUE"]."/index.php")
  && file_exists("themes/".$config_value["VALUE"]."/theme.php"))
  {
    $value = $config_value["VALUE"];
  } 
  else $value = "default";
  return $value; 
}

function RenderAdminMenu($user_id)
{
  $string = null;
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  if($user_info["USER_LEVEL"] > 0)
  {
    $string .= "<div class='list-group'>";
    $string .= "<a class='list-group-item active'>Menu Administrace</a>";
    if($user_info["USER_LEVEL"] == 3 || $user_info["USER_LEVEL"] == 2 || $user_info["USER_LEVEL"] == 1) $string .= "<a href='?s=admin&in_page=user' class='list-group-item'><span class='glyphicon glyphicon-user' aria-hidden='true'></span> Uživatelé</a>";
    if($user_info["USER_LEVEL"] == 3 || $user_info["USER_LEVEL"] == 2 || $user_info["USER_LEVEL"] == 1) $string .= "<a href='?s=admin&in_page=news' class='list-group-item'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span> Novinky</a>";
    if($user_info["USER_LEVEL"] == 3 || $user_info["USER_LEVEL"] == 2 || $user_info["USER_LEVEL"] == 1) $string .= "<a href='?s=admin&in_page=shoutbox' class='list-group-item'><span class='glyphicon glyphicon-comment' aria-hidden='true'></span> Shoutbox</a>";
    if($user_info["USER_LEVEL"] == 3 || $user_info["USER_LEVEL"] == 2) $string .= "<a href='?s=admin&in_page=menu' class='list-group-item'><span class='glyphicon glyphicon-list' aria-hidden='true'></span> Menu</a>";
    if($user_info["USER_LEVEL"] == 3 || $user_info["USER_LEVEL"] == 2) $string .= "<a href='?s=admin&in_page=tags' class='list-group-item'><span class='glyphicon glyphicon-asterisk' aria-hidden='true'></span> Tagy</a>";
    if($user_info["USER_LEVEL"] == 3 || $user_info["USER_LEVEL"] == 2) $string .= "<a href='?s=admin&in_page=groups' class='list-group-item'><span class='glyphicon glyphicon-link' aria-hidden='true'></span> Skupiny</a>";
    if($user_info["USER_LEVEL"] == 3) $string .= "<a href='?s=admin&in_page=main' class='list-group-item'><span class='glyphicon glyphicon-cog' aria-hidden='true'></span> Hlavní nastavení</a>";
    if($user_info["USER_LEVEL"] == 3) $string .= "<a href='?s=admin&in_page=update' class='list-group-item'><span class='glyphicon glyphicon-refresh' aria-hidden='true'></span> Aktualizace</a>";
    $string .= "</div>";
  }
  return $string;
}

function UserInfo($user_id)
{
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  $user_query = NULL;
  return $user_info;
}

function UserID($user_name)
{
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT USER_ID FROM `USER` WHERE USER_DISPLAYNAME = ? LIMIT 1");
  $user_query->bindValue(1, $user_name);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  $user_query = NULL;
  return $user_info["USER_ID"];
}

function UserName($user_id)
{
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT USER_DISPLAYNAME FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  $user_query = NULL;
  return $user_info["USER_DISPLAYNAME"];
}

function LoadUser($user_id)
{
  $db = $GLOBALS["db"];
  $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  
  $update = $db->prepare("UPDATE `USER` SET `USER_LASTA`= ?, `USER_IP`= ? WHERE USER_ID = ? LIMIT 1");
  $update->bindValue(1, time());
  $update->bindValue(2, $_SERVER['REMOTE_ADDR']);
  $update->bindValue(3, $user_info["USER_ID"]);
  $update->execute();
  
  $_SESSION["USER_ID"]            = $user_info["USER_ID"];
  $_SESSION["USER_NAME"]          = $user_info["USER_NAME"];
  $_SESSION["USER_PASS"]          = $user_info["USER_PASS"];
  $_SESSION["USER_DISPLAYNAME"]   = $user_info["USER_DISPLAYNAME"];
  $_SESSION["USER_IP"]            = $user_info["USER_IP"];
  $_SESSION["USER_MAIL"]          = $user_info["USER_MAIL"];
  $_SESSION["USER_AVATAR"]        = $user_info["USER_AVATAR"];
  $_SESSION["USER_REGD"]          = $user_info["USER_REGD"];
  $_SESSION["USER_LASTA"]         = $user_info["USER_LASTA"];
  $_SESSION["USER_LEVEL"]         = $user_info["USER_LEVEL"];
  $_SESSION["USER_PODPIS"]        = $user_info["USER_PODPIS"];
  $_SESSION["USER_GROUP"]         = $user_info["USER_GROUP"];
  $_SESSION["USER_GROUPS"]        = $user_info["USER_GROUPS"];
  /* IP */
  $_SESSION["USER_IP"]            = $_SERVER['REMOTE_ADDR'];
}

function CountUserMessages($user_id)
{
  $db = $GLOBALS["db"];
  $message_count = NULL;
  $message_count_query = $db->prepare("SELECT * FROM `MESSAGES` WHERE `USER2_ID` = ? AND `SHOWED` = 0 AND `DELETED2` = 0");
  $message_count_query->bindValue(1, $user_id);
  $message_count_query->execute();
  $message_count = $message_count_query->rowCount();
  return $message_count;
}

function ShowTime($time)
{
  return date("d. m. Y H:i", $time);
}

function TagName($tag_id)
{
  $db = $GLOBALS["db"];
  $tag_query = $db->prepare("SELECT TAGS_NAME FROM `TAGS` WHERE `TAGS_ID` = ? LIMIT 1");
  $tag_query->bindValue(1, $tag_id);
  $tag_query->execute();  
  $tag_info = $tag_query->fetch();
  $tag_query = null;
  return $tag_info["TAGS_NAME"];
}

function TagPopis($tag_id)
{
  $db = $GLOBALS["db"];
  $tag_query = $db->prepare("SELECT TAGS_POPIS FROM `TAGS` WHERE `TAGS_ID` = ? LIMIT 1");
  $tag_query->bindValue(1, $tag_id);
  $tag_query->execute();  
  $tag_info = $tag_query->fetch();
  $tag_query = null;
  return $tag_info["TAGS_POPIS"];
}

function TagID($tag_name)
{
  $db = $GLOBALS["db"];
  $tag_query = $db->prepare("SELECT TAGS_ID FROM `TAGS` WHERE `TAGS_NAME` = ? LIMIT 1");
  $tag_query->bindValue(1, $tag_name);
  $tag_query->execute();  
  $tag_info = $tag_query->fetch();
  $tag_query = null;
  return $tag_info["TAGS_ID"];
}

function ShowUserRights($user_id)
{   
  $db = $GLOBALS["db"];
  $str = NULL;
  $user_query = $db->prepare("SELECT USER_LEVEL FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  if($user_info["USER_LEVEL"] == 0) $str = "Uživatel";
  if($user_info["USER_LEVEL"] == 1) $str = "Moderátor";
  if($user_info["USER_LEVEL"] == 2) $str = "Administrátor";
  if($user_info["USER_LEVEL"] == 3) $str = "Majitel stránek";
  if($user_info["USER_LEVEL"] == 4) $str = "Zablokovaný";
  return $str;
}

function GroupInfo($group_id)
{
  $db = $GLOBALS["db"];
  $group_query = $db->prepare("SELECT * FROM `GROUPS` WHERE `GROUP_ID` = ? LIMIT 1");
  $group_query->bindValue(1, $group_id);
  $group_query->execute();
  $group_info = $group_query->fetch();
  return $group_info; 
}

function GroupName($group_id)
{
  $db = $GLOBALS["db"];
  $group_query = $db->prepare("SELECT GROUP_NAME FROM `GROUPS` WHERE `GROUP_ID` = ? LIMIT 1");
  $group_query->bindValue(1, $group_id);
  $group_query->execute();
  $group_info = $group_query->fetch();
  return $group_info["GROUP_NAME"];   
}

function GroupPopis($group_id)
{
  $db = $GLOBALS["db"];
  $group_query = $db->prepare("SELECT GROUP_POPIS FROM `GROUPS` WHERE `GROUP_ID` = ? LIMIT 1");
  $group_query->bindValue(1, $group_id);
  $group_query->execute();
  $group_info = $group_query->fetch();
  return $group_info["GROUP_POPIS"];   
}

function ShowGroup($group_id)
{
  $db = $GLOBALS["db"];
  $str = NULL;
  $color = NULL;
  $group_query = $db->prepare("SELECT * FROM `GROUPS` WHERE `GROUP_ID` = ? LIMIT 1");
  $group_query->bindValue(1, $group_id);
  $group_query->execute();
  $group_info = $group_query->fetch();
  if($group_info["GROUP_COLOR"] == 0) $color = "label-default";
  else if($group_info["GROUP_COLOR"] == 1) $color = "label-primary";
  else if($group_info["GROUP_COLOR"] == 2) $color = "label-success";
  else if($group_info["GROUP_COLOR"] == 3) $color = "label-info";
  else if($group_info["GROUP_COLOR"] == 4) $color = "label-warning";
  else if($group_info["GROUP_COLOR"] == 5) $color = "label-danger";
  else $color = "label-default";
  $str = "<a class='group_label' style='text-decoration:none;' href='index.php?s=groups&group=".$group_info["GROUP_ID"]."'><span class='label ".$color."'>".$group_info["GROUP_NAME"]."</span></a>";
  return $str;
}

function ShowUserGroups($user_id)
{
  $db = $GLOBALS["db"];
  $str = NULL;
  
  $user_query = $db->prepare("SELECT * FROM `USER` WHERE USER_ID = ? LIMIT 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $user_info = $user_query->fetch();
  
  $groups_id = explode("#", $user_info["USER_GROUPS"]);
  for($i = 0; $i < count($groups_id); $i++) 
  {
    if($groups_id[$i] != $user_info["USER_GROUP"]) 
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
      $this_group = "<a class='group_label' style='text-decoration:none;' href='index.php?s=groups&group=".$group_info["GROUP_ID"]."'><span class='label ".$color."'>".$group_info["GROUP_NAME"]."</span></a>"; 
      $str .= $this_group." "; 
    }
  }
  if(count($groups_id) == 1 && $groups_id[0] == $user_info["USER_GROUP"]) $str = "Tento uživatel není členem žádné další skupiny."; 
  return $str;
}

function StrMagic($str)
{
  $str = preg_replace("#(^|[\n\s>])([\w]+?://[^\s\"\n\r\t<]*)#is", "\\1<a target='_blank' href=\"\\2\">\\2</a>", $str);
  $str = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<a href=\"mailto:$1\">$1</a>", $str);
  $str = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<div class='embed-responsive embed-responsive-16by9'><iframe class='embed-responsive-item' title='YouTube Player' src='http://www.youtube.com/embed/$1?autoplay=0' frameborder='0' allowfullscreen></iframe></div>",$str);
  $str = str_replace('[br]', '<br />', $str);
  $str = preg_replace('#\[b\](.*?)\[/b\]#si', '<strong>\1</strong>', $str);
  $str = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $str);
  $str = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $str);
  $str = preg_replace('#\[bcolor=(black|blue|brown|cyan|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/bcolor\]#si', '<span style=\'background-color:\1;padding:2px\'>\2</span>', $str);
  $str = preg_replace('#\[bcolor=([\#a-f0-9]*?)\](.*?)\[/bcolor\]#si', '<span style=\'background-color:\1;padding:2px\'>\2</span>', $str);
  $str = preg_replace('#\[color=(black|blue|brown|cyan|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $str);
  $str = preg_replace('#\[color=([\#a-f0-9]*?)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $str);
  $str = preg_replace('#\[center\](.*?)\[/center\]#si', '<div style=\'text-align:center\'>\1</div>', $str);
  $str = preg_replace('#\[big\](.*?)\[/big\]#si', '<span style=\'font-size:18px\'>\1</span>', $str);
  $str = preg_replace('#\[small\](.*?)\[/small\]#si', '<span style=\'font-size:8px\'>\1</span>', $str);
  $str = preg_replace('#\[left\](.*?)\[/left\]#si', '<div style=\'text-align:left\'>\1</div>', $str);
  $str = preg_replace('#\[right\](.*?)\[/right\]#si', '<div style=\'text-align:right\'>\1</div>', $str);
  return $str;
}

function CountUserShouts($user_id)
{
  $db = $GLOBALS["db"];
  $shouts = NULL;
  $user_query = $db->prepare("SELECT * FROM `SHOUTBOX` WHERE USER_ID = ? AND SHOWED = 1");
  $user_query->bindValue(1, $user_id);
  $user_query->execute();  
  $shouts = $user_query->rowCount();
  return $shouts;
}

function round_up($value, $places=0) 
{
  $mult = pow(10, abs($places)); 
  return $places < 0 ?
  ceil($value / $mult) * $mult :
  ceil($value * $mult) / $mult;
}

?>