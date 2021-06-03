<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
</head>
<body>

<?php
	global $keybmin;

	$login = $keybmin->login_check();
	if($login){
		header("Location:".$keybmin->settings['SITEURL']);
	}

	if(isset($post) and !empty($post)){
		if($keybmin->post_control(['mail', 'password']) and $_POST['post'] == 'login'){
			$results = $keybmin->user_login($mail, $password);
			if($results['status'] == 'success'){
				header("Refresh:1;");
			}
		}
	}
?>

<form action="" method="post">
	<input type="hidden" name="post" value="login">

	<?php
		if(isset($results['status'])){
			?>
			<div class="alert alert-<?=$results['status']?>"><?=$results['message']?></div>
			<?php
		}
	?>

	<input type="text" name="mail" placeholder="Mail">
	<input type="password" name="password" placeholder="Password">
	<input type="submit">
</form>

</body>
</html>