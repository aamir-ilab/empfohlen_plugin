<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// Register Custom Taxonomy
function skills_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Skills', 'Taxonomy General Name', 'emp' ),
		'singular_name'              => _x( 'Skill', 'Taxonomy Singular Name', 'emp' ),
		'menu_name'                  => __( 'Skills', 'emp' ),
		'all_items'                  => __( 'All skills', 'emp' ),
		'parent_item'                => __( 'Parent skill', 'emp' ),
		'parent_item_colon'          => __( 'Parent skill:', 'emp' ),
		'new_item_name'              => __( 'New skill Name', 'emp' ),
		'add_new_item'               => __( 'Add New skill', 'emp' ),
		'edit_item'                  => __( 'Edit skill', 'emp' ),
		'update_item'                => __( 'Update skill', 'emp' ),
		'view_item'                  => __( 'View skill', 'emp' ),
		'separate_items_with_commas' => __( 'Separate skill with commas', 'emp' ),
		'add_or_remove_items'        => __( 'Add or remove skills', 'emp' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'emp' ),
		'popular_items'              => __( 'Popular skills', 'emp' ),
		'search_items'               => __( 'Search skills', 'emp' ),
		'not_found'                  => __( 'Not Found', 'emp' ),
		'no_terms'                   => __( 'No skills', 'emp' ),
		'items_list'                 => __( 'skills list', 'emp' ),
		'items_list_navigation'      => __( 'skills list navigation', 'emp' ),
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
    		'Skills', 'EMP Skills', 
    		'manage_options', 
    		'edit-tags.php?taxonomy=skill'); 
}
