<?php
/**
 * Custom Post Type Invoice
 * Created by creativedev.
 * User: arsalan
 * Date: 28/01/2020
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


function empfohlen_get_invoice_capabilities() {

    $caps = array(
        // meta caps (don't assign these to roles)
        // 'create_post'            => 'create_invoice',
        'edit_post'              => 'edit_invoice',
        'read_post'              => 'read_invoice',
        'delete_post'            => 'delete_invoice',
        // primitive/meta caps
        'create_posts'           => 'create_invoices',
        // primitive caps used outside of map_meta_cap()
       'edit_posts'             => 'edit_invoices',
       'publish_posts'          => 'publish_invoices',
        // primitive caps used inside of map_meta_cap()
        'read'                   => 'read',
        'delete_posts'           => 'delete_invoices',
    );
    return apply_filters( 'empfohlen_get_invoice_capabilities', $caps );
}

if( !function_exists( 'empfohlen_invoice_post_type' ) ){
    function empfohlen_invoice_post_type(){
         $labels = array(
		'name'                  => _x( 'Invoices', 'Post Type General Name', 'emp' ),
		'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'emp' ),
		'menu_name'             => __( 'Invoice', 'emp' ),
		'name_admin_bar'        => __( 'Invoice', 'emp' ),
		'archives'              => __( 'Invoice Archives', 'emp' ),
		'attributes'            => __( 'Invoice Attributes', 'emp' ),
		'parent_item_colon'     => __( 'Parent Invoice:', 'emp' ),
		'all_items'             => __( 'All Invoices', 'emp' ),
		'add_new_item'          => __( 'Add New Invoice', 'emp' ),
		'add_new'               => __( 'Add New Invoice', 'emp' ),
		'new_item'              => __( 'New Invoice', 'emp' ),
		'edit_item'             => __( 'Edit Invoice', 'emp' ),
		'update_item'           => __( 'Update Invoice', 'emp' ),
		'view_item'             => __( 'View Invoice', 'emp' ),
		'view_items'            => __( 'View Invoices', 'emp' ),
		'search_items'          => __( 'Search Invoices', 'emp' ),
		'not_found'             => __( 'Invoice Not found', 'emp' ),
		'not_found_in_trash'    => __( 'Invoice Not found in Trash', 'emp' ),
		'featured_image'        => __( 'Featured Image', 'emp' ),
		'set_featured_image'    => __( 'Set featured image', 'emp' ),
		'remove_featured_image' => __( 'Remove featured image', 'emp' ),
		'use_featured_image'    => __( 'Use as featured image', 'emp' ),
		'insert_into_item'      => __( 'Insert into item', 'emp' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'emp' ),
		'items_list'            => __( 'invoices list', 'emp' ),
		'items_list_navigation' => __( 'invoices list navigation', 'emp' ),
		'filter_items_list'     => __( 'Filter invoices list', 'emp' ),
	);
	 
	 $args = array(
            'label'                 => __( 'Invoice', 'emp' ),
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_in_menu' => 'empfohlen',
            'show_ui' => true,
            'query_var' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'capabilities'    => empfohlen_get_invoice_capabilities(),
            'menu_icon' => 'dashicons-businessman',
            'menu_position' => 14,
            'supports'              => array( 'title', 'comments' ),
        );


	register_post_type( 'invoice', $args );

    }
}
add_action( 'init', 'empfohlen_invoice_post_type' );




// add_action('admin_menu', 'dmin_menu_invoice'); 
// function dmin_menu_invoice() { 
//     add_submenu_page(
//     		'empfohlen', 
//     		'Invoices', 'EMP Invoices', 
//     		'manage_options', 
//     		'edit.php?post_type=invoice'); 
// }





function add_invoice_caps() {
    // gets the administrator role
    $admins = get_role( 'administrator' );

    $admins->add_cap( 'edit_invoice' ); 
    $admins->add_cap( 'read_invoice' ); 
    $admins->add_cap( 'delete_invoice' ); 
    $admins->add_cap( 'create_invoices' ); 
    $admins->add_cap( 'edit_invoices' ); 
    $admins->add_cap( 'publish_invoices' ); 
    $admins->add_cap( 'delete_invoices' ); 
    $admins->add_cap( 'publish_invoices' ); 

}
add_action( 'admin_init', 'add_invoice_caps');






