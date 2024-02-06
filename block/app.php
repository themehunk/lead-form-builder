<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function example_static_example_static_block_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'example_static_example_static_block_init' );




function displayReactApp() { 
	$current_user = (array) wp_get_current_user()->roles;
	ob_start();
	?>
    <div id="my-react-app"></div>
	<?php return ob_get_clean();
}
// register shortcode
add_shortcode('displayReactApp', 'displayReactApp'); 

add_action('wp_enqueue_scripts', 'enq_react');
function enq_react(){

    wp_register_script('display-react',
	plugin_dir_url( __FILE__ ) . '/build/index.js',
	['wp-element'],
	rand(), // Change this to null for production
	true);
    $current_user = wp_get_current_user();
    $data = array( 
     'email' => $current_user->user_email,
     );
  wp_localize_script( 'display-react', 'object', $data ); //localize script to pass PHP data to JS
  wp_enqueue_script( 'display-react' );    
}
