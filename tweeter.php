<?php
/*
   Plugin Name: Tweeter
   Plugin URI: http://wordpress.org/extend/plugins/tweeter/
   Version: 1.0
   Author: Viren Mohindra | http://virenmohindra.me/
   Description: Tweet specific highlights of your article post
   Text Domain: tweeter
   License: GPLv3
  */

$Tweeter_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function Tweeter_noticePhpVersionWrong() {
    global $Tweeter_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Tweeter" requires a newer version of PHP to be running.',  'tweeter').
            '<br/>' . __('Minimal version of PHP required: ', 'tweeter') . '<strong>' . $Tweeter_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'tweeter') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function Tweeter_PhpVersionCheck() {
    global $Tweeter_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $Tweeter_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'Tweeter_noticePhpVersionWrong');
        return false;
    }
    return true;
}

/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function Tweeter_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('tweeter', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

<?php
//Stops WordPress from converting your quote symbols into smartquotes, since they are not compatible with the Twitter Share button. (The urlencoding of single quotes / apostrophes breaks in the tweet.)
remove_filter('the_content', 'wptexturize');
class TweetableText{
 function makeTweetable($atts, $content = "") {
   extract(shortcode_atts(array(
      'alt' => '',
      'hashtag' => '',
   ), $atts));
   global $wpdb, $post;
	if(get_post_type($post) == "post") {
		$post_id = $post->ID;
		$permalink = get_permalink($post_id);
		$tweetcontent = ucfirst(strip_tags($content));
		if ($alt != '') $tweetcontent = $alt;
		if ($hashtag != '') $tweetcontent .= " " . $hashtag;
		$ret = "<span class='tweetable'>";
		$ret .= "<a href='https://twitter.com/intent/tweet?original_referer=".urlencode($permalink)."&source=tweetbutton&text=".rawurlencode(($tweetcontent)) ."&url=".urlencode($permalink)."'>$content&thinsp;<i class='icon-twitter' style='color: #ed2e24;'></i>";
		$ret .= "</a>";
		$ret .= "<span class='sharebuttons'>";
		$ret .= "<a href='https://twitter.com/intent/tweet?original_referer=".urlencode($permalink)."&source=tweetbutton&text=".rawurlencode(($tweetcontent)) ."&url=".urlencode($permalink)."'>TWEET";
		$ret .= "</a>";
		$ret .= "</span>";
		$ret .= "</span>";
            return $ret;
	} else {
		return $content;
	}
     }
}
add_shortcode( 'tweetable', array('TweetableText', 'makeTweetable') );
function tweetabletext_header() {
?>
<script type="text/javascript">
jQuery(document).ready(function(){
  $(".tweetable").hover(
      function(){
            if ($(this).data('vis') != true) {
                    $(this).data('vis', true);
                    $(this).find('.sharebuttons').fadeIn(200);
            }
      },
      function(){
            if ($(this).data('vis') === true) {
                    $(this).find('.sharebuttons').clearQueue().delay(0).fadeOut(200);
                    $(this).data('vis', false);
                    $(this).data('leftSet', false);
            }
      });
});	
</script>
<style>
	.tweetable {position: relative;}
	.tweetable a { text-decoration: none; border-bottom: 0px dotted #ed2e24; color: #333; background: whitesmoke; }
	.tweetable a:hover { text-decoration: none; border-bottom: 0px dotted #ed2e24; color: #ed2e24;}
	.sharebuttons {display: none; position: absolute; top: -30px; left: 0px; z-index: 101;  width: 55px; background: #ed2e24; color: whitesmoke; border-radius: 3px; height: 20px; padding: 5px; text-align: center; font-family: 'helvetica neue', helvetica, arial, sans-serif; font-size: 14px; font-weight: bold; }
	.sharebuttons a { color: whitesmoke; background: #ed2e24; border: 0; }
	.sharebuttons a:hover { color: whitesmoke; background: #ed2e24; border: 0; }
	.sharebuttons span.brand a {font-size: 10px; color: whitesmoke; text-decoration:none; display: block; padding: 0 0 0 25px; margin: 0; border: none; height: 12px; }
</style>
<?
}
if (!is_admin()) {
	add_action(  'wp_head', 'tweetabletext_header' ); 
}

// Initialize i18n
add_action('plugins_loadedi','Tweeter_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (Tweeter_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('tweeter_init.php');
    Tweeter_init(__FILE__);
    ?>
