<?php namespace EkkoCore\templates;
$type = get_query_var( \EkkoCore\Plugin::QUERY_VAR_TYPE );
$id = get_query_var( \EkkoCore\Plugin::QUERY_VAR_ID );
?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>Ekkolabs</title>
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<iframe style="display: none; width: 0px; height: 0px;" src="<?php echo \EkkoCore\EKKOLABS_SCHEME . ':///' . esc_attr( $type ) . '/' . esc_attr( $id ); ?>"></iframe>
<div class="site-wrapper">
	<div class="site-wrapper-inner">
		<div class="cover-container">
			<div class="masthead clearfix">
				<div class="inner">
					<img style="width: 400px;" src="<?php echo \EkkoCore\PLUGIN_URL; ?>src/img/ekkolabs.png">
				</div>
			</div>
			<div class="inner cover">
				<h1 class="cover-heading">Welcome to Ekkolabs!</h1>

				<p class="lead">With the Ekkolabs app, you can learn while you are traveling. Or at home. Or over a meal. Or whenever it is convenient for you.</p>

				<p class="lead">Install Ekkolabs now and start learning something new today.</p>

				<p class="lead">
					<a href="https://itunes.apple.com/us/app/ekkolabs/id892911573"><img src="<?php echo \EkkoCore\PLUGIN_URL; ?>src/img/appstore-en.svg" class="app-store-badge"></a>
					<a href="https://play.google.com/store/apps/details?id=org.ekkoproject.android.player"><img src="<?php echo \EkkoCore\PLUGIN_URL; ?>src/img/playstore-en.png"></a>
				</p>

				<p class="lead">
					<a href="<?php echo \EkkoCore\EKKOLABS_SCHEME . ':///' . esc_attr( $type ) . '/' . esc_attr( $id ); ?>">Launch Course</a>
				</p>
			</div>
			<div class="mastfoot">
				<div class="inner">
					<p>&copy; 2014 <a href="/">Ekkolabs</a></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
