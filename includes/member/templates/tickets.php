<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$empfohlen_setting_options = get_option( 'emp_setting' );
$emp_currency = $empfohlen_setting_options['emp_currency']; // Currency
$current_user = wp_get_current_user();
$userData = $current_user->data;
// $user_groups = get_the_terms( (int) $userData->ID, 'user-group');

 $tickets = new WP_Query( 
 			array( 
 				'post_type' => 'ticket', 
 				'posts_per_page' => 10 ,
 				'meta_query' => array(
					array(
						'key'     => 'member_id',
            'value'   => $userData->ID,
					),
				),
 			) 
 	);
  



global $_SESSION;
$success   =  isset($_SESSION['success'])?$_SESSION['success']:'';
$error     =  isset($_SESSION['error'])?($_SESSION['error']):'';

if(isset($_SESSION['success'])){ unset($_SESSION['success']); } 
if(isset($_SESSION['error'])){ unset($_SESSION['error']); } 

// echo "<pre> success "; print_r( $success ); echo "</pre> ";  
// echo "<pre> error "; print_r( $error ); echo "</pre> ";  


 
 // echo "<pre> tickets "; print_r( $tickets->posts ); echo "</pre> ";  
 // exit; 
 
  
?>

<?php /* ?>
<div class="row p_ticket_form_cont">
  <dv class="col-md-12">
    <button class="btn btn-sm btn_small p_ticket_form_display wauto capitalize">Add new</button>
  </dv>


<div class="p_ticket_form hidden">
  <div class="col-md-12">
    <form action="" method="post" class="empfohlen_form empfohlen_form_ticket" id="empfohlen_form_ticket">
   
   <div class="field-wrapper">
      <label for="tf_title"><?php _e("Ticket Title","emp"); ?></label>
      <input id="tf_title" name="tf_title" type="text"/>
   </div>

   <div class="field-wrapper">
      <label for="tf_title"><?php _e("Description","emp"); ?></label>
      <div class="content_edit">
        <?php
        $content = ''; //This content gets loaded first.';
        $editor_id = 'tf_content';
        wp_editor( $content, $editor_id );
        ?>
      </div>
   </div>

   <div class="field-wrapper">
     <button class="btn btn-sm btn_small ticket_form_submit wauto capitalize">Submit</button>
   </div>

   <input type="hidden" name="emp_submit_ticket_nonce" value="<?php echo wp_create_nonce('emp-submit-ticket-nonce'); ?>"/>
   <input type="hidden" name="action" value="submit_ticket" />

  </form>
  </div>
  
</div>
</div>
<?php  */ ?>




<!-- <div class="tickettList content_bg">
  	<div class="row ticket_header ticket_item">
  			<div class="t_cell t_id dinline">ID</div>
        <div class="t_cell t_status dinline">Status</div>
  			<div class="t_cell t_title dinline">Title</div>
  			<div class="t_cell t_date dinline ">Date</div>
  	</div>
  <?php
	 	// while ( $tickets->have_posts() ) : $tickets->the_post(); 	  
	 	// 	 include(EMPFOHLEN_DIR.'public/partials/member/ticket_row.php');
	  // endwhile;    
  ?>
</div> -->
<!-- projectList -->




<div class="ticketList content_bg">
  <h3 class="dinline p_task_heading"><?php _e('My Tickets','empfohlen');?></h3>
   <div class="ticket_list">
       <?php
        while ( $tickets->have_posts() ) : $tickets->the_post();     
          include(EMPFOHLEN_DIR.'public/partials/member/ticket_row_new.php');
        endwhile;    
      ?>
   </div>
</div>




 


<style type="text/css">
 .col.tick_infoButton {
    width: auto;
    display: inline-block;
    padding: 20px 10px;
 }
.t_content_title{ margin: 10px 0px;  }
.t_content.color_white{ padding: 10px 20px;  }
.t_content.color_white *{ color: white !important;  }
.ticket_item.t_item.show_description{ background: white; }
.ticket_item.t_item.show_description .tic_body{ 
   display: block !important;
    color: #fff;
    background: rgba(0,0,0,.5);
    padding: 10px;
    font-size: .875em;
    margin: 0 5px;
    margin-bottom: 10px;
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    -moz-transition: max-height .3s ease-in-out;
    -webkit-transition: max-height .3s ease-in-out;
    -o-transition: max-height .3s ease-in-out;
    -ms-transition: max-height .3s ease-in-out;
    transition: max-height .3s ease-in-out;
}
.ticket_item.t_item {
    background: rgba(0,0,0,.04);
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    margin-bottom: 10px;
    margin: 10px 5px;
}
.ticket_item.t_item {
    background: rgba(0,0,0,.04);
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    margin-bottom: 10px;
    margin: 10px 5px;
}
.col.tick_id {
    padding: 20px 10px;
    min-width: 90px;
    display: inline-block;
}
.col.tick_status {
    padding: 20px 10px;
    min-width: 90px;
    display: inline-block;
}
.col.tick_title {
    display: inline-block;
    cursor: pointer;
    width: auto;
    font-size: 1.065em;
    margin-right: 10px;
    font-weight: 500;
}
.col.tick_date {
    width: auto;
    display: inline-block;
    float: right;
    padding: 20px;
}
.tic_body { display: none; }
.col.tick_id span {
    color: #fff;
    background: #509753;
}
.col.tick_id span, .col.tick_status span {
    padding: 0px 8px;
    display: inline-block;
    width: 100%;
    font-weight: 600;
    font-size: .875em;
    color: #fff;
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    line-height: 25px;
    text-align: center;
}
.col.tick_status span { background: #302b63; }
.t_item.ticket_item.status_resolved { background: #9e9e9e80; }
.tick_infoButton span.expand {
    background-image: url(<?php echo EMPFOHLEN_URI; ?>images/icon-expand_job.svg);
    background-position: right 2px center;
    background-repeat: no-repeat;
    background-size: 21px;
    background-color: #fff;
    border-radius: 20px;
    padding: 5px 40px 5px 8px;
    font-size: 14px;
} 
.tick_infoButton span.collapse {
    display: none;
    background-image: url(<?php echo EMPFOHLEN_URI; ?>images/icon-collapse_job.svg);
    background-position: right 2px center;
    background-repeat: no-repeat;
    background-size: 21px;
    border-radius: 20px;
    padding: 5px 40px 5px 8px;
    font-size: 14px;
    background-color: rgba(0,0,0,.1);
}

.ticket_item.t_item.show_description .tick_infoButton span.expand{ display: none; }
.ticket_item.t_item.show_description .tick_infoButton span.collapse { display: block; }


 /*
 .t_item.ticket_item.status_resolved {
    background: #9e9e9e80;
}
  .ticket_info{ cursor: pointer;  }
  .t_status{ text-transform: capitalize; }
  .tickettList{   
    width: 100%;
    display: table;
  }
 .t_item.ticket_item {
    background: rgba(0,0,0,.04);
    border-radius: 10px;
    background-clip: padding-box;
    border-bottom: 1px solid #9E9E9E;
}
 
.ticket_item.row {
      margin-bottom: 15px;
    margin-right: 10px;
    margin-left: 10px;
}
.ticket_item .t_id {
    width: 10%;
    display: inline-block; 
    padding: 10px;
}
.ticket_item .t_title { width: 50%; }
.ticket_item .t_status { width: 20%; }
.ticket_item .t_date { width: 15%; }
.t_cell{ padding: 10px;  }


.ticket_item .t_content{  
  display: none;

    margin-bottom: 10px;
    margin-right: 10px;
    margin-left: 10px;
    padding: 10px;
    color: #fff;
    background: rgba(0,0,0,.5);
    border-radius: 5px;
     }
.ticket_item.show_description .t_content{ 
    display: block;
  }

.p_ticket_form{ 
    width: 100%;
    margin: 20px 0px;
     }

.p_ticket_form_display{ margin: 20px 0px; }
.empfohlen_form { 
    margin: 20px 0px;
    background: #f3e9e5;
    padding: 10px 10px;
    border-radius: 10px;
}
.empfohlen_form label{ 
  font-weight: bold; 
  margin: 10px 0px;  
}
.t_content_title{  margin: 0px; }
.t_content_description, .t_content_description *{ color: white !important; }
*/ 

</style>