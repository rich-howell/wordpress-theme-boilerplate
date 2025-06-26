<?php

add_theme_support( 'title-$post_tag' );
add_theme_support( 'post-thumbnails' );

function add_lightbox_assets() {
	wp_enqueue_style(
		'lightbox-css',
		get_template_directory_uri() . '/css/lightbox.css'
	);

	wp_enqueue_script(
		'lightbox-js',
		get_template_directory_uri() . '/js/lightbox.js',
		array( 'jquery' ),
		null,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'add_lightbox_assets' );

function custom_theme_enqueue_styles() {
	$theme_dir = get_template_directory_uri();

	wp_enqueue_style(
		'bootstrap-css',
		$theme_dir . '/css/bootstrap.min.css',
		null,
		filemtime( get_template_directory() . '/css/bootstrap.min.css' )
	);

	wp_enqueue_style(
		'custom_theme-css',
		$theme_dir . '/css/custom.css',
		null,
		filemtime( get_template_directory() . '/css/custom.css' )
	);

	wp_enqueue_style(
		'events-css',
		$theme_dir . '/css/events.css',
		null,
		filemtime( get_template_directory() . '/css/events.css' )
	);
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_styles' );

function custom_theme_enqueue_fontawesome() {
	wp_enqueue_style(
		'font-awesome-cdn',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		null,
		null
	);

	// Add integrity and other attributes
	add_filter(
		'style_loader_tag',
		function (
			$tag,
			$handle
		) {
			if ( 'font-awesome-cdn' === $handle ) {
				return '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
			}
			return $tag;
		},
		10,
		2
	);
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_fontawesome' );

function custom_theme_enqueue_google_fonts() {
	wp_enqueue_style(
		'google-fonts-poppins',
		'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
		null,
		null
	);
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_google_fonts' );

function custom_theme_enqueue_scripts() {
	wp_enqueue_script(
		'bootstrap-bundle',
		get_template_directory_uri() . '/js/bootstrap.bundle.min.js',
		null,
		filemtime( get_template_directory() . '/js/bootstrap.bundle.min.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'custom_theme_enqueue_scripts' );


/**
* Remove Howdy Site Wide
*/

function remove_howdy_in_strings( $text ) {
	$text = str_ireplace( 'Howdy! ', '', $text );
	$text = str_ireplace( 'Howdy, ', '', $text );
	return $text;
}
add_filter( 'gettext', 'remove_howdy_in_strings' );

/**
 * Disable the Gutenberg editor
 */

add_filter( 'use_block_editor_for_post_type', 'disable_gutenberg', 10, 2 );
function disable_gutenberg( $current_status, $post_type ) {
	return false;
}

/**
* Remove comments from sidebar
*/

function remove_admin_menu_items() {
	$remove_menu_items = array( __( 'Comments' ) );
	global $menu;
	end( $menu );
	while ( prev( $menu ) ) {
		$item = explode( ' ', $menu[ key( $menu ) ][0] );
		if ( in_array( $item[0] !== null ? $item[0] : '', $remove_menu_items ) ) {
			unset( $menu[ key( $menu ) ] );}
	}
}

add_action( 'admin_menu', 'remove_admin_menu_items' );

/**
* Redirect user away from comments page
*/

add_action(
	'admin_init',
	function () {

		global $page_now;

		if ( 'edit-comments.php' === $page_now ) {
			wp_safe_redirect( admin_url() );
			exit;
		}

		add_action(
			'admin_menu',
			function () {
				remove_menu_page( 'edit-comments.php' );
			}
		);

		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}
);

/**
* Remove items from menu bar
*/

function remove_admin_bar_comments() {
	global $wp_admin_bar;
	if ( is_admin_bar_showing() ) {
		$wp_admin_bar->remove_menu( 'wp-logo' );
		$wp_admin_bar->remove_menu( 'comments' );
		$wp_admin_bar->remove_menu( 'new-content' );
		$wp_admin_bar->remove_menu( 'customize' );
		$wp_admin_bar->remove_menu( 'edit' );
		$wp_admin_bar->remove_menu( 'search' );
	}
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_comments' );

/**
* Change Admin Footer Text
*/

function remove_footer_admin() {
	echo 'your_theme';
}

add_filter( 'admin_footer_text', 'remove_footer_admin' );

/**
* Cleanup Dashboard Widgets
*/

function remove_dashboard_widgets() {
	global $wp_meta_boxes;

	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
}

add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets' );

/**
* Admin menu Re-order
*/

add_filter(
	'custom_menu_order',
	function () {
		return true;
	}
);
add_filter( 'menu_order', 'my_new_admin_menu_order' );

function my_new_admin_menu_order( $menu_order ) {

	$new_positions = array(
		'index.php'                          => 1,  // Dashboard
		'edit.php?post_type=hub'             => 2,  // Hubs
		'edit.php?post_type=organisation'    => 3,  // Organisations
		'edit.php?post_type=page'            => 4,  // Pages
		'edit.php'                           => 5,  // Posts
		'edit.php?post_type=tribe_events'    => 6,  // Events
		'edit.php?post_type=acf-field-group' => 7,  // Advanced Custom Fields
		'edit.php?post_type=team'            => 8,  // Advanced Custom Fields
		'upload.php'                         => 9,  // Media
	);

	function move_element( &$array, $a, $b ) {
		$out = array_splice( $array, $a, 1 );
		array_splice( $array, $b, 0, $out );
	}

	foreach ( $new_positions as $value => $new_index ) {
		$current_index = array_search( $value, $menu_order, true );
		if ( $current_index !== false ) {
			move_element( $menu_order, $current_index, $new_index );
		}
	}
	return $menu_order;
}

/**
* Pagination
*/

function bootstrap_pagination( \WP_Query $wp_query = null, $echo_out = true, $params = array() ) {
	if ( null === $wp_query ) {
		global $wp_query;
	}

	$add_args = array();

	$pages = paginate_links(
		array_merge(
			array(
				'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format'       => '?paged=%#%',
				'current'      => max( 1, get_query_var( 'paged' ) ),
				'total'        => $wp_query->max_num_pages,
				'type'         => 'array',
				'show_all'     => false,
				'end_size'     => 3,
				'mid_size'     => 1,
				'prev_next'    => true,
				'prev_text'    => __( 'Previous Page' ),
				'next_text'    => __( 'Next Page' ),
				'add_args'     => $add_args,
				'add_fragment' => '',
			),
			$params
		)
	);

	if ( is_array( $pages ) ) {

			$pagination = '<nav class="mt-3" aria-label="navigation">';

			$pagination .= '<ul class="pagination justify-content-center"';

		foreach ( $pages as $page ) {
			$pagination .= '<li class="page-item' . ( strpos( $page, 'current' ) !== false ? ' active' : '' ) . '"> ' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
		}

		$pagination .= '</ul></nav>';

		if ( $echo_out ) {
			echo esc_attr( $pagination );
		} else {
			return $pagination;
		}
	}

	return null;
}

/**
* Difficulty Area taxonomy
*/

function custom_workout_difficulty_taxonomy() {

	$labels = array(
		'name'              => _x( 'Difficulty', 'taxonomy general name' ),
		'singular_name'     => _x( 'Difficulty', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Difficulty' ),
		'all_items'         => __( 'All Difficulty' ),
		'parent_item'       => __( 'Parent Difficulty' ),
		'parent_item_colon' => __( 'Parent Difficulty:' ),
		'edit_item'         => __( 'Edit Difficulty' ),
		'update_item'       => __( 'Update Difficulty' ),
		'add_new_item'      => __( 'Add New Difficulty' ),
		'new_item_name'     => __( 'New Difficulty Name' ),
		'menu_name'         => __( 'Difficulty' ),
	);

	register_taxonomy(
		'workout-difficulty',
		array(
			'workout',
			'exercise',
		),
		array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'workout-difficulty' ),
		)
	);

	$default_terms = array( 'Beginner', 'Intermediate', 'Advanced' );

	foreach ( $default_terms as $term_name ) {
		wp_insert_term( $term_name, 'workout-difficulty' );
	}
}

add_action( 'init', 'custom_workout_difficulty_taxonomy' );
