<?php
	global $keybmin;

	$fileName = pathinfo((__FILE__))['filename'];

	$csses = [];
	$jses  = [
		$this->settings['THEMEPATH'].'assets/js/pages/dashboard.js',
	];

	require "header.php";
?>
<div id="main">
	<header class="mb-3">
		<a href="#" class="burger-btn d-block d-xl-none">
			<i class="bi bi-justify fs-3"></i>
		</a>
	</header>

	<div class="page-heading">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Layout Default</h3>
					<p class="text-subtitle text-muted">The default layout </p>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page">Layout Default</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
		<section class="section">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Default Layout</h4>
				</div>
				<div class="card-body">
					Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, commodi? Ullam quaerat
					similique iusto
					temporibus, vero aliquam praesentium, odit deserunt eaque nihil saepe hic deleniti? Placeat
					delectus
					quibusdam ratione ullam!
				</div>
			</div>
		</section>
	</div>

	<footer>
		<div class="footer clearfix mb-0 text-muted">
			<div class="float-start">
				<p>2021 © Mazer</p>
			</div>
			<div class="float-end">
				<p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="http://ahmadsaugi.com">A. Saugi</a></p>
			</div>
		</div>
	</footer>
</div>
<?php
	require "footer.php";
?>
