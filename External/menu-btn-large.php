<!--mypage-->
<?php 

if ( strpos($pagetype, "?") != false ){
    $pagetype = strstr($pagetype, "?", TRUE);
}
?>
<?php if( $pagetype == "/L/mypage/index.php" ||
          $pagetype == "/L/mypage/profile-edit.php" ||
          $pagetype == "/L/mypage/profile-edit.php" ||
          $pagetype == "/L/spot/edit.php" ) : ?>
    <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php __DIR__;?>/L/mypage/index.php">履歴</a></li>
                <li><a href="<?php __DIR__;?>/L/mypage/profile-edit.php">プロフィール</a></li>
                <li><a href="<?php __DIR__;?>/L/logout.php">ログアウト</a></li>
                <li><a href="<?php __DIR__;?>/L/mypage/index.php">マイページ</a></li>
            </ul>
            <a class="btn btn-danger navbar-btn navbar-right" href="<?php __DIR__;?>/L/spot/create.php">マップを作成する</a>
            <div class="navbar-form navbar-left hidden-xs hidden-sm" role="search">
				<div class="form-group">
					<input type="text" class="form-control search-place" placeholder="場所を入力">
				</div>
				<button type="button" class="search-map-button btn btn-default">検索</button>
			</div>

    </div>

<!--それ以外-->
<?php else : ?>

    <!--ログアウト時-->
    <?php if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"]=="") : ?>
    <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="./about.php">マイロカボとは</a></li>
                <li><a class="item menu-login" data-toggle="modal" data-target="#loginModal2">ログイン</a></li>
                <li><a href="<?php __DIR__;?>/L/newmenber-r/index.php">新規登録</a></li>
            </ul>
            <?php if( $pagetype == "/L/index.php" ||
                      $pagetype == "/L/" ||
                      $pagetype == "/L/search/index.php") : ?>
            <!--それ以外-->
            <?php else : ?>
            <div class="navbar-form navbar-left hidden-xs hidden-sm" role="search">
				<div class="form-group">
					<input type="text" class="form-control search-place" placeholder="場所を入力">
				</div>
				<button type="button" class="search-map-button btn btn-default">検索</button>
				
			</div>
            <?php endif; ?>

    </div>
    
    <!--ログイン時-->
    <?php else : ?>
    
    <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="./mypage/index.php">口コミを書く</a></li>
                <li><a href="<?php __DIR__;?>/L/mypage/index.php">マイページ</a></li>
            </ul>
            <a class="btn btn-danger navbar-btn navbar-right" href="<?php __DIR__;?>/L/spot/create.php">マップを作成する</a>
            
            
            <?php if( $pagetype == "/L/index.php" ||
                      $pagetype == "/L/" ||
                      $pagetype == "/L/search/index.php") : ?>
            <!--それ以外-->
            <?php else : ?>
            <div class="navbar-form navbar-left hidden-xs hidden-sm" role="search">
				<div class="form-group">
					<input type="text" class="form-control search-place" placeholder="場所を入力">
				</div>
				<button type="button" class="search-map-button btn btn-default">検索</button>
			</div>
            <?php endif; ?>

    </div>

    <?php endif; ?>

<?php endif; ?>


