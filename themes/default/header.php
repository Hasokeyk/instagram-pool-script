<?php

	global $keybmin;

	$fileName = pathinfo((__FILE__))['filename'];

	$globalCss = [
		'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap',
		$this->settings['THEMEPATH'].'assets/css/bootstrap.css',
		$this->settings['THEMEPATH'].'assets/vendors/iconly/bold.css',
		$this->settings['THEMEPATH'].'assets/vendors/perfect-scrollbar/perfect-scrollbar.css',
		$this->settings['THEMEPATH'].'assets/vendors/bootstrap-icons/bootstrap-icons.css',

		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/fonts/circular-std/style.css',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/fonts/fontawesome/css/fontawesome-all.css',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/fonts/flag-icon-css/flag-icon.min.css',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/fonts/fontawesome/css/fontawesome-all.css',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/multi-select/css/multi-select.css',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/bootstrap-iconpicker/css/fontawesome-iconpicker.min.css',

		$this->settings['THEMEPATH'].'assets/css/app.css',
	];
	$allCss    = array_merge($globalCss, $csses??[]);

	$globalJs = [
		'https://code.jquery.com/jquery-3.4.1.min.js',
		$this->settings['THEMEPATH'].'assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js',
		$this->settings['THEMEPATH'].'assets/js/bootstrap.bundle.min.js',
		$this->settings['THEMEPATH'].'assets/vendors/apexcharts/apexcharts.js',

		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/multi-select/js/jquery.multi-select.js',
		$keybmin->settings['KEYBPATH'].'keybmin_assets/vendor/bootstrap-iconpicker/js/fontawesome-iconpicker.min.js',

		$this->settings['THEMEPATH'].'assets/js/main.js',
	];
	$allJs    = array_merge($globalJs, $jses??[]);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$keybmin->page_title?></title>
    <meta name="description" content="<?=$keybmin->page_desc?>">

	<?php
		$keybmin->preload($allCss??null, $allJs??null, 'global');
		$keybmin->css_minify($globalCss??null, 'global');
		$keybmin->css_minify($csses??null, $fileName);
	?>

</head>
<div id="app">
    <?php
	    require "sidebar.php";
    ?>