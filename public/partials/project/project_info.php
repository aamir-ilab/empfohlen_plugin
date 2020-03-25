<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

 
<div class="project_info_cont">
 <div class="project "> 

     <div class="sp_header">
            <h3><?php echo get_the_title(); ?></h3>
    </div>

    <div class="hidden sp_processing">
        <div class="sp_loading">
            <p><?php _e('Creating task please wait...','empfohlen') ?></p>
            <div class="sp_loading_animation">
                <div class="sp_square_box"></div>
            </div>
        </div>
    </div>

     <div class="hidden sp_message">
        <div class="sp_padd text_center">
            <div class="message sp_title"></div>
            <div class="message_data sp_padd"></div>
        </div>
    </div>

    <div class="sp_content">     
        <div class="sp_pintro sp_padd">
            <div class="sp_title"><?php _e('Project Intro','empfohlen'); ?></div>
            <?php echo $project_intro; ?>        
        </div>
     
        <div class="sp_description sp_padd">
            <div class="sp_title"><?php _e('Project Description','empfohlen'); ?></div>
            <?php echo $description; ?>         
        </div>

        <div class="sp_add_info sp_padd">
            <div class="sp_title"><?php _e('Project Additional Information','empfohlen'); ?></div>
            <p><?php echo $additional_information; ?></p>         
        </div>

        <div class="sp_footer_action">
            <a class="btn btn-sm sp_start_btn "><?php _e('Start Project','empfohlen');?></a>
        </div>

        <input type="hidden" id="project_start_nonce" name="project_start_nonce" value="<?= wp_create_nonce('project-start-nonce'); ?>"/>
    </div>


 <?php
  // echo "<pre> post "; print_r( $post ); echo "</pre> ";  
 ?>
 </div>
</div>
<!-- fussion column Wrapper-->



<script type="text/javascript">
jQuery(document).ready(function(){

 jQuery('.sp_start_btn').on('click', function(){
    console.log(' sp_start_btn '); 
    jQuery('.sp_processing').removeClass('hidden');
    jQuery('.project_info_cont .sp_content').addClass('hidden');

    var data = {
        pid: <?php echo $post->ID; ?>,
        action : 'project_start_create_task',
        security: jQuery('#project_start_nonce').val()
    };

    var ajax_url = '<?php echo admin_url('admin-ajax.php');?>';
    // Do AJAX request
    jQuery.ajax({
        url: ajax_url,
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (response) {
                 console.log('success response = ', response);
                 jQuery('.sp_processing').addClass('hidden');
                 jQuery('.sp_message').removeClass('hidden');  
                 jQuery('.sp_message .message').html(response.message);   
                 jQuery('.sp_message .message_data').html(response.data);    
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log('error jqXHR ');
            console.log(textStatus, errorThrown);
            jQuery('.sp_message .message').html('<?php _e('Error','empfohlen');?>');   
            jQuery('.sp_message .message_data').html('<?php _e('Error Creating Task','empfohlen');?>'); 
        }
    });






 });

});
</script>



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
.sp_padd{
    padding: 20px;
    background: #fff;
    margin: 10px 0;
}
.sp_title{ 
    margin: 0px 0px 20px;
    padding: 0px;
    font-size: 1.563em;
    color: black;
}
.sp_footer_action {
    padding: 20px;
    margin: 10px 0;
    text-align: center;
    display: block;
}
.btn.sp_start_btn {
    display: inline-block;
    width: auto;
    padding: 15px;
}
.sp_loading{
    display: block;
    text-align: center;
}
.sp_loading_animation{
    display: inline-block;
}
.sp_loading p{
  color: white !important;
  font-size: 1.563em;
}
.sp_square_box{
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    width: 50px;
    height: 50px;
    background: #fff;
    -webkit-animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
    animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
}


@-webkit-keyframes square-spin {
    25% {
        -webkit-transform: perspective(100px) rotateX(180deg) rotateY(0);
        transform: perspective(100px) rotateX(180deg) rotateY(0)
    }
    50% {
        -webkit-transform: perspective(100px) rotateX(180deg) rotateY(180deg);
        transform: perspective(100px) rotateX(180deg) rotateY(180deg)
    }
    75% {
        -webkit-transform: perspective(100px) rotateX(0) rotateY(180deg);
        transform: perspective(100px) rotateX(0) rotateY(180deg)
    }
    100% {
        -webkit-transform: perspective(100px) rotateX(0) rotateY(0);
        transform: perspective(100px) rotateX(0) rotateY(0)
    }
}

@keyframes square-spin {
    25% {
        -webkit-transform: perspective(100px) rotateX(180deg) rotateY(0);
        transform: perspective(100px) rotateX(180deg) rotateY(0)
    }
    50% {
        -webkit-transform: perspective(100px) rotateX(180deg) rotateY(180deg);
        transform: perspective(100px) rotateX(180deg) rotateY(180deg)
    }
    75% {
        -webkit-transform: perspective(100px) rotateX(0) rotateY(180deg);
        transform: perspective(100px) rotateX(0) rotateY(180deg)
    }
    100% {
        -webkit-transform: perspective(100px) rotateX(0) rotateY(0);
        transform: perspective(100px) rotateX(0) rotateY(0)
    }
}

.square-spin>div {
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
    width: 50px;
    height: 50px;
    background: #fff;
    -webkit-animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite;
    animation: square-spin 3s 0s cubic-bezier(0.09, 0.57, 0.49, 0.9) infinite
}



.sp_processing {
    padding: 20px;
    text-align: center;
}

</style>
