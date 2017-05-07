<?php $current = basename($_SERVER['PHP_SELF']);?>

<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="/index.php">
		<img src="/assets/img/logo_30.png" width="30" height="30" class="d-inline-block align-top" alt="">devilbox
	</a>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">

			<?php $file = 'index.php'; $name = 'Home';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'vhosts.php'; $name = 'Virtual Hosts';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'mail.php'; $name = 'Emails';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php
			// ---- Datbases ---- //
			$script = basename($_SERVER['PHP_SELF']);
			$files = array(
				'db_mysql.php' => 'MySQL DB',
				'db_postgres.php' => 'PgSQL DB',
				'db_redis.php' => 'Redis DB',
				'db_memcd.php' => 'Memcached DB'
			);
			$active = (in_array($script, array_keys($files))) ? 'active' : '';
			?>
			<li class="nav-item dropdown <?php echo $active;?>">
				<a class="nav-link dropdown-toggle" href="#" id="supportedContentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Databases</a>
				<div class="dropdown-menu" aria-labelledby="supportedContentDropdown">
					<?php foreach ($files as $href => $name): ?>
						<a class="dropdown-item" href="/<?php echo $href;?>"><?php echo $name;?></a>
					<?php endforeach; ?>
				</div>
			</li>

			<?php
			// ---- Info ---- //
			$script = basename($_SERVER['PHP_SELF']);
			$files = array(
				'info_php.php' => 'PHP Info',
				'info_mysql.php' => 'MySQL Info',
				'info_pgsql.php' => 'PgSQL Info',
				'info_redis.php' => 'Redis Info',
				'info_memcd.php' => 'Memcached Info'
			);
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
			if (strpos(loadClass('Php')->getVersion(), '5.4') !== false) {
				// Support for PHP 5.4
				$phpmyadmin = '4.0';
			} else {
				// works with PHP >= 5.5
				$phpmyadmin = '4.7';
			}
			$script = basename($_SERVER['PHP_SELF']);
			$files = array(
				'vendor/phpmyadmin-'.$phpmyadmin.'/index.php' => 'phpMyAdmin',
				'vendor/adminer-4.3.1/adminer/index.php' => 'Adminer',
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
		<?php $errors =  loadClass('Logger')->countErrors(); ?>
		<div class="form-inline my-2 my-lg-0">Errors: <?php echo $errors; ?></div>
	</div>
</nav>


<br/>
