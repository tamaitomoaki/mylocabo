<!--ログアウト時-->
<?php if(isset($_SESSION["chk_ssid"]) != session_id()) : ?>
<div id="menu-btn" class="menu-nav-button btn-group pull-right visible-xs-block" role="group">
    <a class="btn btn-default" role="button">
    <span class="menu-bar"></span>
    <span class="menu-bar"></span>
    <span class="menu-bar"></span>メニュー</a>
</div>

<!--ログイン時-->
<?php else : ?>
<div id="menu-btn" class="menu-nav-button btn-group pull-right visible-xs-block" role="group">
    <a class="btn btn-default" role="button">
    <span class="menu-bar"></span>
    <span class="menu-bar"></span>
    <span class="menu-bar"></span>メニュー</a>
</div>
<a class="btn btn-danger navbar-btn visible-xs-inline-block" href="<?php __DIR__;?>/L/spot/create.php">マップ作成</a>
<?php endif; ?>

