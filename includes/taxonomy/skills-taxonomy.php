<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Register Custom Taxonomy
function skills_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Device', 'Taxonomy General Name', 'empfohlen' ),
		'singular_name'              => _x( 'Devices', 'Taxonomy Singular Name', 'empfohlen' ),
		'menu_name'                  => __( 'Devices', 'empfohlen' ),
		'all_items'                  => __( 'All Device', 'empfohlen' ),
		'parent_item'                => __( 'Parent Device', 'empfohlen' ),
		'parent_item_colon'          => __( 'Parent Device:', 'empfohlen' ),
		'new_item_name'              => __( 'New Device Name', 'empfohlen' ),
		'add_new_item'               => __( 'Add New Device', 'empfohlen' ),
		'edit_item'                  => __( 'Edit Device', 'empfohlen' ),
		'update_item'                => __( 'Update Device', 'empfohlen' ),
		'view_item'                  => __( 'View Device', 'empfohlen' ),
		'separate_items_with_commas' => __( 'Separate Device with commas', 'empfohlen' ),
		'add_or_remove_items'        => __( 'Add or remove Device', 'empfohlen' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'empfohlen' ),
		'popular_items'              => __( 'Popular Device', 'empfohlen' ),
		'search_items'               => __( 'Search Device', 'empfohlen' ),
		'not_found'                  => __( 'Not Found', 'empfohlen' ),
		'no_terms'                   => __( 'No Device', 'empfohlen' ),
		'items_list'                 => __( 'Devices list', 'empfohlen' ),
		'items_list_navigation'      => __( 'Devices list navigation', 'empfohlen' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'skill', array( 'project' ), $args );

}
add_action( 'init', 'skills_taxonomy', 0 );




add_action('admin_menu', 'skills_admin_menu'); 
function skills_admin_menu() { 
    add_submenu_page(
    		'empfohlen', 
    		'Devices', 
    		'EMP Devices List', 
    		'manage_options', 
    		'edit-tags.php?taxonomy=skill'); 
}
