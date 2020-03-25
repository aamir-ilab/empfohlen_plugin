<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

 
<div class="project_info_cont">
 <div class="project "> 

     <div class="sp_header">
            <h3><?php echo get_the_title(); ?></h3>
    </div>

    <div class="sp_padd2">
         <h3><?php 
        $empfohlen_setting_options = get_option( 'emp_setting' );
        $emp_member_dashboard = (int) $empfohlen_setting_options['emp_member_dashboard'];  
        $member_dashboard_url = get_permalink($emp_member_dashboard);
        echo  sprintf(__('Your are not allowed to access this project click <a href="%s"> here to go to Dashboard</a>', 'empfohlen'),$member_dashboard_url);      

         ?></h3>
    </div>

 


 <?php
  // echo "<pre> post "; print_r( $post ); echo "</pre> ";  
 ?>
 </div>
</div>
<!-- fussion column Wrapper-->



 


<style type="text/css" scoped="scoped">
.project_info_cont {
    margin-top: 70px;
    padding-bottom: 150px;
}
.project_info_cont .project { 
   color: #fff;
   border-top-left-radius: 20px;
   border-top-right-radius: 20px;
   background-clip: padding-box;
   background: linear-gradient(to bottom, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 100%);
   filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#66000000", endColorstr="#99000000", GradientType=0);
}
.sp_header{  padding: 20px; }
.sp_header h3 {
    color: white;
    display: block;
    margin: 0px;
    color: white;
    text-align: center;
    padding: 18px;
    font-weight: 400;
    font-size: 1.863em;
}
.sp_padd2{
   padding: 20px;
    margin: 10px 0;
    text-align: center;
} 
.sp_padd2 a{ color: #FF9800; }
.sp_padd2 h3{ color: white;  }

</style>
