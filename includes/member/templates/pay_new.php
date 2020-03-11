<?php if ( ! defined( 'ABSPATH' ) ) exit; 




$current_user = wp_get_current_user();
$userData = $current_user->data; 
$balances = get_field('balance',  'user_'.$userData->ID );
echo "<pre> balances "; print_r( $balances ); echo "</pre> ";  	
$user_currency = EmpHelper::getUserCurrency($current_user->ID);
$total_base_price = 0; 
$total_user_price = 0; 

if( !empty($balances) ){
  foreach ($balances as $bk => $balance) {
    $base_price     =  EmpHelper::cc_tobase($balance['balance_currency'],$balance['balance_value']);
    $balances[$bk]['base_price'] =  $base_price;
    $total_base_price +=  $base_price;
  }
}

if(!empty($total_base_price)){
  
  $total_user_price = EmpHelper::cc_base_to_currency($user_currency,$total_base_price);
}


echo "<pre> user_currency "; print_r( $user_currency ); echo "</pre> ";   
echo "<pre> total_user_price "; print_r( $total_user_price ); echo "</pre> ";   
echo "<pre> total_base_price "; print_r( $total_base_price ); echo "</pre> ";   
echo "<pre> balances "; print_r( $balances ); echo "</pre> ";   


 $args = array(
    'post_type'              => 'withdrawl',
    'meta_query'             => array(
        array(
            'key'     => 'withdrawl_member_id',
            'value'   =>  $userData->ID,
        ),
        // array(
        //     'key'     => 'withdrawl_status',
        //     'value'   => 'pending',
        // ),
    ),
); 
$withdrawl_query = new WP_Query( $args );
$withdrawl_exist = $withdrawl_query->posts;



 $args = array(
    'post_type'              => 'invoice',
    'meta_query'             => array(
        array(
            'key'     => 'invoice_member_id',
            'value'   =>  $userData->ID,
        ),
    ),
); 
$invoice_query = new WP_Query( $args );
$invoice_exist = $invoice_query->posts;

// echo "<pre> withdrawl_exist "; print_r( $withdrawl_exist ); echo "</pre> ";
//echo '<p> pay </p>';
//echo"<div></div>";

echo'<div class="fusion-builder-row fusion-row ">
    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_1_3 fusion-builder-column-3 fusion-one-third fusion-column-first 2_3 credit">
        <div class="fusion-column-wrapper">
            <h3>credit</h3>
            <div class="innerBox">
                <div class="amountContainerNew payout">
                    <div class="textContainer">
                        <div class="icon"></div>
                        <div class="text">This is the total of your earnings already checked:</div>
                    </div>
                    <div class="value">
                        <div class="defaultContent">0,00 €</div>
                        <div class="animation square-spin">
                            <div class="innerAnimation"></div>
                        </div>
                    </div>
                </div>
                <div class="buttonBar">
                    <a class="button" data-toggle="modal" data-target="#payout_modal">Request a withdrawal</a>
                </div>
            </div>
        </div>
    </div>
    ';  ?>
		

    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_2_3 fusion-builder-column-2 fusion-two-third fusion-column-last 1_3 revenues">
    	<div class="fusion-column-wrapper"><h3><?php _e('Withdrawl Requests','emp');?></h3></div>
			<div class="withdrawal_list">
				<?php
				if (!empty($withdrawl_exist)) { 
						foreach ($withdrawl_exist as $wkey => $withdrawl) { 
							$withdrawl_id_code  	= get_field('withdrawl_id', $withdrawl->ID);
							$withdrawl_status			= get_field('withdrawl_status', $withdrawl->ID);
							$withdrawl_currency  	= get_field('withdrawl_currency', $withdrawl->ID);
							$withdrawl_amount			= get_field('withdrawl_amount', $withdrawl->ID);
						?>
						<div class="wdr_entry row withdrawl_item withdrawl_<?php echo $withdrawl->ID; ?>">
							<div class="wdr_head">
								<div class="col w_code"><span><?=  $withdrawl_id_code; ?></span></div>
								<div class="col w_pay"><span><?php echo EmpHelper::currency_to_code($withdrawl_currency).' '. number_format($withdrawl_amount,2); ?></span></div>
								<div class="col w_title"><span>	Withdrawl request by User: 2 amount: 5000 </span></div>
								<div class="col w_status"><span><?= $withdrawl_status ?></span></div>
							</div>
						</div>
					<?php
					}
				}
				?>
			</div>
		</div>
		<!-- fusion-layout-column end -->








		<div class="fusion-layout-column fusion_builder_column fusion_builder_column_2_3 fusion-builder-column-2 fusion-two-third fusion-column-last 1_3 revenues">
    	<div class="fusion-column-wrapper"><h3><?php _e('Invoices','emp');?></h3></div>
			<div class="withdrawal_list">
				<?php
				if (!empty($invoice_exist)) { 
						foreach ($invoice_exist as $invkey => $invoice) { 
								$invoice_task					=  (int) get_field('invoice_task_id', $invoice->ID);
								$task 								= ($invoice_task > 0)?(get_post($invoice_task)):('');
								$invoice_currency  	= get_field('invoice_currency', $invoice->ID);
								$invoice_amount			= get_field('invoice_amount', $invoice->ID);
						?>
						<div class="wdr_entry row withdrawl_item withdrawl_<?php echo $invoice->ID; ?>">
							<div class="wdr_head">
								<div class="col w_code"><span>ID: <?= $invoice->ID  ?></span></div>
								<div class="col w_pay"><span><?php echo EmpHelper::currency_to_code($invoice_currency).' '. number_format($invoice_amount,2); ?></span></div>
								<div class="col w_title">
									<span><?= $invoice->post_title ?></span>
									<?= !empty($task)?('<a href="'.get_permalink($task->ID).'">'.$task->post_title.'</a>'):''?>
								</div>
							</div>
						</div>
					<?php
					}
				}
				?>
			</div>
		</div>
		<!-- fusion-layout-column end -->

 


	
 

<?php

echo '</div> <!-- fusion-row end -->';
?>




 

<!-- Modal for payout  -->
<div class="modal fade" id="payout_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="payOutModalLabel">Pay out list</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body payout_body">
      <?php
      if (!empty($balances)){ ?>

      	<input type="hidden" id="payout_nonce" name="payout_nonce" value="<?= wp_create_nonce('withdrawl-payout-nonce'); ?>"/>
      	<div class="row">
      		<div class="col-md-12 dtable">
      			<div class="payout_currency payout_currency_header dtable_row">
      					<span class="dt_cell bold">Currency</span>
      					<span class="dt_cell bold">Amount</span>
      					<span class="dt_cell bold">Action</span>
      			</div>
      		</div>
	      </div>

      	<?php
      	foreach ($balances as $balance) {
      		// echo "<pre> balance  "; print_r( $balance ); echo "</pre> ";  


      		?>
      		<div class="row">
      				<div class="col-md-12 dtable">
	      				 	<div class="payout_currency dtable_row">
	      				 		<!-- <span class="dt_cell bold">Currency</span>
		      					<span class="dt_cell bold">Amount</span>
		      					<span class="dt_cell bold">Action</span> -->

	      				 		<span class="dt_cell bold"><?php echo $balance['balance_currency'];?></span>
	      						<span class="dt_cell bold"><?php echo $balance['balance_value'];?></span>
	      						<span class="dt_cell bold">
	      								<button type="button" data-currency="<?php echo $balance['balance_currency'];?>" class="payout_btn btn-primary">Payout</button>
	      								<!-- <span class="processing"></span> -->
	      						</span>
	      				 	</div>
      		 		</div>
      		</div>
      	<?php
      	}
      }
      ?>
      </div>
       
    </div>
  </div>
</div>






<style type="text/css">
.wdr_entry {
		    margin: 10px 5px;
		background: rgba(0,0,0,.04);
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    margin-bottom: 10px;
	}

.col.w_code {
    padding: 20px 10px;
    min-width: 90px;
    display: inline-block;
}

.col.w_pay {
    padding: 20px 10px;
    min-width: 90px;
    display: inline-block;
}

.col.w_title {
    width: auto;
    display: inline-block;
        font-size: 1.065em;
    margin-right: 10px;
    font-weight: 500;
}

.col.w_status {
    width: auto;
    display: inline-block;
    float: right;
    padding: 20px;
}
.col.w_status span {
    background: #ffa400;
    color: white;
    padding: 4px 8px;
    border-radius: 9px;
    text-transform: capitalize;
    font-weight: 500;
}
.col.w_pay span,
.col.w_code span {
    padding: 0px 8px;
    display: inline-block;
    width: 100%;
    font-weight: 600;
    font-size: .875em;
    color: #fff;
    background: #302b63;
    -webkit-border-radius: 25px;
    -moz-border-radius: 25px;
    border-radius: 25px;
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    line-height: 25px;
    text-align: center;
}


 .col.w_code span{
    color: #fff;
    background: #509753;
}

</style>






<?php 
