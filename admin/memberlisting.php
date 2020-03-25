<?php

/*
Plugin Name: WP_List_Table Class Example
Plugin URI: https://www.sitepoint.com/using-wp_list_table-to-create-wordpress-admin-tables/
Description: Demo on how WP_List_Table Class works
Version: 1.0
Author: Collins Agbonghama
Author URI:  https://w3guy.com
*/

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Customers_List extends WP_List_Table {

	public $total_members = 0; 

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Members', 'empfohlen' ), //singular name of the listed records
			'plural'   => __( 'Member', 'empfohlen' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		]);

	}


	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_customers( $per_page = 5, $page_number = 1 ) {

		// global $wpdb;
		// $sql = "SELECT * FROM {$wpdb->prefix}customers";
		// if ( ! empty( $_REQUEST['orderby'] ) ) {
		// 	$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
		// 	$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		// }
		// $sql .= " LIMIT $per_page";
		// $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		// $result = $wpdb->get_results( $sql, 'ARRAY_A' );




		// WP_User_Query arguments
		$args = array(
			'role'           => 'member',
			'number'         =>  $per_page,
			'offset'         =>  ($page_number - 1) * $per_page,
			'count_total'    => true,
		);

		if (!empty($_REQUEST['order'])){ $args['order'] = $_REQUEST['order']; }
		if (!empty($_REQUEST['orderby'])){ $args['orderby'] = $_REQUEST['orderby']; }

		// The User Query
		$member_query = new WP_User_Query( $args );

		$result = array();
		if (!empty($member_query->results)){ $result = $member_query->results; }  

		// echo "<pre> this "; print_r( self ); echo "</pre> ";  
		// $this->total_members = 100; //$member_query->total_members;
		// echo "<pre> member_query "; print_r( $member_query ); echo "</pre> ";  

		return array( 'result' => $result, 'total_count' => $member_query->total_members ); 
	}


	/**
	 * Delete a customer record.
	  @param int $id customer ID
	*/

	// public static function delete_customer( $id ) {
	// 	global $wpdb;
	// 	$wpdb->delete(
	// 		"{$wpdb->prefix}customers",
	// 		[ 'ID' => $id ],
	// 		[ '%d' ]
	// 	);
	// }


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		// return " record_count() ";
		// global $wpdb;
		// $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}customers";
		// return $wpdb->get_var( $sql );
		return 112233; 
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No member avaliable.', 'empfohlen' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		 
		 // echo "<pre> item "; print_r( $item ); echo "</pre> "; 
		 // echo "<pre> column_name "; print_r( $column_name ); echo "</pre> "; 
		switch ( $column_name ) {
			case 'id': 
				 return $item->ID; 
			case 'name':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item->ID
		);
	}


	/**
	 * Method for name column
	 * @param array $item an array of DB data
	 * @return string
	 */
	function column_name( $item ) {
		$delete_nonce = wp_create_nonce( 'emp_delete_member' );
		$title = '<strong>'.$item->data->display_name.'</strong>';
		$actions = [
			'delete' => sprintf('<a href="?page=%s&action=%s&member=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item->ID), $delete_nonce)
		];
		return $title . $this->row_actions( $actions );
	}


	function column_email( $item ) {
		return $item->data->user_email;
	}


	function column_group( $item ) {
		return $item->data->user_email;
	}

	function column_skill( $item ) {
		// $user_skill = wp_get_object_terms($item->ID, 'skill');  
		// echo "<pre>  item->ID "; print_r( $item->ID ); echo "</pre> ";  
		$user_skill = get_the_terms( (int) $item->ID, 'skill');
		$ret = ''; 
		 if(!empty($user_skill)){
		 	foreach ($user_skill as $skill) {
		 		$ret .= '<span class="user_skill">'.$skill->name.'</span>'; 
		 	}
		 }

		// echo "<pre> user_skill "; print_r( $user_skill ); echo "</pre> ";  
		return $ret; 
	}


	function column_balance( $item ) {
		// $user_skill = wp_get_object_terms($item->ID, 'skill');  
		// echo "<pre>  item->ID "; print_r( $item->ID ); echo "</pre> ";  
		// $balance = acf_get_fields($item->ID);
		// $balances = get_field('balance',  'user_'.$item->ID );
		 

		
// // this gets the repeater an all rows in an array
// $repeater = get_field('balance', 'user_'.$item->ID );
//  echo "<pre> repeater "; print_r( $repeater ); echo "</pre> ";  
// // create a new row, should be same format as returned by above
// $new_row = array(
// 'balance_currency' => 'JPY ',
// 'balance_value' => '2100 '
// );
// // push the new row onto the beginning of the repeater
// array_unshift($repeater, $new_row);
// // you can use update field to update the entire repeater at once
// update_field('balance', $repeater,'user_'.$item->ID );



// $member_balance = array();
// $new_row = array(
// 'balance_currency' => 'JPY',
// 'balance_value' => (int) 123
// );
// array_unshift($member_balance, $new_row);
// update_field('balance', $member_balance,'user_'.$item->ID );




		$ret = ''; 
		$balances = get_field('balance',  'user_'.$item->ID );
		if (!empty($balances)){
			foreach ($balances as $balance) {
				$ret .= '<div class="balance">';
				$ret .= '<span>'.$balance['balance_currency'].' '.$balance['balance_value'].'</span>'; 
				$ret .= '</div>';				 
			}
		}


		// if  ( $item->ID == 2 ){
		// 	echo "<pre> user_item->ID "; print_r( 'user_'.$item->ID  ); echo "</pre> "; 
		// 	echo "<pre> balances "; print_r( $balances  ); echo "</pre> "; 
		// }


		return $ret;
		 
		// $ret = ''; 
		//  if(!empty($user_skill)){
		//  	foreach ($user_skill as $skill) {
		//  		$ret .= '<span class="user_skill">'.$skill->name.'</span>'; 
		//  	}
		//  }
		// return $ret; 
	}


	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'id'    	=> __('ID', 'empfohlen'),
			'name'    => __('Name', 'empfohlen'),
			'email' 	=> __('Email', 'empfohlen'),
			'group' 	=> __('Group', 'empfohlen'),
			'skill' 	=> __('Skill', 'empfohlen'),
			'balance' 	=> __('Balance', 'empfohlen')			 
		];
		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'id' => array( 'id', true ),
			'name' => array( 'name', true ),
			'email' => array( 'email', true )
		);
		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 * @return array
	 */
	public function get_bulk_actions() {
		// $actions = [ 'bulk-delete' => 'Delete' ];
		// return $actions;
		return ''; 
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'customers_per_page', 5 );
		$current_page = $this->get_pagenum();
		

		$result_data = self::get_customers( $per_page, $current_page );
		$this->items = $result_data['result'];  
		$total_items = $result_data['total_count']; 


		// $total_items  = self::record_count();
		// $total_items  = 100;

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );


	}

	 

}


class Member_Listing {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $customers_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'member_listing_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function member_listing_menu() {

		 // echo "<pre>  "; print_r( 'member_listing_menu' ); echo "</pre> ";  exit; 
		$hook = add_submenu_page(
	        'empfohlen',
	        __( 'Empfohlen Member Listing', 'empfohlen' ),
	        __( 'Member Listing', 'empfohlen' ),
	        'manage_emp_menu_member_listing',
	        'empfohlen-member-listing',
	        array( $this, 'member_listing_settings_page' )
    	);
		// $hook = add_menu_page(
		// 	'Sitepoint WP_List_Table Example',
		// 	'SP WP_List_Table',
		// 	'manage_options',
		// 	'wp_list_table_class',
		// 	[ $this, 'plugin_settings_page' ]
		// );
		add_action( "load-$hook", [ $this, 'screen_option' ] );
	}


	/**
	 * Plugin settings page
	 */
	public function member_listing_settings_page() {
		?>
		<div class="wrap">
			<h2>Member List</h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->customers_obj->prepare_items();
								$this->customers_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/** Screen options **/
	public function screen_option() {
		$option = 'per_page';
		$args   = [
			'label'   => 'Members',
			'default' => 5,
			'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );
		$this->customers_obj = new Customers_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}



if ( is_admin() )
	// $member_listing = new Member_Listing::get_instance();
	$member_listing = new Member_Listing();
// add_action( 'plugins_loaded', function () { SP_Plugin::get_instance(); });
