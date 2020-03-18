<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Register Custom Taxonomy
function skills_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Device', 'Taxonomy General Name', 'emp' ),
		'singular_name'              => _x( 'Devices', 'Taxonomy Singular Name', 'emp' ),
		'menu_name'                  => __( 'Devices', 'emp' ),
		'all_items'                  => __( 'All Device', 'emp' ),
		'parent_item'                => __( 'Parent Device', 'emp' ),
		'parent_item_colon'          => __( 'Parent Device:', 'emp' ),
		'new_item_name'              => __( 'New Device Name', 'emp' ),
		'add_new_item'               => __( 'Add New Device', 'emp' ),
		'edit_item'                  => __( 'Edit Device', 'emp' ),
		'update_item'                => __( 'Update Device', 'emp' ),
		'view_item'                  => __( 'View Device', 'emp' ),
		'separate_items_with_commas' => __( 'Separate Device with commas', 'emp' ),
		'add_or_remove_items'        => __( 'Add or remove Device', 'emp' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'emp' ),
		'popular_items'              => __( 'Popular Device', 'emp' ),
		'search_items'               => __( 'Search Device', 'emp' ),
		'not_found'                  => __( 'Not Found', 'emp' ),
		'no_terms'                   => __( 'No Device', 'emp' ),
		'items_list'                 => __( 'Devices list', 'emp' ),
		'items_list_navigation'      => __( 'Devices list navigation', 'emp' ),
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
