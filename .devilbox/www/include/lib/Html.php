<?php
namespace devilbox;

class Html
{

	/**
	 * The Devilbox Navbar menu
	 * @var array
	 */
	private $_menu = array(
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
				'name' => 'C&C',
				'path' => '/cnc.php'
			),
			array(
				'name' => 'Emails',
				'path' => '/mail.php'
			)
		),
		array(
			'name' => 'Configs',
			'menu' => array(
				array(
					'name' => 'PHP',
					'path' => '/config_php.php'
				),
				array(
					'name' => 'Httpd',
					'path' => '/config_httpd.php'
				),
			),
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
					'name' => 'MongoDB DB',
					'path' => '/db_mongo.php'
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
					'name' => 'Httpd Info',
					'path' => '/info_httpd.php'
				),
				array(
					'name' => 'PHP Info',
					'path' => '/info_php.php'
				),
				array(
					'name' => 'PHP Xdebug Info',
					'path' => '/info_xdebug.php'
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
					'name' => 'MongoDB Info',
					'path' => '/info_mongo.php'
				),
				array(
					'name' => 'Redis Info',
					'path' => '/info_redis.php'
				),
				array(
					'name' => 'Memcached Info',
					'path' => '/info_memcd.php'
				),
			)
		),
		array(
			'name' => 'Tools',
			'menu' => array(
				array(
					'name' => 'Adminer',
					'path' => '__ADMINER__',
					'target' => '_blank'
				),
				array(
					'name' => 'phpMyAdmin',
					'path' => '__PHPMYADMIN__',
					'target' => '_blank'
				),
				array(
					'name' => 'phpPgAdmin',
					'path' => '__PHPPGADMIN__',
					'target' => '_blank'
				),
				array(
					'name' => 'PHPRedMin',
					'path' => '/vendor/phpredmin/public/index.php',
					'target' => '_blank'
				),
				array(
					'name' => 'PHPMemcachedAdmin',
					'path' => '/vendor/phpmemcachedadmin-1.3.0/index.php',
					'target' => '_blank'
				),
				array(
					'name' => 'Opcache GUI',
					'path' => '/opcache.php'
				),
				array(
					'name' => 'Opcache Control Panel',
					'path' => '/vendor/ocp.php'
				)
			)
		)
	);



	/*********************************************************************************
	 *
	 * Statics
	 *
	 *********************************************************************************/

	/**
	 * $this Singleton instance
	 * @var Object|null
	 */
	protected static $_instance = null;

	/**
	 * Singleton Instance getter.
	 *
	 * @return object|null
	 */
	public static function getInstance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}



	/*********************************************************************************
	 *
	 * Select functions
	 *
	 *********************************************************************************/


	public function getHead($font_awesome = false)
	{
		$css_fa = ($font_awesome) ? '<link href="/vendor/font-awesome/font-awesome.min.css" rel="stylesheet">' : '';

		$html = <<<HTML
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

			<!-- Meta -->
			<meta name="description" content="The devilbox - your customizable LAMP/LEMP stack.">
			<meta name="author" content="cytopia">

			<!-- Favicons -->
			<link rel="apple-touch-icon" sizes="57x57" href="/assets/favicon/apple-icon-57x57.png">
			<link rel="apple-touch-icon" sizes="60x60" href="/assets/favicon/apple-icon-60x60.png">
			<link rel="apple-touch-icon" sizes="72x72" href="/assets/favicon/apple-icon-72x72.png">
			<link rel="apple-touch-icon" sizes="76x76" href="/assets/favicon/apple-icon-76x76.png">
			<link rel="apple-touch-icon" sizes="114x114" href="/assets/favicon/apple-icon-114x114.png">
			<link rel="apple-touch-icon" sizes="120x120" href="/assets/favicon/apple-icon-120x120.png">
			<link rel="apple-touch-icon" sizes="144x144" href="/assets/favicon/apple-icon-144x144.png">
			<link rel="apple-touch-icon" sizes="152x152" href="/assets/favicon/apple-icon-152x152.png">
			<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-icon-180x180.png">
			<link rel="icon" type="image/png" sizes="192x192"  href="/assets/favicon/android-icon-192x192.png">
			<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="96x96" href="/assets/favicon/favicon-96x96.png">
			<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
			<link rel="manifest" href="/manifest.json">
			<meta name="msapplication-TileColor" content="#ffffff">
			<meta name="msapplication-TileImage" content="/assets/favicon/ms-icon-144x144.png">
			<meta name="theme-color" content="#ffffff">

			<!-- CSS/JS -->
			<link href="/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
			{$css_fa}
			<link href="/assets/css/custom.css" rel="stylesheet">

			<title>The DevilBox</title>
HTML;
		return $html;
	}


	public function getNavbar()
	{
		$menu = $this->_buildMenu();
		$logout = '';
		if (loadClass('Helper')->isLoginProtected()) {
			$logout =	'<ul class="navbar-nav">'.
							'<li class="nav-item text-right"><a class="nav-link" href="/logout.php?id='.session_id().'">Log out</a></li>'.
						'</ul>';
		}

		$html = <<<HTML
			<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
				<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<a class="navbar-brand" href="/index.php">
					<img src="/assets/img/logo.png" width="30" height="30" class="d-inline-block align-top" alt="">devilbox
				</a>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">

						{$menu}


					</ul>
					{$logout}
				</div>

			</nav>
			<br/>
HTML;
		return $html;
	}



	public function getFooter()
	{
		$render_time = round((microtime(true) - $GLOBALS['TIME_START']), 2);
		$errors =  loadClass('Logger')->countErrors();

		$html = <<<HTML
			<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse footer">
				<div class="container justify-content-end">
					<ul class="nav navbar-nav">
						<li class="nav-item nav-link">Render time: {$render_time} sec</li>
						<li class="nav-item"><a class="nav-link" target="_blank" href="https://github.com/cytopia/devilbox"><code>Github</code></a></li>
						<li class="nav-item"><a class="nav-link" href="/credits.php"><code>Credits</code></a></li>
						<li class="nav-item"><a class="nav-link" href="/support.php"><code>Support</code></a></li>
						<li class="nav-item"><a class="nav-link" href="/debug.php"><code>Debug ({$errors})</code></a></li>
					</ul>
				</div>
			</nav>

			<script src="/vendor/jquery/jquery-3.2.1.min.js"></script>
			<script src="/vendor/tether/tether.min.js"></script>
			<script src="/vendor/bootstrap/bootstrap.min.js"></script>
HTML;
		return $html;
	}



	public function getCirle($name)
	{
		switch ($name) {
			case 'dns':
				$class = 'bg-info';
				$version = loadClass('Dns')->getVersion();
				$available = loadClass('Dns')->isAvailable();
				$name = loadClass('Dns')->getName();
				break;
			case 'php':
				$class = 'bg-info';
				$version = loadClass('Php')->getVersion();
				$available = loadClass('Php')->isAvailable();
				$name = loadClass('Php')->getName();
				break;
			case 'httpd':
				$class = 'bg-info';
				$version = loadClass('Httpd')->getVersion();
				$available = loadClass('Httpd')->isAvailable();
				$name = loadClass('Httpd')->getName();
				break;
			case 'mysql':
				$class = 'bg-warning';
				$version = loadClass('Mysql')->getVersion();
				$available = loadClass('Mysql')->isAvailable();
				$name = loadClass('Mysql')->getName();
				break;
			case 'pgsql':
				$class = 'bg-warning';
				$version = loadClass('Pgsql')->getVersion();
				$available = loadClass('Pgsql')->isAvailable();
				$name = loadClass('Pgsql')->getName();
				break;
			case 'redis':
				$class = 'bg-danger';
				$version = loadClass('Redis')->getVersion();
				$available = loadClass('Redis')->isAvailable();
				$name = loadClass('Redis')->getName();
				break;
			case 'memcd':
				$class = 'bg-danger';
				$version = loadClass('Memcd')->getVersion();
				$available = loadClass('Memcd')->isAvailable();
				$name = loadClass('Memcd')->getName();
				break;
			case 'mongo':
				$class = 'bg-danger';
				$version = loadClass('Mongo')->getVersion();
				$available = loadClass('Mongo')->isAvailable();
				$name = loadClass('Mongo')->getName();
				break;
			default:
				$available = false;
				$version = '';
				break;
		}

		$style = 'color:black;';
		$version = '('.$version.')';
		if (!$available) {
			$class = '';
			$style = 'background-color:gray;';
			$version = '&nbsp;';
		}
		$circle = '<div class="circles">'.
					'<div>'.
						'<div class="'.$class.'" style="'.$style.'">'.
							'<div>'.
								'<div><br/><strong>'.$name.'</strong><br/><small style="color:#333333">'.$version.'</small></div>'.
							'</div>'.
						'</div>'.
					'</div>'.
				'</div>';
		return $circle;
	}



	/*********************************************************************************
	 *
	 * Private functions
	 *
	 *********************************************************************************/

	private function _buildMenu()
	{

		$path = $_SERVER['PHP_SELF'];
		$html = '';

		foreach ($this->_menu as $type => $elements) {

			// Menu
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

			// Submenu
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

					// Replace
					if ($el['path'] == '__PHPMYADMIN__') {
						if (version_compare(loadClass('Php')->getVersion(), '5.5', '<')) {
							$el['path'] = '/vendor/phpmyadmin-4.0/index.php';
						} elseif (version_compare(loadClass('Php')->getVersion(), '7.1', '<')) {
							$el['path'] = '/vendor/phpmyadmin-4.9.7/index.php';
						} else {
							$el['path'] = '/vendor/phpmyadmin-5.1.3/index.php';
						}
					}
					if ($el['path'] == '__PHPPGADMIN__') {
						if (version_compare(loadClass('Php')->getVersion(), '7.1', '<')) {
							$el['path'] = '/vendor/phppgadmin-5.6.0/';
						} elseif (version_compare(loadClass('Php')->getVersion(), '7.2', '<')) {
							$el['path'] = '/vendor/phppgadmin-7.12.1/';
						} else {
							$el['path'] = '/vendor/phppgadmin-7.13.0/';
						}
					}
					if ($el['path'] == '__ADMINER__') {
						if (version_compare(loadClass('Php')->getVersion(), '5.4', '<')) {
							$el['path'] = '/vendor/adminer-4.6.3-en.php';
						} elseif (version_compare(loadClass('Php')->getVersion(), '8.0', '<')){
							$el['path'] = '/vendor/adminer-4.8.1-en.php';
						} else {
							$el['path'] = '/vendor/adminer-4.8.1-en.php';
						}
					}

					$target = isset($el['target']) ? 'target="'.$el['target'].'"' : '';
					$html .= '<a class="dropdown-item" '.$target.' href="'.$el['path'].'">'.$el['name'].'</a>';
				}

				$html .=	'</div>';
				$html .= '</li>';
			}
		}

		return $html;
	}
}
