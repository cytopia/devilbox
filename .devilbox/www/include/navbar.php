<?php $current = basename($_SERVER['SCRIPT_FILENAME']);?>

<nav class="navbar navbar-full navbar-dark bg-inverse">
	<div class="container">
		<a class="navbar-brand" href="/index.php">
			<img src="/assets/img/logo_30.png" width="30" height="30" class="d-inline-block align-top" alt="">
			devilbox
		</a>
		<ul class="nav navbar-nav">

			<?php $file = 'index.php'; $name = 'Home';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'vhosts.php'; $name = 'Virtual Hosts';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'db_mysql.php'; $name = 'MySQL DB';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'db_postgres.php'; $name = 'PostgreSQL DB';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php if (loadClass('Docker')->getEnv('COMPOSE_OPTIONAL') == 1): ?>
				<?php $file = 'db_redis.php'; $name = 'Redis DB';?>
				<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
					<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
				</li>
			<?php endif; ?>


			<?php $file = 'mail.php'; $name = 'Emails';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php
			// ---- Info ---- //
			$script = $_SERVER['SCRIPT_NAME'];
			$files = array(
				'phpinfo.php' => 'PHP info',
				'mysqlinfo.php' => 'MySQL info',
				'postgresinfo.php' => 'PostgreSQL info'
			);
			if (loadClass('Docker')->getEnv('COMPOSE_OPTIONAL') == 1) {
				$files['redisinfo.php'] = 'Redis info';
			}
			$active = (in_array($script, array_keys($files))) ? 'active' : '';
			?>
			<li class="nav-item dropdown <?php echo $active;?>">
				<a class="nav-link dropdown-toggle" href="#" id="supportedContentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Info</a>
				<div class="dropdown-menu" aria-labelledby="supportedContentDropdown">
					<?php foreach ($files as $href => $name): ?>
						<a class="dropdown-item" href="/<?php echo $href;?>"><?php echo $name;?></a>
					<?php endforeach; ?>
				</div>
			</li>

			<?php
			// ---- Tools ---- //
			if (strpos(loadClass('Docker')->PHP_version(), 'PHP 5.4') !== false) {
				// Support for PHP 5.4
				$phpmyadmin = '4.0';
			} else {
				// works with PHP >= 5.5
				$phpmyadmin = '4.7';
			}
			$files = array(
				'vendor/phpmyadmin-'.$phpmyadmin.'/index.php' => 'phpMyAdmin',
				'vendor/adminer/adminer/index.php' => 'Adminer',
				'opcache.php' => 'Opcache GUI'
			);
			$active = (in_array($script, array_keys($files))) ? 'active' : '';
			?>
			<li class="nav-item dropdown <?php echo $active;?>">
				<a class="nav-link dropdown-toggle" href="#" id="supportedContentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tools</a>
				<div class="dropdown-menu" aria-labelledby="supportedContentDropdown">
					<?php foreach ($files as $href => $name): ?>
					<a class="dropdown-item" href="/<?php echo $href;?>"><?php echo $name;?></a>
					<?php endforeach; ?>
				</div>
			</li>

		</ul>
	</div>
</nav>

<br/>
