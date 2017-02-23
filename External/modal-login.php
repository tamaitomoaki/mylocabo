<div class="modal fade" id="loginModal2" tabindex="-1">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span>×</span></button>
				<h1 class="modal-title">ログイン</h1>
			</div>
            <div class="modal-body">
                <form name="form1" action="<?php __DIR__;?>/L/login_act.php" method="post" id="loginform">
                    <div class="form-group">
                        <input type="email" class="form-control input-lg" name="email" id="email" placeholder="メールアドレスを記入" size="10" required>
                        <div class="alert-email"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control input-lg" name="password" id="password" placeholder="パスワードを記入">
                        <div class="alert-password"></div>
                    </div>
                    <button type="submit" class="btn btn-success btn-lg btn-block hidden" id="login-btn" value="ログイン"></button>
                    <a class="btn btn-success btn-lg btn-block" role="button" id="dummy-btn">ログイン</a>
                </form>
                <div class="modal-alert"></div>
                <hr>
                <h1>登録はお済みですか？</h1>
                <p>まだ登録されていない方は、<br>新規に登録をしてアカウントを<br>作成しましょう。</p>
                <a href="<?php __DIR__;?>/L/newmenber-r/index.php" class="btn btn-danger btn-lg btn-block">登録する</a>
            </div>
		</div>
	</div>
</div>
