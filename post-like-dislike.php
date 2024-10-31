<?php
/**
 * @package Post Like Dislike
 */
/*
Plugin Name: Post Like Dislike
Plugin URI: http://CuvixSystem.com/
Description: By this plugin you can get like dislike of your post. From the plugin admin pannel you can choose where you want to put the thumb <strong>left or right and top or bottom </strong>.  Here is two kind of voting system ---  Anonymous Vote and Registered Vote. <strong>1) Anonymous Vote - </strong>Any one can vote here. It is not necessary to register for vote. It is depend on session, if session destroy you can vote again <strong> 2)  Registered Vote - </strong>Only Registered member can vote .  Go to your <a href="admin.php?page=post-like-dislike">Post Like Dislike configuration</a> page .
Version: 1.0
Author: Sourav Sarkar
Author URI: http://CuvixSystem.com/
License: GPLv2 or later
*/

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}


function add_list_content($content)
{
	
	
	if(get_option('hloc')=="right")
	{
		$hloc=" style='overflow: auto;'><p style='float:right;margin:0'>";
	}
	else{
		$hloc=" style='overflow: auto;'><p>";
	}
	
	
	
	$current_user = wp_get_current_user();
	$usr_id=$current_user->ID;
	$vote_done=get_option('vote_done');
	
		if(get_option('vote_type')=='reg')
		{
			$vote_pst=explode(",",$vote_done[$usr_id]);
			for($i=0;$i<=count($vote_pst);$i++)
			{
				if($vote_pst[$i]==get_the_ID())
				{
					$do_vote[get_the_ID()]="done";
					break;
				}
			}
		}

           $likepost=get_post_meta(get_the_ID(),'like_post',true);
			$unlikepost=get_post_meta(get_the_ID(),'unlike_post',true);
	?>		
            <!--<link rel="stylesheet" href="<?php echo plugins_url()?>/post-like-dislike/style.css" />-->
    <?php         
			if($likepost=="")
			{
				$likepost='0';
			}
			if($unlikepost=="")
			{
				$unlikepost='0';
			}
         $likepost="<span class='likepost'> +".$likepost."</span>";
			$unlikepost="<span class='unlikepost'> -".$unlikepost."</span>";  
	if(get_post_type( get_the_ID() ) == 'post'){
		if($_SESSION[get_the_ID().'_post_like']!="done" )
			{			
				
				if(get_option('vote_type')=='reg' && $usr_id=='')
				{
					if(get_option('vloc')=="top")
					{
					$content_new = '<div '.$hloc.' <img class="likepostimg"  src="'.plugins_url().'/post-like-dislike/img/like_done.png"> '.$likepost.' <img class="unlikepostimg"  src="'.plugins_url().'/post-like-dislike/img/unlike_done.png"> '.$unlikepost.'</p></div>'.$content_new;
					}
					else
					{
					$content_new .= '<div '.$hloc.' <img class="likepostimg"   src="'.plugins_url().'/post-like-dislike/img/like_done.png"> '.$likepost.' <img class="unlikepostimg"   src="'.plugins_url().'/post-like-dislike/img/unlike_done.png"> '.$unlikepost.'</p></div>';
					}
				}
				else if( $do_vote[get_the_ID()]=="done")
				{
					if(get_option('vloc')=="top")
					{
					$content_new = '<div'.$hloc.'<img class="likepostimg"  src="'.plugins_url().'/post-like-dislike/img/like_done.png"> '.$likepost.' <img class="unlikepostimg"   src="'.plugins_url().'/post-like-dislike/img/unlike_done.png"> '.$unlikepost.'</p></div>'.$content_new;
					}
					else
					{
					$content_new .= '<div '.$hloc.'<img class="likepostimg"   src="'.plugins_url().'/post-like-dislike/img/like_done.png"> '.$likepost.' <img class="unlikepostimg"   src="'.plugins_url().'/post-like-dislike/img/unlike_done.png"> '.$unlikepost.'</p></div>';
					}
				}
				else
				{	
					if(get_option('vloc')=="top")
					{
					$content_new  ='<div'.$hloc.'<a href="'.$_SERVER['PHP_SELF'].'?q='.get_the_ID().'&act=like"><img class="likepostimg"  src="'.plugins_url().'/post-like-dislike/img/like.png"></a> '.$likepost.' <a href="'.$_SERVER['PHP_SELF'].'?q='.get_the_ID().'&act=unlike"><img class="unlikepostimg"  src="'.plugins_url().'/post-like-dislike/img/unlike.png"></a> '.$unlikepost.'</p></div>'.$content_new;	
					

					}
					else
					{		
					$content_new .= '<div'.$hloc.'<a href="'.$_SERVER['PHP_SELF'].'?q='.get_the_ID().'&act=like"><img class="likepostimg"  src="'.plugins_url().'/post-like-dislike/img/like.png"></a> '.$likepost.' <a href="'.$_SERVER['PHP_SELF'].'?q='.get_the_ID().'&act=unlike"><img class="unlikepostimg" src="'.plugins_url().'/post-like-dislike/img/unlike.png"></a> '.$unlikepost.'</p></div>';
					}
				}

			
			}
			else
			{

				
				if(get_option('vloc')=="top")
					{
					$content_new = '<div '.$hloc.'<img class="likepostimg"  src="'.plugins_url().'/post-like-dislike/img/like_done.png"> '.$likepost.' <img class="unlikepostimg"  src="'.plugins_url().'/post-like-dislike/img/unlike_done.png"> '.$unlikepost.'</p></div>'.$content_new;
					}
					else
					{
					$content_new .= '<div '.$hloc.'<img class="likepostimg"  src="'.plugins_url().'/post-like-dislike/img/like_done.png"> '.$likepost.' <img class="unlikepostimg"  src="'.plugins_url().'/post-like-dislike/img/unlike_done.png"> '.$unlikepost.'</p></div>';
					}
					
				
							
			}
		
		}
		
	
		if(get_option('vloc')=="top")
		{
		return $content_new.$content;
		}
		else
		{
		return $content.$content_new;
		}
}

add_filter('the_content', 'add_list_content',1,2);

add_filter('the_excerpt', 'add_list_content');

add_filter('get_the_excerpt', 'no_share_links',-1); 
    function no_share_links( $content ) { 
     remove_filter('the_content', 'add_post_content'); 
	 remove_filter('the_content', 'add_list_content',1,2);
     return $content; 
   } 
 
		

function like_post($content)
{
	
	if($_REQUEST['act']=='like' && $_REQUEST['q']!="" && $_SESSION[$_REQUEST['q'].'_post_like']=="")
	{
		
		$_SESSION[$_REQUEST['q'].'_post_like']='done';
		$counts=get_post_meta($_REQUEST['q'],'like_post',true)+1;
		add_post_meta($_REQUEST['q'], 'like_post', $counts, true);  
    	update_post_meta($_REQUEST['q'], 'like_post', $counts); 
		$url_rdir="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?p=".$_REQUEST['q'];
		
		if(get_option('vote_type')=='reg')
		{
			$current_user = wp_get_current_user();
			$usr_id=$current_user->ID;
			$vote_done=get_option('vote_done');
			if($vote_done=='')
			{
				$vote_done[$usr_id]=$_REQUEST['q'];
				add_option('vote_done',$vote_done);
			}
			else
			{
				if($vote_done[$usr_id]=='')
				{
					$vote_done[$usr_id]=$_REQUEST['q'];
				}
				else
				{
					$vote_pst=explode(",",$vote_done[$usr_id]);
					for($i=0;$i<=count($vote_pst);$i++)
					{
						if($vote_pst[$i]!=$_REQUEST['q'])
						{
							$do_vote="yes";
							break;
						}
					}
					if($do_vote=='yes')
					{
					$vote_done[$usr_id]=$vote_done[$usr_id].",".$_REQUEST['q'];
					}
				}
			update_option('vote_done',$vote_done);
			}
		}
	?>
            <script type="text/javascript">
   				 window.location = "<?php echo $url_rdir; ?>";
			</script>
            <?php
	}
	if($_REQUEST['act']=='unlike' && $_REQUEST['q']!="" && $_SESSION[$_REQUEST['p'].'_post_like']=="")
	{
	
	
		if(get_option('vote_type')=='reg')
		{
			$current_user = wp_get_current_user();
			$usr_id=$current_user->ID;
			$vote_done=get_option('vote_done');
			if($vote_done=='')
			{
				$vote_done[$usr_id]=$_REQUEST['q'];
				add_option('vote_done',$vote_done);
			}
			else
			{
				if($vote_done[$usr_id]=='')
				{
					$vote_done[$usr_id]=$_REQUEST['q'];
				}
				else
				{
					$vote_pst=explode(",",$vote_done[$usr_id]);
					for($i=0;$i<=count($vote_pst);$i++)
					{
						if($vote_pst[$i]!=$_REQUEST['q'])
						{
							$do_vote="yes";
							break;
						}
					}
					if($do_vote=='yes')
					{
					$vote_done[$usr_id]=$vote_done[$usr_id].",".$_REQUEST['q'];
					}
				}
			update_option('vote_done',$vote_done);
			}
		}
	
	
	
		
		$_SESSION[$_REQUEST['q'].'_post_like']='done';
		$counts=get_post_meta($_REQUEST['q'],'unlike_post',true)+1;
		add_post_meta($_REQUEST['q'], 'unlike_post', $counts, true);  
    	update_post_meta($_REQUEST['q'], 'unlike_post', $counts); 
		$url_rdir="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?p=".$_REQUEST['q'];
	?>
            <script type="text/javascript">
   				 window.location = "<?php echo $url_rdir; ?>";
			</script>
            <?php
	}
}
add_action('wp_head', 'like_post');



function theme_styles()  
{ 
  // Register the style like this for a theme:  
  // (First the unique name for the style (custom-style) then the src, 
  // then dependencies and ver no. and media type)
  wp_register_style( 'custom-style', 
    plugins_url() . '/post-like-dislike/style.css', 
    array(), 
    '20120208', 
    'all' );

  // enqueing:
  wp_enqueue_style( 'custom-style' );
}
add_action('wp_enqueue_scripts', 'theme_styles');

/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
	add_menu_page( 'My Plugin Options', 'Post Like Dislike', 'manage_options', 'post-like-dislike', 'my_plugin_options' );
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
    <?php
		if($_REQUEST['vtype']!="")
		{
			if(get_option('vote_type')=='')
			{
			add_option('vote_type',$_REQUEST['vtype']);
			}
			else
			{
			update_option('vote_type',$_REQUEST['vtype']);
			}
		}
		if($_REQUEST['vloc']!="")
		{
			if(get_option('vloc')=='')
			{
			add_option('vloc',$_REQUEST['vloc']);
			}
			else
			{
			update_option('vloc',$_REQUEST['vloc']);
			}
		}
		if($_REQUEST['hloc']!="")
		{
			if(get_option('hloc')=='')
			{
			add_option('hloc',$_REQUEST['hloc']);
			}
			else
			{
			update_option('hloc',$_REQUEST['hloc']);
			}
		}
	
	echo '<div class="wrap_admin">';
	
	?>
    <h1>Post Like Dislike</h1>
    <hr />
    <b>Choose Position</b><br /> 
    <u>Varticle Location</u> :
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=post-like-dislike">
    <table><tr>
    <td><input type="radio" name="vloc" value="bottom" <?php if(get_option('vloc')=='bottom' || get_option('vloc')==''){ ?>checked="checked"<?php } ?>  /> After Content</td>
    <td>&nbsp;&nbsp;&nbsp;<input type="radio" name="vloc" value="top" <?php if(get_option('vloc')=='top'){ ?>checked="checked"<?php } ?>  /> Before Content</td>
    </tr></table>
    <br /> 
    <u>Horizantal Location</u> :
    <table><tr>
    <td><input type="radio" name="hloc" value="left" <?php if(get_option('hloc')=='left' || get_option('hloc')==''){ ?>checked="checked"<?php } ?> /> Left</td>
    <td>&nbsp;&nbsp;&nbsp;<input type="radio" name="hloc" value="right" <?php if(get_option('hloc')=='right'){ ?>checked="checked"<?php } ?> /> Right</td>
    </tr></table>
    <hr />
    <b>Vote Type</b><br /> 
    <table><tr>
    <td valign="top" width="100"><input type="radio" name="vtype" value="ano" <?php if(get_option('vote_type')=='ano' || get_option('vote_type')==''){ ?>checked="checked"<?php } ?> /> Anonymous</td>
    <td>[ Any one can vote here. It is not necessary to register here for vote. It is depend on session, if session destroy you can vote again ]</td></tr><tr>
    <td><input type="radio" name="vtype" value="reg" <?php if(get_option('vote_type')=='reg'){ ?>checked="checked"<?php } ?> /> Registered</td><td>[ Only Registered member can vote ]</td>
    </tr></table>
    <input type="submit" />
    </form>
    <?php
	echo '</div>';
}