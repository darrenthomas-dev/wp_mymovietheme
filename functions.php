<?php
/**
 * My Second Theme functions and definitions
 *
 * @package WordPress
 * @subpackage My_Second_Theme
 * @since My Second Theme 1.0
 */

if ( ! function_exists( 'mysecondtheme_setup' ) ) :

	/**
	 * 'Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since My Second Theme 1.0
	 */
	function mysecondtheme_setup() {
		/**
		 * Add default posts and comments RSS feed links to head.
		 */
		 add_theme_support( 'automatic-feed-links' );

		 /**
		  * Enable support for Post Thumbnails
		  */
		 add_theme_support( 'post-thumbnails' );

		 /**
		  * This theme useswp_nav_menu() in one location.
		  */
		 register_nav_menus( array(
			 'primary' => __( 'Header Menu', 'mysecondtheme' ),
		 ) );

		 /**
		  * Enable support for Post Formats.
			*/
			add_theme_support( 'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'status',
				'audio',
				'chat',
			) );
	}
endif;
add_action( 'after_setup_theme', 'mysecondtheme_setup' );

/**
 * Creates a copy right message
 */
function copyright_text() {
	echo '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' );
}

/**
 * Enqueue scripts and styles.
 */
function mysecondtheme_scripts_and_styles() {
	// Reset stylesheet.
	wp_enqueue_style( 'css/reset', get_stylesheet_uri() );

	// Theme stylesheet.
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	/**
	 * Better jQuery inclusion.
	 */
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', ( 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js' ), false );
		wp_enqueue_script( 'jquery' );
	}
}
add_action( 'wp_enqueue_scripts', 'mysecondtheme_scripts_and_styles' );

/**
 * Help text for help section.
 */
function dt_custom_help_tab() {
	global $post_id;
	$screen = get_current_screen();

	if ( is_taxonomy( 'dt_genre' ) ) {
		$screen->add_help_tab(array(
			'id'      => 'movie_help_genre',
			'title'   => 'Genres',
			'content' => '<h3>Choosing genres</h3><p>For help with selecting the correct genre for your movie you could check out the information on <a href="http://www.imdb.com/">imdb.com</a>.</p>',
			)
		);
	}
}
add_action( 'admin_head', 'dt_custom_help_tab' );

/**
 * Adding Custom Post Type.
 */
function dt_movie_post_type() {
	$labels = array(
		'name'               => __( 'Movies', 'mysecondtheme' ),
		'singular_name'      => __( 'Movie', 'mysecondtheme' ),
		'add_new'            => __( 'Add New', 'mysecondtheme' ),
		'add_new_item'       => __( 'Add New Movie', 'mysecondtheme' ),
		'edit_item'          => __( 'Edit Movie', 'mysecondtheme' ),
		'new_item'           => __( 'New Movie', 'mysecondtheme' ),
		'all_items'          => __( 'All Movies', 'mysecondtheme' ),
		'view_item'          => __( 'View Movies', 'mysecondtheme' ),
		'search_items'       => __( 'Search Movies', 'mysecondtheme' ),
		'not_found'          => __( 'No movies found', 'mysecondtheme' ),
		'not_found_in_trash' => __( 'No movies found in Trash', 'mysecondtheme' ),
		'menu_name'          => __( 'Movies', 'mysecondtheme' ),
	);

	$args = array(
		'labels'              => $labels,
		'description'         => '',
		'exclude_from_search' => false,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'movie' ),
		'capability_type'     => 'post',
		'menu_icon'           => get_bloginfo( 'template_directory' ) . '/images/movie.png',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 20,
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions' ),
		'can_export'          => true,
	);

	register_post_type( 'dt_movie', $args );
}
add_action( 'init', 'dt_movie_post_type' );

/**
 * Setup metaboxes
 */
function dt_movies_meta_box() {
	add_meta_box(
		'dt_movies_meta',
		__( 'Movie details', 'mysecondtheme' ),
		'dt_movie_metabox_fields',
		'dt_movie',
		'normal',
		'core'
	);
}
add_action( 'add_meta_boxes', 'dt_movies_meta_box' );

/**
 * Movie post type custom metaboxes.
 *
 * @param type $post Update movie post type.
 */
function dt_movie_metabox_fields( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'dt_movie_meta_noncename' );

	$rating  = get_post_meta( $post->ID, 'dt_movie_rating', true );
	$running = get_post_meta( $post->ID, 'dt_movie_running_time', true );
	$release = get_post_meta( $post->ID, 'dt_movie_release_date', true );
	$review  = get_post_meta( $post->ID, 'dt_movie_review_rating', true );
	?>

	<p><label for="dt_movie_rating">Movie classification</label>
		<br>
		<select name="dt_movie_rating" id="dt_movie_rating">
			<option value="">Select a classification</option>
			<option value="G" <?php	if ( 'G' === $rating ) { echo 'selected'; } ?>>G</option>
			<option value="PG" <?php if ( 'PG' === $rating ) { echo 'selected'; } ?>>PG</option>
			<option value="PG-13" <?php if ( 'PG-13' === $rating ) { echo 'selected'; } ?>>PG-13</option>
			<option value="R" <?php if ( 'R' === $rating ) { echo 'selected'; } ?>>R</option>
			<option value="NC-17" <?php if ( 'NC-17' === $rating ) { echo 'selected'; } ?>>NC-17</option>
		</select>
		<br>
		<span class="description">Select the US rating classification from the dropdown</span>
	</p>
	<p>
		<label for="dt_movie_running_time">Running time</label><br>
		<input type="text" class="all-options" name="dt_movie_running_time" id="dt_movie_running_time" value="<?php echo esc_attr( $running ); ?>"><br><span class="description">Enter the running time in minutes</span>
	</p>
	<p>
		<label for="review_rating_1"><input type="radio" value="1" id="review_rating_1" name="dt_movie_review_rating" <?php if ( '1' === $review ) { echo 'checked'; } ?>> <span>1 star</span></label><br>
		<label for="review_rating_2"><input type="radio" value="2" id="review_rating_2" name="dt_movie_review_rating" <?php if ( '2' === $review ) { echo 'checked'; } ?>> <span>2 star</span></label><br>
		<label for="review_rating_3"><input type="radio" value="3" id="review_rating_2" name="dt_movie_review_rating" <?php if ( '3' === $review ) { echo 'checked'; } ?>> <span>3 star</span></label><br>
		<label for="review_rating_4"><input type="radio" value="4" id="review_rating_4" name="dt_movie_review_rating" <?php if ( '4' === $review ) { echo 'checked'; } ?>> <span>4 star</span></label><br>
		<label for="review_rating_5"><input type="radio" value="5" id="review_rating_5" name="dt_movie_review_rating" <?php if ( '5' === $review ) { echo 'checked'; } ?>> <span>5 star</span></label><br>
		<span class="description">Select the movie review rating</span>
	</p>
	<?php
}

/**
 * Save Movie post type meta data.
 *
 * @param type $post_id update movie post type meta.
 */
function dt_movie_meta_save( $post_id ) {
	global $post;

	// If the post has not been updated, do nothng.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Verify from screen and with authorisation.
	if ( ! isset( $_POST['dt_movie_meta_noncename'] ) || ! wp_verify_nonce( $_POST['dt_movie_meta_noncename'], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	// Get the post type object.
	$post_type = get_post_type_object( $post->post_type );

	// Check if the current user has permission to edit the post.
	if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
		return $post_id;
	}

	// Get the posted data and pass to associative array.
	$metadata['dt_movie_rating'] = ( isset( $_POST['dt_movie_rating'] ) ? $_POST['dt_movie_rating'] : '' );

	$metadata['dt_movie_running_time'] = ( isset( $_POST['dt_movie_running_time'] ) ? $_POST['dt_movie_running_time'] : '' );

	$metadata['dt_movie_release_date'] = ( isset( $_POST['dt_movie_release_date'] ) ? $_POST['dt_movie_release_date'] : '' );

	$metadata['dt_movie_review_rating'] = ( isset( $_POST['dt_movie_review_rating'] ) ? $_POST['dt_movie_review_rating'] : '' );

	// Update record.
	foreach ( $metadata as $key => $value ) {
		// Get current meta value.
		$current_value = get_post_meta( $post_id, $key, true );

		if ( $value && '' == $current_value ) {
			add_post_meta( $post_id, $key, $value, true );
		} elseif ( $value && $value != $current_value ) {
			update_post_meta( $post_id, $key, $value );
		} elseif ( '' == $value && $current_value ) {
			delete_post_meta( $post_id, $key, $current_value );
		}
	}
}
add_action( 'save_post', 'dt_movie_meta_save' );

/**
 * Create custom taxonomies.
 */
function dt_movie_taxonomies() {
	$labels = array(
		'name'               => _x( 'Genres', 'taxonomy general name', 'mysecondtheme' ),
		'singular_name'      => _x( 'Genre', 'taxonomy singular name', 'mysecondtheme' ),
		'add_new_item'       => __( 'Genre', 'Add New Genre', 'mysecondtheme' ),
		'update_item'        => __( 'Update Genre', 'mysecondtheme' ),
		'edit_item'          => __( 'Genre', 'Edit Genre', 'mysecondtheme' ),
		'new_item_name'      => __( 'Genre', 'New Genre Name', 'mysecondtheme' ),
		'all_items'          => __( 'Genre', 'All Genres', 'mysecondtheme' ),
		'parent_item'        => __( 'Parent Genre', 'mysecondtheme' ),
		'parent_item_colon'  => __( 'Parent Genre:', 'mysecondtheme' ),
		'search_items'       => __( 'Genre', 'Search Genres', 'mysecondtheme' ),
		'menu_name'          => __( 'Genre', 'mysecondtheme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'genre' ),
		'hierarchical'        => true,
		'show_tagcloud'       => false,
		'show_admin_column'   => true,
		'sort'                => true,
	);

	register_taxonomy( 'dt_genre', 'dt_movie', $args );
}
add_action( 'init', 'dt_movie_taxonomies' );

/**
 * Help text for taxonomy.
 */
function dt_movie_help_text( $contextual_help, $screen_id, $screen ) {
	if ( 'dt_movie' === $screen->id ) {

	} elseif ( 'edit-dt_movie' === $screen->id ) {
		# code...
	} elseif ( 'edit-dt_genre' === $screen->id ) {
		$contextual_help .= '<p>' . __( 'Add movie genres to the Genre taxonomy to help classify the movies added', 'mysecondtheme' ) . '</p>';
	}
	return $contextual_help;
}
add_action( 'contextual_help', 'dt_movie_help_text', 10, 3 );

/**
 * Movie type custom messages.
 *
 * @param type $messages Update messages for movie type.
 */
function dt_updated_movie_messages( $messages ) {
	global $post, $post_id;
	$messages['dt_movie'] = array(
		0  => '',
		1  => sprintf( __( 'Movie updated. <a href="%s">View movie</a>', 'mysecondtheme' ), esc_url( get_permalink( $post_id ) ) ),
		2  => __( 'Custom field updated.', 'mysecondtheme' ),
		3  => __( 'Custom field deleted.', 'mysecondtheme' ),
		4  => __( 'movie updated.', 'mysecondtheme' ),
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Movie restored to revision from %s', 'mysecondtheme' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => sprintf( __( 'Movie published. <a href="%s">View Movie</a>', 'mysecondtheme' ), esc_url( get_permalink( $post_id ) ) ),
		7  => __( 'Movie saved.', 'mysecondtheme' ),
		8  => sprintf( __( 'Movie submitted. <a target="_blank" href="%s">Preview movie</a>', 'mysecondtheme' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_id ) ) ) ),
		9  => sprintf( __( 'Movie scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview movie</a>', 'mysecondtheme' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_id ) ) ),
		10 => sprintf( __( 'Movie draft updated. <a target="_blank" href="%s">Preview movie</a>', 'mysecondtheme' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_id ) ) ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'dt_updated_movie_messages' );

/**
 * Flush rewrite rules on theme switch.
 */
function dt_flush_rewrite_rules() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'dt_flush_rewrite_rules' );
