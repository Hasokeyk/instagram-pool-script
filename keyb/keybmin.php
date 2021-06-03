<?php


	namespace keybmin;

	class keybmin{

		public  $db_local     = 'localhost';
		public  $db_name      = '';
		public  $db_user      = '';
		public  $db_pass      = '';
		public $db           = null;
		public  $test         = false;
		public  $session_name = false;
		public  $settings     = [];
		public  $page         = 'home';
		public  $page_info    = [];
		public  $page_file    = null;
		public  $user_info;
		public  $page_title;
		public  $page_desc;

		function __construct($session_name, $settings = []){
			global $keybmin;

			if(isset($settings['db_type']) and $settings['db_type'] == 'mysqli'){
				$this->db = $this->mysqli_connect_db($settings['db_local']??null, $settings['db_name']??null, $settings['db_user']??null, $settings['db_pass']??null);
			}else{
				echo _('Desteklenmeyen Veritabanı Türü');
			}

			$this->session_name = $session_name;
			$this->test         = $settings['test']??false;

			//SECURITY
			$get = $this->get_security();
			extract($get);
			$post = $this->post_security();
			extract($post);
			//SECURITY

			//GET SETTINGS
			$ask = $this->db->query("SELECT * FROM kb_settings");
			if($ask->num_rows>0){
				while($setting = $ask->fetch_assoc()){
					$this->settings[$setting['var']] = $setting['val'];
				}

				$site_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);

				$this->settings = array_merge([
					'SITEURL'   => $site_url,
					'THEMEPATH' => $site_url.'themes/'.$this->settings['theme'].'/',
					'KEYBPATH'  => $site_url.'keyb/',
					'THEMEDIR'  => THEMEDIR.'/'.$this->settings['theme'].'/',
				], $this->settings);

			}else{
				$this->page = '500';
			}
			//GET SETTINGS

			//THEME FUNCTIONS FILE
			if(file_exists(THEMEDIR.'functions.php')){
				require THEMEDIR.'functions.php';
			}
			//THEME FUNCTIONS FILE

			//USER LOGIN CHECK
			$loginCheck = $this->login_check();
			if($loginCheck){
				$this->page = $_GET['page']??$this->page;
			}else{
				$this->page = $_GET['page']??$this->page;
			}
			//USER LOGIN CHECK

			//PAGE EXITS
			$keybmin = $this;
			if(is_dir(THEMEDIR.$this->settings['theme'])){
				$this->page_check($this->page);
				require $this->page_file.'.php';
			}else{
				exit(THEMEDIR.$this->settings['theme'].' Theme Not Found');
			}
			//PAGE EXITS
		}

		function login_check(){

			if(isset($_SESSION[$this->session_name]['session']) and !empty($_SESSION[$this->session_name]['session'])){

				$ask = $this->db->query("SELECT * FROM kb_users WHERE session = '".$_SESSION[$this->session_name]['session']."'");
				if($ask->num_rows>0){
					$this->user_info = $ask->fetch_assoc();
					return true;
				}else{
					session_destroy();
					return false;
				}

			}else{
				return false;
			}

		}

		function page_check($page){

			$page_info = $this->page_info($page);
			if($page_info !== false){

				$this->page_title = $page_info['title'];
				$this->page_desc  = $page_info['description'];

				if(file_exists(THEMEDIR.$this->settings['theme'].'/ajax/'.$page_info['template'].'.php') and $page_info['type'] == 'ajax'){
					$page_file = THEMEDIR.$this->settings['theme'].'/ajax/'.$page_info['template'];
				}else if(file_exists(KEYBDIR.'/keybmin_pages/'.$page_info['template'].'.php') and $page_info['type'] == 'keybmin'){
					$page_file = KEYBDIR.'/keybmin_pages/'.$page_info['template'];
				}else if(file_exists(THEMEDIR.$this->settings['theme'].'/'.$page_info['template'].'.php')){
					$page_file = THEMEDIR.$this->settings['theme'].'/'.$page_info['template'];
				}else if(file_exists(THEMEDIR.$this->settings['theme'].'/pages/'.$page_info['template'].'.php')){
					$page_file = THEMEDIR.$this->settings['theme'].'/pages/'.$page_info['template'];
				}else{
					header("HTTP/1.0 404 Not Found");
					$page_file = THEMEDIR.$this->settings['theme'].'/'.'404';
				}

				if($page_info['login_control'] == 1){

					if($this->login_check()){
						if(!$this->user_auth_check($this->page, 'template')){
							$this->page = 'banned';
							$page_info  = $this->page_info($this->page);
							var_dump($page_info);
							$page_file = THEMEDIR.$this->settings['theme'].'/'.$this->page;

							$this->page_title = $page_info['title'];
							$this->page_desc  = $page_info['description'];
						}
					}else{
						$page_file = THEMEDIR.$this->settings['theme'].'/'.'login';
					}

				}

				$this->page_file = $page_file;
				return true;
			}

			$this->page_file = THEMEDIR.$this->settings['theme'].'/'.'404';
			return false;
		}

		function page_info($page){

			$ask = $this->db->query("SELECT * FROM kb_pages WHERE link = '?page=".$page."'");
			if($ask->num_rows>0){
				return $this->page_info = $ask->fetch_assoc();
			}

			return false;
		}

		function user_auth_check($page, $type = 'shortcode'){

			if($this->login_check()){
				$ask = $this->db->query("SELECT * FROM kb_pages WHERE ".$type." = '".$page."'");
				if($ask->num_rows>0){
					$page_info = $ask->fetch_assoc();
					if($page_info['login_control'] == '1'){
						$pageAuth = json_decode($page_info['userAuth']);

						foreach($pageAuth as $id => $authID){
							if($authID == $this->user_info['authID']){
								return true;
							}
						}
					}else{
						return true;
					}
				}
			}

			return false;
		}

		private function mysqli_connect_db($db_local = null, $db_name = null, $db_user = null, $db_pass = null){

			try{
				$db = new \mysqli($db_local??$this->db_local, $db_user??$this->db_user, ($db_pass??$this->db_pass)??'', $db_name??$this->db_name);
				if($db->connect_error){
					echo _('Veritabanı Hatası');
					exit();
				}

				$db->set_charset('utf-8');

				return $db;
			}
			catch(\mysqli_sql_exception $err){
				echo $err->getMessage();
			}

		}

		function get_security(){
			$value = [];
			foreach($_GET as $p => $d){
				if(is_string($_GET[$p]) === true){
					$value[$p] = trim(strip_tags($this->db->escape_string($d)));
				}
			}
			return $value;
		}

		function post_security(){
			$value = [];
			foreach($_POST as $p => $d){
				if(is_string($_POST[$p]) === true){
					$value[$p] = trim(strip_tags($this->db->escape_string($d)));
				}
			}
			return $value;
		}

		function get_control($get){

			$control = 0;
			foreach($get as $parametre){
				if(isset($_GET[$parametre]) and !empty($_GET[$parametre])){
					$control++;
				}else{
					return false;
					break;
				}
			}

			if(count($get) == $control){
				return true;
			}else{
				return false;
			}

		}

		function post_control($post = null){

			$control = 0;
			if($post != null){
				foreach($post as $parametre){
					if(isset($_POST[$parametre]) and !empty($_POST[$parametre])){
						$control++;
					}else{
						return $parametre;
					}
				}

				if(count($post) == $control){
					return true;
				}
			}
			return false;
		}

		function user_login($mail, $password){

			if(isset($mail, $password)){

				$ask = $this->db->query("SELECT * FROM kb_users WHERE mail = '".$mail."' AND password='".md5($password)."'");
				if($ask->num_rows>0){
					$info = $ask->fetch_assoc();

					$session = md5(time());
					$data    = [
						'ID'         => $info['id'],
						'full_name'  => $info['full_name'],
						'mail'       => $info['mail'],
						'login_time' => time(),
						'session'    => $session,
						'authID'     => $info['authID'],
					];

					setcookie($this->session_name, json_encode($data));
					$_SESSION[$this->session_name] = $data;

					$this->db->query("UPDATE kb_users SET session='".$session."' WHERE id = '".$info['id']."'");

					$results = [
						'status'  => 'success',
						'message' => _('Login Success. Wait...'),
					];

				}else{
					$results = [
						'status'  => 'danger',
						'message' => _('User Not Found'),
					];
				}

			}else{
				$results = [
					'status'  => 'danger',
					'message' => _('Parameters Not Found'),
				];
			}

			return $results;

		}

		function user_register($fullname, $mail, $password, $confirmPassword){

			if(isset($fullname, $mail, $password, $confirmPassword)){

				$ask = $this->db->query("SELECT * FROM kb_users WHERE mail = '".$mail."'");
				if($ask->num_rows == 0){

					if($password == $confirmPassword){
						$register = $this->db->query("INSERT INTO kb_users SET fullName = '".$fullname."',mail='".$mail."',password='".md5($password)."',authID='3',time='".time()."'");
						if($register){

							$this->userLogin($mail, $password);

							$results = [
								'status'  => 'success',
								'message' => _('Register Success. Auto Login. Wait... '),
							];

						}else{
							$results = [
								'status'  => 'danger',
								'message' => _('Register problem'),
							];
						}
					}else{
						$results = [
							'status'  => 'danger',
							'message' => _('Password Not Match'),
						];
					}

				}else{
					$results = [
						'status'  => 'danger',
						'message' => _('User Exists'),
					];
				}

			}else{
				$results = [
					'status'  => 'danger',
					'message' => _('Parameters Not Found'),
				];
			}

			return $results;

		}

		function preload($csses, $jses, $fileName = 'non'){

			if($csses != null){
				if($this->test == true){
					foreach($csses as $c){
						echo '<link rel="preload" href="'.$c.'" as="style">'."\n";
					}
				}else{
					echo '<link rel="preload" href="'.($this->settings['THEMEPATH'].'cache/'.$fileName.'.css').'" as="style">'."\n";
				}
			}

			if($jses != null){
				if($this->test == true){
					foreach($jses as $j){
						echo '<link rel="preload" href="'.$j.'" as="script">'."\n";
					}
				}else{
					echo '<link rel="preload" href="'.($this->settings['THEMEPATH'].'cache/'.$fileName.'.js').'" as="script">'."\n";
				}
			}

		}

		function css_minify($csses = null, $fileName = 'non'){

			if($csses != null){
				if($this->test == false){

					if(!file_exists($this->settings['THEMEDIR'].'cache/'.$fileName.'.css')){
						$minifier = new Minify\CSS();
						foreach($csses as $c){
							$minifier->add($c);
						}
						$minifier->minify($this->settings['THEMEDIR'].'cache/'.$fileName.'.css');
					}

					echo '<link href="'.($this->settings['THEMEPATH'].'cache/'.$fileName.'.css').'" rel="stylesheet" type="text/css"/>'."\n";
				}else{
					$css = '';
					foreach($csses as $c){
						$css .= '<link href="'.$c.'" rel="stylesheet" type="text/css"/>'."\n";
					}
					echo $css;
				}

			}else{
				return false;
			}
		}

		function js_minify($jses = null, $fileName = 'non'){

			if($jses != null){
				if($this->test == false){

					if(!file_exists($this->settings['THEMEDIR'].'cache/'.$fileName.'.js')){
						$minifier = new Minify\JS();
						foreach($jses as $j){

							if($this->startsWith($j, 'http')){
								$j = file_get_contents($j);
							}

							$minifier->add($j);
						}
						$minifier->minify($this->settings['THEMEDIR'].'cache/'.$fileName.'.js');
					}

					echo '<script src="'.($this->settings['THEMEPATH'].'cache/'.$fileName.'.js').'" type="text/javascript"/></script>'."\n";
				}else{
					$js = '';
					foreach($jses as $j){
						$js .= '<script src="'.str_replace($this->settings['THEMEDIR'], $this->settings['THEMEPATH'], $j).'" type="text/javascript"/></script>'."\n";
					}
					echo $js;
				}
			}else{
				return false;
			}
		}

		function get_sidebar_array($parentID = 0){
			$allSidebarMenu = [];
			$getMenu        = $this->db->query("SELECT *,(SELECT parentID FROM kb_pages WHERE parentID = KP.id LIMIT 1) AS UST FROM kb_pages AS KP WHERE menu = 1 AND parentID = '".$parentID."' ORDER BY orderBy ASC");
			if($getMenu->num_rows>0){
				while($menu = $getMenu->fetch_assoc()){
					if($menu['UST'] == ''){
						$allSidebarMenu[$menu['id']] = $menu;
					}else{
						$allSidebarMenu[$menu['id']]        = $menu;
						$allSidebarMenu[$menu['id']]['sub'] = $this->get_sidebar_array($menu['UST']);
					}
				}
			}

			return $allSidebarMenu;
		}

		function get_sidebar_html($menu_array = null, $page = null, $menu_html = null, $sub_menu_html = null, $sub = false){

			$active = $this->search_array_value($menu_array, 'template', $page);
			if($sub == false){
				echo $menu_html['ul'];
			}else{
				$menu = end($menu_array);
				if($menu == false){
					return;
				}
				echo $menu_html['ul'];
			}

			foreach($menu_array as $id => $menu){

				if($this->user_auth_check($menu['shortcode'])){

					if(isset($menu['sub']) and is_array($menu['sub'])){
						printf($menu_html['sub_content'], ($menu['template'] == $page?'active':''));
						printf($menu_html['a'], $menu['link']);
						echo '<i class="'.($menu_html['icon_class']??'').' '.$menu['iconClass'].'"></i>';
						if(isset($menu_html['menu_name'])){
							printf($menu_html['menu_name'], $menu['title']);
						}else{
							echo $menu['title'];
						}
						echo $menu_html['/a'];
						$this->get_sidebar_html($menu['sub'], $page, $sub_menu_html, $sub_menu_html, true);
						echo $menu_html['/sub_content'];
					}else{
						printf($menu_html['li'], ($menu['template'] == $page?'active':''));
						printf($menu_html['a'], $menu['link']);
						echo '<i class="'.($menu_html['icon_class']??'').' '.$menu['iconClass'].'"></i>';
						if(isset($menu_html['menu_name'])){
							printf($menu_html['menu_name'], $menu['title']);
						}else{
							echo $menu['title'];
						}
						echo $menu_html['/a'];
						echo $menu_html['/li'];
					}


				}

			}

			if($sub == false){
				echo $menu_html['/ul'];
			}else{
				echo $sub_menu_html['/ul'];
			}

		}

		function get_page_lists($filter = 'all', $filterType = 'type'){

			$allPage = [];

			if($filter == 'all'){
				$sql = "SELECT * FROM kb_pages";
			}
			else{
				$sql = "SELECT * FROM kb_pages WHERE ".$filterType." = '".$filter."'";
			}

			$askPage = $this->db->query($sql);
			if($askPage->num_rows > 0){
				while($page = $askPage->fetch_assoc()){
					if($this->user_auth_check($page['shortcode'])){
						$allPage[] = $page;
					}
				}
			}

			return $allPage;
		}

		function get_auth_lists($sub = 'all', $name = 'parentID'){
			$allAuth = [];

			if($sub == 'all'){
				$sql = "SELECT * FROM kb_auth";
			}
			else{
				$sql = "SELECT * FROM kb_auth WHERE ".$name." = '".$sub."'";
			}

			$askPage = $this->db->query($sql);
			if($askPage->num_rows > 0){
				while($page = $askPage->fetch_assoc()){
					$allAuth[] = $page;
				}
			}

			return $allAuth;
		}

		function get_user_lists($sub = 'all'){
			global $db;

			$allUsers = [];

			if($sub == 'all'){
				$sql = "SELECT * FROM kb_users";
			}
			else{
				$sql = "SELECT * FROM kb_users WHERE parentID = '".$sub."'";
			}

			$askUser = $db->query($sql);
			if($askUser->num_rows > 0){
				while($user = $askUser->fetch_assoc()){
					$allUsers[] = $user;
				}
			}

			return $allUsers;
		}

		function search_array_value($array = [], $needle, $haystrack){

			foreach($array as $key => $value){
				if(is_array($value)){
					$sub = $this->search_array_value($value, $needle, $haystrack);
					if($sub){
						return true;
					}
				}else{
					if($key == $needle){
						if($value == $haystrack){
							return true;
							break;
						}
					}
				}
			}

			return false;
		}

		function __destruct(){
			if(isset($this->db)){
				$this->db->close();
			}
		}
	}