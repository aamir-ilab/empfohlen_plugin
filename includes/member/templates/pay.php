<?php if ( ! defined( 'ABSPATH' ) ) exit; 




$current_user = wp_get_current_user();
$userData = $current_user->data; 
$balances = get_field('balance',  'user_'.$userData->ID );
// echo "<pre> balances "; print_r( $balances ); echo "</pre> ";  	

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

    
    <div class="fusion-layout-column fusion_builder_column fusion_builder_column_2_3 fusion-builder-column-2 fusion-two-third fusion-column-last 1_3 revenues">
        <div class="fusion-column-wrapper">
            <h3>Withdrawl Requests </h3>'; 


////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '<div class="">

</div>';

////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '

            <table class="withdrawal_list w100">'; 
							if (!empty($withdrawl_exist)) { ?>

								<thead class="withdrawl">
										<tr>
											<th class="text_left">ID </th>
											<th class="text_left">Title</th>
											<th class="text_left">Status</th>
											<th class="text_left">Currency</th>
											<th class="text_left">Amount</th>
										</tr>
								</thead>
								<tbody>
								<?php
								foreach ($withdrawl_exist as $wkey => $withdrawl) { 
									$withdrawl_id_code  	= get_field('withdrawl_id', $withdrawl->ID);
									$withdrawl_status			= get_field('withdrawl_status', $withdrawl->ID);
									$withdrawl_currency  	= get_field('withdrawl_currency', $withdrawl->ID);
									$withdrawl_amount			= get_field('withdrawl_amount', $withdrawl->ID);
								?>
									<tr class="withdrawl withdrawl_<?php echo $withdrawl->ID; ?>">
											<td><?= $withdrawl_id_code .'('.$withdrawl->ID.')' ?></td>
											<td><?= $withdrawl->post_title ?></td>
											<td><?= $withdrawl_status ?></td>
											<td><?= $withdrawl_currency ?></td>
											<td><?= $withdrawl_amount ?></td>
									</tr>		
								<?php
								} 
								?>
								</tbody>
							<?php
							}
				echo  '           
		      </table>
		  </div>
		</div> <!-- fusion-layout-column end -->'; 




		echo '
		<div class="fusion-layout-column fusion_builder_column fusion_builder_column_2_3 fusion-builder-column-2 fusion-two-third fusion-column-last 1_3 revenues">
        <div class="fusion-column-wrapper">
            <h3>Invoices </h3>
            <table class="invoice_list w100">'; 
							if (!empty($invoice_exist)) { ?>

								<thead class="invoice">
										<tr>
											<th class="text_left">ID </th>
											<th class="text_left">Title</th>
											<th class="text_left">Task</th>
											<th class="text_left">Currency</th>
											<th class="text_left">Amount</th>
										</tr>
								</thead>
								<tbody>
								<?php
								foreach ($invoice_exist as $invkey => $invoice) { 
									$invoice_task					=  (int) get_field('invoice_task_id', $invoice->ID);
									$task 								= ($invoice_task > 0)?(get_post($invoice_task)):('');
									$invoice_currency  	= get_field('invoice_currency', $invoice->ID);
									$invoice_amount			= get_field('invoice_amount', $invoice->ID);
								?>
									<tr class="withdrawl withdrawl_<?php echo $withdrawl->ID; ?>">
											<td><?= $invoice->ID  ?></td>
											<td><?= $invoice->post_title ?></td>
											<td><?= !empty($task)?('<a href="'.get_permalink($task->ID).'">'.$task->post_title.'</a>'):''?></td>
											<td><?= $invoice_currency ?></td>
											<td><?= $invoice_amount ?></td>
									</tr>		
								<?php
								} 
								?>
								</tbody>
							<?php
							}
				echo  '           
		      </table>
		  </div>
		</div> <!-- fusion-layout-column end -->'; 



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

<?php 