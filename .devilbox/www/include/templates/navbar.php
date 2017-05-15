<?php
$menu = array(
	array(
		array(
			'name' => 'Home',
			'path' => '/index.php'
		),
		array(
			'name' => 'Virtual Hosts',
			'path' => '/vhosts.php'
		),
		array(
			'name' => 'Emails',
			'path' => '/mail.php'
		)
	),
	array(
		'name' => 'Databases',
		'menu' => array(
			array(
				'name' => 'MySQL DB',
				'path' => '/db_mysql.php'
			),
			array(
				'name' => 'PgSQL DB',
				'path' => '/db_pgsql.php'
			),
			array(
				'name' => 'Redis DB',
				'path' => '/db_redis.php'
			),
			array(
				'name' => 'Memcached DB',
				'path' => '/db_memcd.php'
			)
		)
	),
	array(
		'name' => 'Info',
		'menu' => array(
			array(
				'name' => 'PHP Info',
				'path' => '/info_php.php'
			),
			array(
				'name' => 'MySQL Info',
				'path' => '/info_mysql.php'
			),
			array(
				'name' => 'PgSQL Info',
				'path' => '/info_pgsql.php'
			),
			array(
				'name' => 'Redis Info',
				'path' => '/info_redis.php'
			),
			array(
				'name' => 'Memcached Info',
				'path' => '/info_memcd.php'
			)
		)
	),
	array(
		'name' => 'Tools',
		'menu' => array(
			array(
				'name' => 'phpMyAdmin',
				'path' => (strpos(loadClass('Php')->getVersion(), '5.4') !== false) ? '/vendor/phpmyadmin-4.0/index.php' : '/vendor/phpmyadmin-4.7/index.php',
				'target' => '_blank'
			),
			array(
				'name' => 'Adminer',
				'path' => '/vendor/adminer-4.3.1/adminer/index.php'
			),
			array(
				'name' => 'Opcache GUI',
				'path' => '/opcache.php'
			)
		)
	)
);

/**
 * Get Navigation menu
 * @param  mixed[] $menu Menu Array
 * @return string
 */
function get_menu($menu) {

	$path = $_SERVER['PHP_SELF'];
	$html = '';

	foreach ($menu as $type => $elements) {

		if (!isset($elements['menu'])) {

			foreach ($elements as $el) {
				if ($path == $el['path']) {
					$class = 'active';
					$span = '<span class="sr-only">(current)</span>';
				} else {
					$class = '';
					$span = '';
				}

				$html .= '<li class="nav-item '.$class.'">';
				$html .= 	'<a class="nav-link" href="'.$el['path'].'">'.$el['name'].' '.$span.'</a>';
				$html .= '</li>';
			}

		} else {
			$name = $elements['name'];
			$class = '';
			$id	= md5($name);


			// Make submenu active
			foreach ($elements['menu'] as $el) {
				if (strpos($path, $el['path']) !== false) {
					$class = 'active';
					break;
				}
			}

			$html .= '<li class="nav-item dropdown '.$class.'">';
			$html .=	'<a class="nav-link dropdown-toggle" href="#" id="'.$id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
			$html .=    	$name;
			$html .=	'</a>';
			$html .=	'<div class="dropdown-menu" aria-labelledby="'.$id.'">';

			foreach ($elements['menu'] as $el) {
				$target = isset($el['target']) ? 'target="'.$el['target'].'"' : '';
				$html .= '<a class="dropdown-item" '.$target.' href="'.$el['path'].'">'.$el['name'].'</a>';
			}

			$html .=	'</div>';
			$html .= '</li>';
		}
	}

	return $html;
}
?>
<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="/index.php">
		<img src="/assets/img/logo_30.png" width="30" height="30" class="d-inline-block align-top" alt="">devilbox
	</a>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">

			<?php echo get_menu($menu);?>

		</ul>
		<?php $errors =  loadClass('Logger')->countErrors(); ?>
		<div class="form-inline my-2 my-lg-0">Errors: <?php echo $errors; ?></div>
	</div>
</nav>
<br/>
