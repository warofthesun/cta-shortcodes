<?php
   /*
   Plugin Name: CTA Shortcodes
   Plugin URI: http://localhost:8888
   description: Add CTA's to blog posts
   Version: 0.1
   Author: Dan
   Author URI: http://technologyadvice.com
   License: GPL2
   */
?>
<?php

function ctaOne($atts, $content = null) {
  $atts = shortcode_atts(
    array(
      'link' => 'http://technologyadvice.com'
    ), $atts, 'cta');
  return '<div class="blog_cta-one ">
            <div class="blog_cta-content">
              <div class="message col-xs-12">' . $content . '</div>
              <div class="call_to_action col-xs-12">
                <a class="btn blog_cta-one-button"  data-toggle="modal" data-target="#pst-modal">
                  find out now
                </a>
              </div>
            </div>
          </div>';
}

add_shortcode('cta-one', 'ctaOne');


add_action( 'after_setup_theme', 'mytheme_theme_setup' );

if ( ! function_exists( 'mytheme_theme_setup' ) ) {
    function mytheme_theme_setup() {

        add_action( 'init', 'mytheme_buttons' );

    }
}


/********* TinyMCE Buttons ***********/
if ( ! function_exists( 'mytheme_buttons' ) ) {
    function mytheme_buttons() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }

        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }

        add_filter( 'mce_external_plugins', 'mytheme_add_buttons' );
        add_filter( 'mce_buttons', 'mytheme_register_buttons' );
    }
}

if ( ! function_exists( 'mytheme_register_buttons' ) ) {
    function mytheme_register_buttons( $buttons ) {
        array_push( $buttons, 'cta-one' );
        return $buttons;
    }
}


if ( ! function_exists( 'mytheme_add_buttons' ) ) {
    function mytheme_add_buttons( $plugin_array ) {
        $plugin_array['cta_buttons'] = plugins_url() . '/cta-shortcodes/js/tinymce_buttons.js';
        return $plugin_array;
    }
}


//Add styles for the CTA:
$pluginURL = plugins_url("",__FILE__);
$CSSURL = "$pluginURL/css/styles.css";
wp_register_style( 'cta_styles', $CSSURL);

wp_enqueue_style('cta_styles');
?>