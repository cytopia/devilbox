<?php require '../config.php'; ?>
<?php

// If login protection is disabled or user has already logged in
if (loadClass('Helper')->isloggedIn()) {
	loadClass('Helper')->redirect('/');
}

// Validate $_POST login

$login_error = '';

if (isset($_POST['username']) && isset($_POST['password'])) {

	// Auth successful
	if (loadClass('Helper')->login($_POST['username'], $_POST['password'])) {
		loadClass('Helper')->redirect('/');
	}
	$login_error = 'Wrong username or password';

}

?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;min-height: 100%;">
	<head>
		<?php echo loadClass('Html')->getHead(true); ?>
	</head>

	<body style="height: 100%; min-height: 100%; text-align: center; color: #fff; text-shadow: 0 .05rem .1rem rgba(0,0,0,.5); background: #1f1f1f; margin-bottom:0 !important;">

		<div class="site-wrapper">
			<div class="site-wrapper-inner">
				<div class="cover-container">
					<div class="container">
						<div class="inner cover">

							<img src="/assets/img/banner.png" style="width:60%; padding-bottom:20px;"/>
							<h1 class="cover-heading">Devilbox Login</h1>

							<div class="text-danger"><?php echo $login_error; ?></div>
							<form method="POST">
								<div class="form-group row">
									<div class="col-sm-12">
										<input type="text" class="form-control" id="inputUsername" placeholder="Username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<input type="password" class="form-control" id="inputPassword" placeholder="Password" name="password">
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-12">
										<button type="submit" class="btn btn-primary">Sign in</button>
									</div>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
