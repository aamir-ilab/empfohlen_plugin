<?php if ( ! defined( 'ABSPATH' ) ) exit; 


$current_user = wp_get_current_user();
$user_currency = EmpHelper::getUserCurrency($current_user->ID);

$total_user_price =  get_field('balance_amount',  'user_'.$userData->ID ); 

$total_user_price = empty($total_user_price) ? 0 : $total_user_price; 

$empfohlen_setting_options = get_option('emp_setting');
$emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
$payout_url = get_permalink($emp_member_dashboard).'?tmpl=pay';


?>



 

<div class="earning_box_cont p_10 content_bg mt_20">
  <div class="earning_box">
    <div class="earn_title">Credit earned</div>
    <div class="earning_text mt_20">
      <?php echo EmpHelper::currency_to_code($user_currency).' '.number_format($total_user_price,2)?>
    </div>
    <div class="earning_payout mt_20"><a class="earning_payout_link" href="<?php echo $payout_url; ?>"><?php _e('Pay out credit','emp') ?></a></div>
  </div>
</div>






<style type="text/css">
 .earn_title {
    font-size: 1.5em;
}
.earning_text {
    font-size: 2.7em;
    text-align: center;
}
.earning_payout_link{ 
    cursor: pointer;
    background: url(<?php echo EMPFOHLEN_URI.'/images/link_button.svg'?>) no-repeat center left;
    background-size: 17px;
    line-height: 20px;
    padding-left: 22px;
    margin-bottom: 10px;
    font-size: .875em;
}
</style>

