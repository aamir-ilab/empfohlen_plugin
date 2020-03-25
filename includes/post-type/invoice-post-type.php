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
		'name'                  => _x( 'Invoices', 'Post Type General Name', 'empfohlen' ),
		'singular_name'         => _x( 'Invoice', 'Post Type Singular Name', 'empfohlen' ),
		'menu_name'             => __( 'Invoice', 'empfohlen' ),
		'name_admin_bar'        => __( 'Invoice', 'empfohlen' ),
		'archives'              => __( 'Invoice Archives', 'empfohlen' ),
		'attributes'            => __( 'Invoice Attributes', 'empfohlen' ),
		'parent_item_colon'     => __( 'Parent Invoice:', 'empfohlen' ),
		'all_items'             => __( 'All Invoices', 'empfohlen' ),
		'add_new_item'          => __( 'Add New Invoice', 'empfohlen' ),
		'add_new'               => __( 'Add New Invoice', 'empfohlen' ),
		'new_item'              => __( 'New Invoice', 'empfohlen' ),
		'edit_item'             => __( 'Edit Invoice', 'empfohlen' ),
		'update_item'           => __( 'Update Invoice', 'empfohlen' ),
		'view_item'             => __( 'View Invoice', 'empfohlen' ),
		'view_items'            => __( 'View Invoices', 'empfohlen' ),
		'search_items'          => __( 'Search Invoices', 'empfohlen' ),
		'not_found'             => __( 'Invoice Not found', 'empfohlen' ),
		'not_found_in_trash'    => __( 'Invoice Not found in Trash', 'empfohlen' ),
		'featured_image'        => __( 'Featured Image', 'empfohlen' ),
		'set_featured_image'    => __( 'Set featured image', 'empfohlen' ),
		'remove_featured_image' => __( 'Remove featured image', 'empfohlen' ),
		'use_featured_image'    => __( 'Use as featured image', 'empfohlen' ),
		'insert_into_item'      => __( 'Insert into item', 'empfohlen' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'empfohlen' ),
		'items_list'            => __( 'invoices list', 'empfohlen' ),
		'items_list_navigation' => __( 'invoices list navigation', 'empfohlen' ),
		'filter_items_list'     => __( 'Filter invoices list', 'empfohlen' ),
	);
	 
	 $args = array(
            'label'                 => __( 'Invoice', 'empfohlen' ),
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






