<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//Exit if accessed directly

get_header();



// do_action('submit_task_port');
global $_SESSION;

$success = $_SESSION['task_success'];
$error 	 = $_SESSION['task_error'];

unset($_SESSION['task_success']);
unset($_SESSION['task_error']);


$post->request = '';
$post->project = '';

// echo "<pre> project "; print_r( $project->ID  ); echo "</pre> ";
$current_user = wp_get_current_user();
$userData = $current_user->data;
$user_id = (int) $userData->ID;


$post->status = get_field('task_status', $post->ID);
$post->task_content = get_field('task_content', $post->ID);
$post->task_additional_info = get_field('task_additional_info', $post->ID);


$upload_dir = wp_get_upload_dir();
$dest_dir 	= $upload_dir['basedir'].'/userdata/'.$user_id.'/task/'.$post->ID;
if(is_dir($dest_dir)) { 
    $task_files = scandir($dest_dir); 
    $ex_folders = array('..', '.');
    $task_files = array_diff($task_files, $ex_folders);
    $post->task_files = $task_files;
}





$download_dir = $upload_dir['baseurl'].'/userdata/'.$user_id.'/task/'.$post->ID;
$post->download_dir = $download_dir;

// check if task is of type request or without request 
$task_type_request = get_field( "task_type_request", $post->ID );
if($task_type_request){
    // for task_type_request get the project info from request 
    $request_id = (int) get_field('request_id', $post->ID);
    if(!empty($request_id)){
        $repest         = get_post( $request_id);
        $post->repest = $repest;
        // echo "<pre> repest "; print_r( $repest  ); echo "</pre> ";
    }
}


// get task project data
$project_id = (int) get_field('project_id', $post->ID);
if(!empty($project_id)){
    $project = get_post($project_id);
    if(!empty($project)) {
        // get project meta data
        $project->keyword               = get_field('keyword', $project->ID);
        $project->timer_enable      = get_field('timer_enable', $project->ID);
        $project->duration              = get_field('duration', $project->ID);
        $project->pay                       = get_field('pay', $project->ID);
        $project->expiration_date = get_field('expiration_date', $project->ID);
        $project->description       = get_field('description', $project->ID);
        $project->requirments       = get_field('requirments', $project->ID);
        $project->additional_information = get_field('additional_information', $project->ID);

        $project->user_currency = EmpHelper::getUserCurrency($current_user->ID);
        $project->price         = get_post_meta($project->ID, 'price', true);  
        $project->user_price    = EmpHelper::cc_base_to_currency($project->user_currency, $project->price );
         // echo "<pre> project  "; print_r( $project  ); echo "</pre> ";  
    }
    //keyword
    $post->project  = $project;
}






$can_submit = false;  
if ($post->status == 'pending'){
    if(!empty( $post->project )){
        // echo " project->expiration_date  = ". $project->expiration_date; 
        // check if project has expiration date 
        if( isset($project->expiration_date) && !empty($project->expiration_date) ){
            $isExpired  = EmpHelper::isExpired($project->expiration_date);
            if (!$isExpired){
                if ($project->timer_enable && !empty($project->duration)){
                    // $is_duration_expired = EmpHelper::is_duration_passed_away($task_created_date, $project->duration);
                    $is_duration_expired =  EmpHelper::is_duration_passed_away($task_created_date, $project->duration);  
                    $can_submit          =  EmpHelper::is_duration_passed_away($task_created_date, $project->duration);  
                }else{
                    $can_submit = true; 
                }
            }
        }else{
            $can_submit = true; 
        }
    }
} 


// $can_submit = true; 

?>

<?php if(isset($success)): ?>
      <?php echo EmpHelper::successBannerHtml( $success ); ?>
<?php endif; ?>

<?php if(isset($error)): ?>
    <?php if(is_array( $error)){
        foreach ($error as $single_error) {
             echo EmpHelper::errorBannerHtml( $single_error );  
        }
    }else{
         echo EmpHelper::errorBannerHtml( $error );  
    }?>
<?php endif; ?>

    <div class="fusion-column-wrapper acord-pro-info">

        <div class="task_info"> 
           <!-- <p> Task info </p>  -->
           <!-- <p> Task Can Submit : <?php //echo $can_submit; ?></p>  -->
           <p class="m_0"><strong> Task Status :</strong> <?php echo $post->status; ?></p> 
           <?php 
            if ($post->status == 'pending'){  
                if(!$can_submit){ 
                     echo '<h3>This task can not be submitted</h3>'; 
                     if (isset($isExpired) && ($isExpired)){ echo '<p>Task Expiration date pass away </p>'; }   
                     if (isset($is_duration_expired) && ($is_duration_expired)){ echo '<p>Task Duration time pass away </p>'; }   
                
                } // can_submit == can_submit  
            } else if( $post->status == 'submitted' ) {
                echo '<h3>Task already submitted</h3>';
            } // status == pending 
            ?>
        </div>

        <?php if($can_submit): ?>
        <form method="post" class="task_submit_form" enctype="multipart/form-data">
        <?php endif; ?>
        
        <div class="accordian fusion-accordian">
            <div class="panel-group fusion-toggle-icon-unboxed" id="accordion-1313-1">
                <!--   -->
                <div class="fusion-panel panel-default fusion-toggle-no-divider fusion-toggle-boxed-mode">
                    <div class="panel-heading">
                        <div class="panel-title toggle">
                            <a data-toggle="collapse" data-parent="#accordion-1313-1" data-target="#dcbb691926450b097" href="#dcbb691926450b097" class="collapsed">
                                <div class="fusion-toggle-icon-wrapper">
                                    <i class="fa-fusion-box"></i>
                                </div>
                                <div class="fusion-toggle-heading">Project Info</div>
                            </a>
                        </div>
                    </div>
                    <div id="dcbb691926450b097" class="panel-collapse collapse" style="height: 0px;">
                    <div class="panel-body toggle-content fusion-clearfix">
                        <p><span class="title-visible">Title:</span><?php echo !empty($post->project)?($post->project->post_title):'';?></p>

                    <p><span class="title-visible">Keyword:</span><?php
                            $keyword = !empty($post->project)?($post->project->keyword):'';
                            echo implode(wp_list_pluck($post->project->keyword,'name'),'');
                            ?></p>

                    <p><span class="title-visible">Timer:</span><?php echo !empty($post->project)?($post->project->timer_enable):''; ?></p>
                    <p><span class="title-visible">Duration:</span><?php echo !empty($post->project)?($post->project->duration):'';?></p>
                    <p><span class="title-visible">Pay:</span>
                        <?php echo !empty($post->project)?(EmpHelper::currency_to_code($post->project->user_currency).' '.number_format($post->project->user_price,2)):'';?>
                    </p>
                    <p><span class="title-visible">Expiration Date:</span><?php echo !empty($post->project)?($post->project->expiration_date):'';?></p>

                        <div>
                            <h4>Project Description</h4>
                            <?php echo !empty($post->project)?($post->project->description):'';?>
                        </div>

                        <div>
                            <h4>Project requirments</h4>
                            <?php
                            // echo !empty($post->project)?($post->project->requirments):'';
                            // echo "<pre> requirments "; print_r( $post->project->requirments ); echo "</pre> ";
                            if(!empty($post->project->requirments)){
                                foreach ($post->project->requirments as $r_key => $r_value) {
                                    echo '<p><span class="title-visible">Requirment '.($r_key+1).': '.$r_value['requirment'].'</span></p>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                </div>
                <!--   -->
                <!--   -->
                <!-- <div class="fusion-panel panel-default fusion-toggle-no-divider fusion-toggle-boxed-mode">

                    <div class="panel-heading">
                        <div class="panel-title toggle">
                            <a data-toggle="collapse" data-parent="#accordion-1313-1" data-target="#45ea47e906c2b6883" href="#45ea47e906c2b6883" class="collapsed">
                                <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i>
                                </div>
                                <div class="fusion-toggle-heading">Task Detail</div>
                            </a>
                        </div>
                    </div>
                    <div id="45ea47e906c2b6883" class="panel-collapse collapse" style="height: 0px;">
                        <div class="panel-body toggle-content fusion-clearfix">
                        <p><span class="title-visible">Task Status:</span> <?php echo $post->status; ?></p>
                        </div>
                    </div>
                </div> -->
                <!--   -->
                <!--   -->
                <div class="fusion-panel panel-default fusion-toggle-no-divider fusion-toggle-boxed-mode">

                    <div class="panel-heading">
                        <div class="panel-title toggle">
                            <a data-toggle="collapse" data-parent="#accordion-1313-1" data-target="#0166a452f407a8a4b" href="#0166a452f407a8a4b" class="collapsed">
                                <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i>
                                </div>
                                <div class="fusion-toggle-heading">Task content</div>
                            </a>
                        </div>
                    </div>
                    <div id="0166a452f407a8a4b" class="panel-collapse collapse" style="height: 0px;">
                        <div class="panel-body toggle-content fusion-clearfix">
                            <div class="content_edit">
                                <?php
                                $content = $post->task_content; //This content gets loaded first.';
                                $editor_id = 'p_t_content_editor';
                                wp_editor( $content, $editor_id );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--   -->
                <!--   -->
                <div class="fusion-panel panel-default fusion-toggle-no-divider fusion-toggle-boxed-mode">

                    <div class="panel-heading">
                        <div class="panel-title toggle">
                            <a data-toggle="collapse" data-parent="#accordion-1313-1" data-target="#aa40fe3bc5279b5b8" href="#aa40fe3bc5279b5b8" class="collapsed">
                                <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i>
                                </div>
                                <div class="fusion-toggle-heading">Task Additional Info</div>
                            </a>
                        </div>
                    </div>
                    <div id="aa40fe3bc5279b5b8" class="panel-collapse collapse" style="height: 0px;">
                        <div class="panel-body toggle-content fusion-clearfix">
                            <div class="additional_info_edit">

                            <div>
                                <h4>Additional Information</h4>
                                <?php echo !empty($post->project)?($post->project->additional_information):'';?>
                            </div>


                                <?php  wp_editor( $post->task_additional_info, 'p_t_additional_info_editor' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--   -->
                <!--   -->
                <div class="fusion-panel panel-default fusion-toggle-no-divider fusion-toggle-boxed-mode no_hover_affect">

                    <div class="panel-heading">
                        <div class="panel-title">
                            <a  class="collapsed">
                                <div class="fusion-toggle-icon-wrapper"><i class="fa-fusion-box"></i></div>
                                <div class="fusion-toggle-heading">Upload Files</div>
                            </a>
                        </div>
                    </div>
                    <div id="485431be8cfddd571" class="panel-collapse collapse in collapse_open_always"  style="display: block !important;" >
                        <div class="panel-body toggle-content fusion-clearfix">
                            <?php if($can_submit): ?>
                            <div class="task_files_upload_cont">
                                <div class="additional_info_edit task_files_upload">
                                    <input class="file-upload-field" type="file" name="task_files[]" multiple>
                                </div>
                            </div>

                            <?php endif; ?>
                            <div class="file">
                                <?php if(isset($post->task_files) && !empty($post->task_files)): ?>
                                    <?php
                                    foreach ($post->task_files as $tdf_key => $tdf) { ?>

                                        <div class="task_file">
                                            <div class="file-icon">
                                                <img src="<?php echo get_site_url(); ?>/wp-includes/images/media/default.png">
                                            </div>
                                            <span class="file_title"><?php echo $tdf; ?></span>
                                            <span class="file_button"><a href="<?php echo $post->download_dir.'/'.$tdf;?>">Download</a></span>

                                        </div>
                                        <?php
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!--   -->


            </div>
        </div>


        <?php if($can_submit){ ?>
            <div class="task_tab">
                <div class="task_detail">
                    <div class="task_edit_layout">
                            <div class="task_action">
                                <input type="hidden" name="emp_submit_task_nonce" value="<?php echo wp_create_nonce('emp-submit-task-nonce'); ?>"/>
                                <input type="hidden" name="action" value="submit_task" />
                                <input type="hidden" name="task_id" value="<?php echo $post->ID; ?>" />
                                <input class="submit_task" type="submit" value="Submit Task">
                            </div>
                    </div>
                </div>
            </div>
        <?php
        }?>

        
        <?php if($can_submit): ?>
        </form>
        <?php endif; ?>
    
    </div><!-- fussion column Wrapper-->


    <style type="text/css" scoped="scoped">
        #accordion-1313-1 .fusion-panel.no_hover_affect:hover{ background-color: #f9f9f9; cursor: default; }
        #accordion-1313-1 .fusion-panel:hover{ background-color: #ffffff }
        #accordion-1313-1 .fusion-panel { border-color:#e1e1e1; border-width:1px; background-color:#f9f9f9; }
        .fusion-accordian  #accordion-1313-1 .panel-title a .fa-fusion-box{ color: #092933;}
        .fusion-accordian  #accordion-1313-1 .panel-title a .fa-fusion-box:before{ font-size: 20px; width: 20px;}
        .fusion-accordian  #accordion-1313-1 .panel-title a{font-size:13px;}
        .fusion-accordian  #accordion-1313-1 .panel-title a:hover, #accordion-1313-1 .fusion-toggle-boxed-mode:hover .panel-title a { color: #3a3939;}
        .fusion-accordian  #accordion-1313-1 .fusion-toggle-boxed-mode:hover .panel-title a .fa-fusion-box{ color: #3a3939;}
        .fusion-accordian  #accordion-1313-1.fusion-toggle-icon-unboxed .fusion-panel .panel-title a:hover .fa-fusion-box{ color: #3a3939 !important;}
        .acord-pro-info{margin-top: 70px;padding-bottom:150px;}
        .submit_task{
            width: 30%;
            margin: 0 auto;
            display: block;
            line-height: 34px;
            padding: 0 75px;
            color: #fff !important;
            font-size: 1.2em;
            text-decoration: none;
            border: none;
            background: #ffae56;
            background: -moz-linear-gradient(top, #ffae56 0%, #ff9100 100%);
            background: -webkit-linear-gradient(top, #ffae56 0%, #ff9100 100%);
            background: linear-gradient(to bottom, #ffae56 0%, #ff9100 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="$colorTop", endColorstr="$colorBottom", GradientType=0);
            -webkit-border-radius: 8px !important;
            -moz-border-radius: 8px !important;
            border-radius: 8px !important;

        }
        .submit_task:hover{
            opacity: 0.8;
            cursor: pointer;
        }
        .fusion-accordian .fusion-panel.fusion-toggle-no-divider.fusion-toggle-boxed-mode .panel-title a.active {
            border-bottom: 1px solid #e1e1e1 !important;
        }
        .acord-pro-info p {
            animation: none;
        font-family: "Open Sans",sans-serif;
        }
    
    	span.title-visible {
    		font-size: 1em;
    		font-family: "Open Sans",sans-serif;
    		font-weight: bold;
    		padding-right: 5px;
		}
    
	/*  -------  	 */
        .t_p_info,
        .task_detail{

            margin: 20px auto;
        }
        .task_detail.edit_task .edit_layout{ display: block; }
        .task_detail.edit_task .show_layout{ display: none; }
        .file-icon {
            padding: 10px;
            text-align: center;
        }
        .task_file {
            display: inline-block;
            text-align: center;
            margin: 10px;
            padding: 5px;
            border: 1px solid #888888;
            cursor: default;
        }
        .file-icon img {
            margin: 0 auto;
        }
        .file_button{ display: block;   }
        .file_button a{   text-decoration: none; }
    
    @media only screen and (max-width: 800px){
    	.acord-pro-info {
    		width: 100%;
		}
    	.submit_task {
    		width: auto;
    	}
    }


    .task_info {
        border: 2px solid white;
        padding: 20px;
        background: #f9f9f9;
        margin: 20px 0px;
    }
    .collapse_open_always{ height: auto !important;  }





.additional_info_edit.task_files_upload {
    position: relative;
    width: 100%;
    height: 60px;
}
.task_files_upload:before {
    content: 'Upload';
    position: absolute;
    top: 0;
    right: 0;
    display: inline-block;
    height: 60px;
    background: #4daf7c;
    color: #fff;
    font-weight: 700;
    z-index: 25;
    font-size: 16px;
    line-height: 60px;
    padding: 0 15px;
    text-transform: uppercase;
    pointer-events: none;
    border-radius: 0 5px 5px 0;
}
.task_files_upload:after {
    content: attr(data-text);
    font-size: 18px;
    position: absolute;
    top: 0;
    left: 0;
    background: #fff;
    padding: 10px 15px;
    display: block;
    width: calc(100% - 40px);
    pointer-events: none;
    z-index: 20;
    height: 40px;
    line-height: 40px;
    color: #999;
    border-radius: 5px 10px 10px 5px;
    font-weight: 300;
}
.task_files_upload_cont {max-width: 500px;padding: 20px;margin: 10px auto;border: 1px solid #c9c9c9;border-radius: 5px;}
.file-upload-field {
    opacity: 0;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 99;
    height: 40px;
    margin: 0;
    padding: 0;
    display: block;
    cursor: pointer;
    width: 100%;
}
    </style>

    <script type="text/javascript">
        jQuery(document).ready(function(){

            // p_t_edit_task
            jQuery('.p_t_edit_task').on('click',function(){
                jQuery('.task_detail').addClass('edit_task');
            });

            //jQuery( ".task_tab" ).accordion();



            // tinyMCE.init({
            //       mode : "specific_textareas",
            //       theme : "simple",
            //       plugins : "autolink, lists, spellchecker, style, layer, table, advhr, advimage, advlink, emotions, iespell, inlinepopups, insertdatetime, preview, media, searchreplace, print, contextmenu, paste, directionality, fullscreen, noneditable, visualchars, nonbreaking, xhtmlxtras, template",
            //       editor_selector :"tinymce-enabled"
            //   });


            jQuery(".task_submit_form").on("change", ".file-upload-field", function(event){ 
                console.log(' change ');
                jQuery(this).parent(".additional_info_edit.task_files_upload").attr("data-text",jQuery(this).val().replace(/.*(\/|\\)/, '') );
            });


// $('input[type=file]').change(function(e){
//   $in=$(this);
//   $in.next().html($in.val());
// });


        });

    </script>



<?php
get_footer();