<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>	
	<?php wp_head(); ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light border-bottom py-2 mb-4">
	<div class="container d-flex flex-wrap align-items-center justify-content-between">    
   
	<a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>">
		<img class="navbar-logo" src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/logo.webp" alt="{{theme name}} Logo">
	</a>
	
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
		aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav me-auto mb-2 mb-lg-0">
		<li class="nav-item">
			<a class="nav-link active" aria-current="page" href="<?php echo esc_url( home_url() ); ?>">Home</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" aria-current="page" href="<?php echo esc_url( home_url( '/menu1' ) ); ?>">menu1</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" aria-current="page" href="<?php echo esc_url( home_url( '/menu2' ) ); ?>">menu2</a>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="supportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
			dropMenu1
			</a>
			<ul class="dropdown-menu" aria-labelledby="supportDropdown">
			<li><a class="dropdown-item" aria-current="page" href="<?php echo esc_url( home_url( '/subMenu1' ) ); ?>">subMenu1</a></li>
			</ul>
		</li>
		</ul>
	</div>    
	</div>
</nav> 
</header>
