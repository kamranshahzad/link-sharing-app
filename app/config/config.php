<?php


class config{
	
	const ENV               = 'dev'; // dev, liv
	const APPLICATION_DEBUG = true;
	const EMAIL_DEBUG       = false;
	const ERROR_LOG			= true;
	const HELP				= true;
	const SYNTAX			= true;
	
	const SITE_NAME			= 'Siz-el';
	const IN_PAGE_TITLE		= true;
	
	
	private $appConfig	= array();
	private $devConfig = array(
							'domain'=>'localhost',
							'dsn'=>array(
									'host'=>'localhost',
									'dbname'=>'dbname',
									'username'=>'username',
									'password'=>'password'
									),
							'encoding'=>'utf8',
							'persistent'=>true,
							'pooling'=>true 
						);
	private $livConfig = array(
							'domain'=>'DOMAIN',
							'dsn'=>array(
									'host'=>'localhost',
									'dbname'=>'dbname',
									'username'=>'username',
									'password'=>'password'
									),
							'encoding'=>'utf8',
							'persistent'=>true,
							'pooling'=>true
						);
	
	
	private $defaultConfig = array(
								'debugemails'=>array('admin_email_id'),
								'fromemails'=>array('USEREMLS'=>array('title'=>'Registration Verification','email'=>'admin_email_id'),'FORGETPASS'=>array('title'=>'Support','email'=>'admin_email_id')),
								'all'=>array(),
								'site'=>array(
									'stylesheets'=>array('dir'=>'sitecss','files'=>array('styles')),
									'javascripts'=>array('dir'=>'sitejs','files'=>array('jquery.validate','site_js')),
									'assets'=>array('imgs'=>'siteimages','swf'=>'flashfiles'),
									'swf'=>'flashfiles'
								 ),
								'admin'=>array(
								 	'stylesheets'=>array('dir'=>'css','files'=>array('main','dev-styles','form-styles')),
									'javascripts'=>array('dir'=>'js','files'=>array('jquery','jquery.validate','validate_form')),
									'assets'=>array('imgs'=>'images','swf'=>'flashfiles')
								 ),
								 'uploads'=>array('GAMES'=>'gamesicons','USERS'=>'siteusers'),
								 'venders'=>array(
								 	'ckeditor'=>array(),
									'modalbox'=>array('dir'=>'modalbox','css'=>array(),'js'=>array())
								 ),
								);
	
	private $navigationOptions = array(
							'site'=>array(),
							'admin'=>array(
								'mainmenus'=>array(
									array('label'=>'Administrative Users','link'=>'admin-users.php?q=show' , 'dash'=> array('icon'=>'admin-user-icons.png','detail'=>'Add/Update/Remove Administrative Users')),
									array('label'=>'User Management','link'=>'manage-users.php?q=show' , 'dash'=> array('icon'=>'user-management-icon.png','detail'=>'Add/Update/Remove Site Users')),
									array('label'=>'Topics Manager','link'=>'topices.php?q=show' , 'dash'=> array('icon'=>'category-icon.png','detail'=>'Add/Update/Remove site Post Categories')),
									array('label'=>'Posts Manager','link'=>'posts-manager.php?q=show' , 'dash'=> array('icon'=>'links_icon.png','detail'=>'Add/Update/Remove Site Posts')),
									array('label'=>'Comments Manager','link'=>'comments-manager.php?q=show' , 'dash'=> array('icon'=>'comments-icon.png','detail'=>'Add/Update/Remove Users')),
									array('label'=>'Games Manager','link'=>'games-manager.php?q=show' , 'dash'=> array('icon'=>'games-icon.png','detail'=>'Add/Update/Remove Users')),
									array('label'=>'Polls Manager','link'=>'poll-manager.php?q=show' , 'dash'=> array('icon'=>'poll_icon.png','detail'=>'Add/Update/Remove Users')),
									array('label'=>'Ads Manager','link'=>'ads-manager.php?q=show' , 'dash'=> array('icon'=>'ads-management-icon.png','detail'=>'Add/Update/Remove Users')),
									array('label'=>'Content Manager','link'=>'content-manager.php?q=show' , 'dash'=> array('icon'=>'website_manager_icon.png','detail'=>'View/Modify Content Pages Text & Tiles')),
									array('label'=>'Site Statistics','link'=>'statistics.php?q=show' , 'dash'=> array('icon'=>'statices_icon.png','detail'=>'View site all statices/fiter by dates ')),
									array('label'=>'My Account','link'=>'my-account.php' , 'dash'=> array('icon'=>'my-account-icon.png','detail'=>'Change password & other details of account'))
								)
							)
						);
	
	
	private $dashboardDefaults =  array('iconsize'=>array('width'=>48,'height'=>48));
	
	function __construct(){
		$this->appConfig = array_merge((self::ENV == 'dev') ? $this->devConfig : $this->livConfig , $this->defaultConfig );
	}
	
	
	public function getNavigations(){
		return 	$this->navigationOptions;
	}
	
	public function getConfig(){
		return 	$this->appConfig;
	}
	
	public function getBasePath(){
		return 	BASE_PATH;
	}
	
	public static function getErrorLog(){
		return BASE_PATH.'/logs/error_log.log';
	}
	
	
}//$






?>