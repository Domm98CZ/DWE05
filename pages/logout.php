<?php if(EMPTY($_SESSION["USER_ID"])) echo "<meta http-equiv='refresh' content='0;url=index.php'>";?>
<div class="col-sm-6">
  <ol class="breadcrumb">
    <li><a href='index.php'>Domov</a></li>
    <li class="active">Stránky</li>
    <li class="active">Odhlášení</li>
  </ol>
  <div class='panel panel-primary'>
    <div class='panel-heading'>Odhlášení</div>
    <div class='panel-body'>
      <?php
      if(!EMPTY($_GET["reason"]))
      {
        if($_GET["reason"] == "bezp") echo "<div class='alert alert-danger' role='alert'>Z bezpečnostních důvodů vás musíme odhlásit, prosím přihlašte se znovu.</div>";
      }
      ?>
      <div class="alert alert-info" role="alert">Odhlašuji uživatele <b><?php echo $_SESSION["USER_DISPLAYNAME"];?></b>. Budete automaticky přesměrováni.</div> 
    </div>
  </div>
</div><!--/center-->
<?php
session_unset($_SESSION);
echo "<meta http-equiv='refresh' content='2;url=index.php'>";
?>