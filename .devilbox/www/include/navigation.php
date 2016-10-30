<?php $current = basename($_SERVER['SCRIPT_FILENAME']);?>

<nav class="navbar navbar-full navbar-dark bg-inverse">
	<div class="container">
		<a class="navbar-brand" href="#">
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


			<?php $file = 'databases.php'; $name = 'Databases';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>


			<?php $file = 'mail.php'; $name = 'Mail';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>


			<?php $file = 'phpinfo.php'; $name = 'PHP info';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'mysqlinfo.php'; $name = 'MySQL info';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<?php $file = 'opcache.php'; $name = 'Opcache';?>
			<li class="nav-item <?php echo $file == $current ? 'active' : '';?>">
				<a class="nav-link" href="<?php echo $file == $current ? '#' : '/'.$file;?>"><?php echo $name;?><?php echo $file == $current ? ' <span class="sr-only">(current)</span>' : '';?></a>
			</li>

			<li class="nav-item">
				<a class="nav-link" href="/vendor/phpmyadmin/index.php">phpMyAdmin</a>
			</li>

		</ul>
	</div>
</nav>

<br/>