<!--mypage-->
<?php if( $pagetype == "/L/mypage/index.php" ||
          $pagetype == "/L/mypage/profile-edit.php" ||
          $pagetype == "/L/mypage/profileimg-edit.php" ) : ?>

    <div id="menu" class="container-fluid hidden">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="menu-close pull-right"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
            </div>
        </nav>


        <a href="<?php __DIR__;?>/L/mypage/index.php"><div class="item"><h2>履歴</h2></div></a>
        <a href="<?php __DIR__;?>/L/mypage/profile-edit.php"><div class="item"><h2>プロフィール</h2></div></a>
        <a href="<?php __DIR__;?>/L/logout.php"><div class="item"><h2>ログアウト</h2></div></a>
        <a href="<?php __DIR__;?>/L/mypage/index.php"><div class="item"><h2>マイページ</h2></div></a>
    </div>

<!--それ以外-->
<?php else : ?>

    <!--ログアウト時-->
    <?php if(isset($_SESSION["chk_ssid"]) != session_id()) : ?>
    <div id="menu" class="container-fluid hidden">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="menu-close pull-right"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
            </div>
        </nav>


        <div class="item menu-login" data-toggle="modal" data-target="#loginModal2"><h2>ログイン</h2></div>
        <a href="<?php __DIR__;?>/L/newmenber-r/index.php"><div class="item"><h2>新規登録</h2></div></a>
        

    </div>
    <!--ログイン時-->
    <?php else : ?>
    <div id="menu" class="container-fluid hidden">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="menu-close pull-right"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
            </div>
        </nav>


        <a href="./mypage/index.php"><div class="item"><h2>口コミを書く</h2></div></a>
        <a href="<?php __DIR__;?>/L/mypage/index.php"><div class="item"><h2>マイページ</h2></div></a>
    </div>

    <?php endif; ?>

<?php endif; ?>

