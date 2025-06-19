<?php 
function lfb_block_base_cpt() {
    $labels = array(
        'name'                  => _x('Block Posts', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('Block Post', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Block Posts', 'textdomain'),
        'name_admin_bar'        => __('Block Post', 'textdomain'),
        'archives'              => __('Block Post Archives', 'textdomain'),
        'attributes'            => __('Block Post Attributes', 'textdomain'),
        'parent_item_colon'     => __('Parent Block Post:', 'textdomain'),
        'all_items'             => __('All Block Posts', 'textdomain'),
        'add_new_item'          => __('Add New Block Post', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'new_item'              => __('New Block Post', 'textdomain'),
        'edit_item'             => __('Edit Block Post', 'textdomain'),
        'update_item'           => __('Update Block Post', 'textdomain'),
        'view_item'             => __('View Block Post', 'textdomain'),
        'view_items'            => __('View Block Posts', 'textdomain'),
        'search_items'          => __('Search Block Post', 'textdomain'),
        'not_found'             => __('Not found', 'textdomain'),
        'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
        'featured_image'        => __('Featured Image', 'textdomain'),
        'set_featured_image'    => __('Set featured image', 'textdomain'),
        'remove_featured_image' => __('Remove featured image', 'textdomain'),
        'use_featured_image'    => __('Use as featured image', 'textdomain'),
        'insert_into_item'      => __('Insert into Block Post', 'textdomain'),
        'uploaded_to_this_item' => __('Uploaded to this Block Post', 'textdomain'),
        'items_list'            => __('Block Posts list', 'textdomain'),
        'items_list_navigation' => __('Block Posts list navigation', 'textdomain'),
        'filter_items_list'     => __('Filter Block Posts list', 'textdomain'),
    );
    $args = array(
        'label'                 => __('Block Post', 'textdomain'),
        'description'           => __('Custom post type for block-based content', 'textdomain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies'            => array('category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-layout',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Enables Gutenberg editor
    );
    register_post_type('lead-form-builder', $args);
}
add_action('init', 'lfb_block_base_cpt', 0);


function restrict_blocks_to_cpt($allowed_blocks, $post) {    
        if ('lead-form-builder' === $post->post->post_type) {
            return array(
                'core/paragraph',
                'create-block/lead-form-builder', // Replace with your block's name
            );
        }
  
        return $allowed_blocks;
    }
    add_filter('allowed_block_types_all', 'restrict_blocks_to_cpt', 10, 2);
    