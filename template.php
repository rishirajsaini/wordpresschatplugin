<?php
/**
 * Plugin  template
 * @param void
 * @return void
 * @since 1.0
 * @package FREELANCEENGINE
 * @category PRIVATE MESSAGE
 * @author Tambh
 */
/**
 * Message button
 * @param void
 * @return void
 * @since 1.0
 * @package FREELANCEENGINE
 * @category PRIVATE MESSAGE
 * @author Tambh
 */
if( !function_exists('ae_private_message_button') ) {
	function ae_private_message_button($bid, $project)
	{
		global $user_ID;
		$to_user = ae_private_msg_user_profile((int)$bid->post_author);
		$response = ae_private_message_created_a_conversation(array('bid_id'=>$bid->ID,'project_id' => $project->ID,'author' => $bid->post_author));
		if ($user_ID == (int)$project->post_author && $project->post_status == 'publish') {
			if( $response['success'] ){
				$data = array(
					'bid_id'=> $bid->ID,
					'to_user'=> $to_user,
					'project_id'=> $project->ID,
					'project_title'=> $project->post_title,
					'from_user'=> $user_ID
				);
				?>
                <a class="fre-normal-btn-o btn-send-msg btn-open-msg-modal" href="javascript:void(0)"><?php _e('Contact',ET_DOMAIN) ?>
                    <script type="data/json"  class="privatemsg_data">
                        <?php  echo json_encode( $data ) ?>
                    </script>
                </a>
			<?php }else{ ?>
                <a class="fre-normal-btn-o btn-send-msg btn-redirect-msg" href="javascript:void(0)"  data-conversation="<?php echo $response['conversation_id'] ?>">
					<?php _e('Contact',ET_DOMAIN) ?>
                </a>
				<?php
			}
		}
	}
}
/**
 * Private message modal
 * @param void
 * @return void
 * @since 1.0
 * @package FREELANCEENGINE
 * @category PRIVATE MESSAGE
 * @author Tambh
 */
if( !function_exists( 'ae_private_message_modal' ) ){
	function ae_private_message_modal(){ ?>
        <!-- MODAL Send Message -->
        <div class="modal fade" id="modal_msg">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </button>
                        <h4 class="modal-title"><?php _e("Send Message", ET_DOMAIN) ?></h4>
                    </div>
                    <div class="modal-body">
                        <div>
                            <form role="form" id="private_msg_form" class="fre-modal-form" >
                                <div class="fre-content-confirm">
                                    <p><?php _e("Type your message into the message box, and then click the Send button.",ET_DOMAIN) ?></p>
                                    <br>
                                </div>

                                <input id="inputSubject" name="post_title" type="hidden" class="form-control width100p" value="<?php _e('no subject', ET_DOMAIN); ?>">

                                <div class="fre-input-field">
                                    <label class="fre-field-title"><?php _e('Message', ET_DOMAIN); ?></label>
                                    <textarea name="post_content" id="" cols="30" rows="10"></textarea>
                                </div>

                                <div class="fre-form-btn">
                                    <button type="submit" class="fre-normal-btn  btn-send-msg-modal"><?php _e('Send', ET_DOMAIN); ?></button>
                                    <span class="fre-form-close" data-dismiss="modal"><?php _e('Cancel', ET_DOMAIN) ?></span>
                                </div>

                                <input type="hidden" name="from_user" value="" />
                                <input type="hidden" name="to_user" value="" />
                                <input type="hidden" name="project_id" value="" />
                                <input type="hidden" name="project_name" value="" />
                                <input type="hidden" name="bid_id" value="" />
                                <input type="hidden" name ="is_conversation" value="1" />
                                <input type="hidden" name ="conversation_status" value="unread" />
                                <input type="hidden" name="sync_type" value="conversation" />
                            </form>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
	<?php }
}
if( !function_exists('ae_private_message_add_profile_tab_template') ) {
	/**
	 * add profile tab content html
	 * @param void
	 * @return void
	 * @since 1.0
	 * @package FREELANCEENGINE
	 * @category PRIVATE MESSAGE
	 * @author Tambh
	 */
	function ae_private_message_add_profile_tab_template()
	{
		global $user_ID;
		$number = ae_private_message_get_new_number($user_ID);
		$class = '';
		?>
        <li>
            <a href="#tab_private_msg" role="tab" data-toggle="tab" class="ae-private-message-conversation-show">
                <span>
                     <?php _e('Messages', ET_DOMAIN);
                     $num = $number;
                     if($number > 100){
	                     $num = '99+';
                     }
                     if($num <= 0){
	                     $num = 0;
	                     $class = 'hidden';
                     }
                     echo '<span class="msg-number '. $class .' "> ' . $num . ' </span>';
                     ?>
                </span>
            </a>
        </li>
	<?php }
}

if( !function_exists('ae_private_message_add_profile_tab_content_template') ){
	function ae_private_message_add_profile_tab_content_template(){
	    ?>
        <div class="fre-page-wrappe conversation-panelr" id="tab_private_msg">

            <div class="row mb-2">
                <div class="col-12">&nbsp;</div>
            </div>

            <div class="fre-page-section">
                <div class="container">
                    <div class="fre-inbox-wrap">
                        <div class="row">
                            <div class="col-md-4 private-message-conversation-contents ">
                                <div class="inbox-user-wrap">
                                    <div class="search-inbox-user">
                                        <?php $placeholder_search = 'Search freelancer name';
                                        global $user_ID;
                                        $user_role = ae_user_role($user_ID);
                                        if($user_role == 'freelancer'){
                                            $placeholder_search = 'Search employer name';
                                        }
                                        ?>
                                        <input class="search" type="text" name="s"
                                               placeholder="<?php _e($placeholder_search,ET_DOMAIN) ?>">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <div class="chosen-inbox-read">
		                                <?php
		                                global $user_ID;
		                                $name = 'conversation_status';
		                                if( ae_user_role($user_ID) == EMPLOYER ||ae_user_role($user_ID) == 'administrator'){
			                                $name = 'post_status';
		                                }
		                                ?>
                                        <select class="fre-chosen-single fre-filter-conversation"
                                                data-chosen-width="20%" data-chosen-disable-search="1"
                                                data-placeholder="<?php _e('Select a status') ?>" name="<?php echo $name; ?>">
                                            <option value=""><?php _e('All', ET_DOMAIN); ?></option>
                                            <option value="unread"><?php _e('UnRead', ET_DOMAIN); ?></option>
                                        </select>
                                    </div>
                                        
									<?php
									$args = array();
									$args = ae_private_message_default_query_args($args, true);
									global $ae_post_factory, $post, $wp_query;
									query_posts($args);
									$post_object = $ae_post_factory->get('ae_private_message');

									$conversation_data = array();
									if( have_posts() ):
                                        echo '<div class="inbox-user-list-wrap">';
                                        echo '<ul class="inbox-user-list">';
										while( have_posts() ) : the_post();
											$convert    = $post_object->convert($post);
                                            //echo "<pre>";
                                           // print_r($convert);
                                           // echo "</pre>";
											$conversation_data[]  = $convert;
										endwhile;
                                        echo '</ul>';
                                        echo '</div>';
									else:
										_e('<div class="no-message-wrap"><p>You have not created any conversation yet. Please come back to your bidders list in Project detail for starting a conversation with bidders.</p></div>', ET_DOMAIN);
									endif;
									?>

                                    <?php
                                        ae_pagination($wp_query, get_query_var('paged'), 'load_more');
                                        wp_reset_query();
                                    ?>

									<?php echo '<script type="data/json" class="ae_private_conversation_data" >'.json_encode($conversation_data).'</script>';?>

                                </div>
                            </div>
                            <div class="col-md-8 private-message-reply-contents" <?php echo empty($conversation_data) ? 'style ="display:block"' : '' ?>>
								<?php
                                ae_private_message_reply_content();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php }
}
if( !function_exists('ae_private_message_loop_item') ){
	/**
	 * Private message conversation loop item
	 * @param void
	 * @return void
	 * @since 1.0
	 * @package FREELANCEENGINE
	 * @category PRIVATE MESSAGE
	 * @author Tambh
	 */
	function ae_private_message_loop_item(){
		global $user_ID;
		?>
        <script type="text/template" id="ae-private-message-loop">
            <div class="inbox-item-wrap inbox-item-wrap-{{=ID}} action" data-action="show">
            	<i class="new-messages"></i>
                {{=conversation_author_avatar}}
                <h2><span data-id="{{=to_user}}" class="icon_status">.</span>{{= conversation_author_name }}</h2>
                <p>
                    {{= last_conversation_icon}} {{= last_conversation_content }}
                </p>
                <span>{{= last_conversation_date }}</span>
            </div>
        </script>
	<?php    }

}

if( !function_exists('ae_private_message_add_notification_menu_template') ){
	/**
	 * header menu message template
	 * @param void
	 * @return void
	 * @since 1.0
	 * @package FREELANCEENGINE
	 * @category PRIVATE MESSAGE
	 * @author Tambh
	 */
	function ae_private_message_add_notification_menu_template(){
	    $class = 'class="trigger-overlay trigger-messages"';?>
        <li>
            <a href="<?php echo et_get_page_link('private-message') ?>">
                <!-- <i class="fa fa-inbox"></i> -->
				<?php
				global $user_ID;
				$message_number = get_user_meta($user_ID, 'total_unread', true);
				_e("Inbox", ET_DOMAIN);
				if( $message_number ) {
					echo ' <span class="notify-number">(' . $message_number . ')</span> ';
				}
				?>
            </a>
        </li>
	<?php }
}

if( !function_exists('ae_private_message_reply_content') ){
	function ae_private_message_reply_content(){
		?>
        <div class="row">
            <div class="col-md-12  col-sm-12 col-xs-12">
                <div class="inbox-content-wrap">
                    <h2 class="inbox-project-title">
                            <span class="fre-back-inbox-btn visible-sm visible-xs">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i
                            </span>
                        <span class="title-conversation" style="text-decoration: none"></span>
                    </h2>
                    <div class="fre-conversation-wrap fre-inbox-message" style="position: relative">
                        <ul class="fre-conversation-list">
                        </ul>
                    </div>
                    <div class="conversation-typing-wrap ae-pr-msg-reply-form">
                        <form action="" id="private_msg_reply_form" novalidate="novalidate">
                       
                            <div class="conversation-typing">
                             <label style="float: left; position: relative; margin-bottom: -35px;" class="conversation-send-message-btn disabled" for="attachment-upload">
                                    <i style="font-weight: bold; font-size: 25px; padding-top: 10px;" onclick="document.getElementById('attachment_upload').click()" class="fa fa-paperclip" aria-hidden="true"></i>
                                    <input type="file" onchange="getFileNameUpload(this)"  name="attachment_upload" id="attachment_upload"/>
                                </label>
                                <span id="file_upload_loader" style="position: absolute;margin-left: 40%;display: none;"><img src="https://ui-ex.com/images/background-transparent-loading-3.gif" alt="Uploading, Please Wait..." style="height:30px; width:auto;"/></span>
                                <textarea id="file_post_title" name="post_title" class="content-chat" placeholder="<?php _e('Your message here...', ET_DOMAIN) ?>" style="height: 38px; padding-left:15px;"></textarea>

                                <input type="hidden" name="post_content" value="" />
                                <input type="hidden" name="post_parent" value="" />
                                <input type="hidden" name="sync_type" value="reply" />
                            </div>

                            <div class="conversation-submit-btn">
                            
                                <label id="message_upload_file_send" class="conversation-send-message-btn disabled" for="conversation-send-message">
                                    <input id="conversation-send-message" type="submit" class="ae-pr-msg-reply-submit" disabled>
                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                </label>
                            </div>
                            <input type="hidden" name="action" value="file_upload_chat_message"/>
                        </form>
                        <style type="text/css">
                        	.icon_status{
                            	    position: absolute;
    margin-left: -26px;
    border-radius: 7px;
    width: 14px;
    color: transparent;
    z-index: 999;
    font-size: 11px;
    height: 15px;
                            }
                            .new-messages{
                                    float: right;
                                    font-style: normal;
                                    color: blue;
                                    font-weight: bold;
                                    background: red;
                                    border-radius: 10px;
                                    padding: 0px 5px 0px 5px;
                            }
                        </style>
                        <script type="text/javascript">
                                   function getFileNameUpload(file){
                                                            if(file.files.length > 0 && file.files.length < 2){
                                                            	document.getElementById('file_upload_loader').style.display = 'block';
                                                                if(confirm("Are you sure to upload " + file.files[0].name + " !")){
                                                                    let fd = new FormData(document.getElementById('private_msg_reply_form'));
                                                                    jQuery.ajax({
                                                                       type:'post',
                                                                       url:'<?php echo admin_url('admin-ajax.php'); ?>',
                                                                       data:fd,
                                                                       processData: false,
                                                                       contentType: false,
                                                                       success:function(res){
                                                                           document.getElementById('file_post_title').value = res;
                                                                           document.getElementById('message_upload_file_send').click();
                                                                           document.getElementById('file_upload_loader').style.display = 'none';
                                                                       }
                                                                    });
                                                                }
                                                            }
                                                        }
                                                        
                                                        
                                                                user_interaction = true;
        //Interacted
        window.addEventListener('focus', function () {
            user_interaction = true;
        });

        // Not interacted
        window.addEventListener('blur', function () {
            user_interaction = false;
        });

        jQuery(document).ready(function () {
            updateStatus();
            if (document.hidden) {
                $('#hidden').text('Hidden');
            }
        });
        function updateStatus() {
        var active_user = [];
var active_user_unique = '';
jQuery('span[data-id]').each(function(){
	active_user.push(jQuery(this).attr('data-id'));
})

active_user_unique = active_user.filter(function(el, index, arr) {
        return index == arr.indexOf(el);
    });
    var chat_group_list_id = [];
jQuery('.inbox-item-wrap').each(function(){
    chat_group_list_id.push(jQuery(this).attr('class').replace(/\D/g,''));
})
            jQuery.post('<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    {action: 'update_user_status', interaction : user_interaction, active_user: active_user_unique.join(), chat_group_list : chat_group_list_id.join()},
                    function (res) {
                        datalists = JSON.parse(res);
                        datalist = datalists['status'];
                        html_list = "<p><span><b>Name</b></span><span><b>Status</b></span></p>";
                        try{
                            for(x in datalist){
                            	if(datalist[x].status_type == 'I'){
                                	view_color = 'red';
                                }else if(datalist[x].status_type == 'U'){
                                	view_color = 'yellow';
                                }else{
                                	view_color = 'green';
                                }
                                jQuery("[data-id="+datalist[x].id+"]").css({background:view_color})
                            }
                            showNewMessagesArrived(datalists['message']);
                        }catch(ex){};
                        setTimeout(updateStatus, 2500);
                    });
        }

        function showNewMessagesArrived(messages){
            messages = JSON.parse(messages);
            for(x in messages){
                var parent_id = messages[x].post_parent;
                    if(!jQuery('.inbox-item-wrap-'+parent_id).attr('data-total-message')){
                        jQuery('.inbox-item-wrap-'+parent_id).attr('data-total-message', messages[x].counts);
                    }else{
                        var old_message = jQuery('.inbox-item-wrap-'+parent_id).attr('data-total-message');
                        if(old_message < messages[x].counts){
                            jQuery('.inbox-item-wrap-'+parent_id).find('.new-messages').text(messages[x].counts - old_message).show();
                        }else{
                            //jQuery('.inbox-item-wrap-'+parent_id).find('.new-messages').text('');
                        }
                    }
                }
            }

                        </script>
                    </div>
                </div>
            </div>
        </div>
	<?php   }
}
if( !function_exists('ae_private_message_rely_loop_item') ){
	/**
	 * Private message reply loop item
	 * @param void
	 * @return void
	 * @since 1.0
	 * @package FREELANCEENGINE
	 * @category PRIVATE MESSAGE
	 * @author Tambh
	 */
	function ae_private_message_reply_loop_item(){ ?>
        <script type="text/template" id="ae-private-message-reply-loop">
            <span class="message-avatar">
                {{= post_author_avatar}}
            </span>
            <div class="message-item">
                <p> {{= post_content }}</p>
            </div>
        </script>
	<?php    }

}
function ae_private_message_redirect(){
	if( isset($_GET['pr_msg_c_id']) ){
		$conversation = ae_private_message_get_conversation($_GET['pr_msg_c_id']);
		if( $conversation && ae_private_message_user_can_view_conversation($conversation) ){
			echo '<script type="data/json" class="ae_private_conversation_redirect_data" >'.json_encode($conversation).'</script>';
		}
	}
}