<?php
   /*
   Plugin Name: CTA Shortcodes
   Plugin URI: http://localhost:8888
   description: Add CTA's to blog posts
   Version: 1.3
   Author: Dan
   Author URI: http://technologyadvice.com
   License: GPL2
   GitHub Theme URI: https://github.com/warofthesun/cta-shortcodes
   */
?>
<?php

function ctaOne($atts, $content = null) {
  $atts = shortcode_atts(
    array(
      'link' => 'http://technologyadvice.com'
    ), $atts, 'cta');
  return '<div class="blog_cta blog_cta--one">
            <div class="blog_cta--one__content">
              <div class="message col-xs-12">' . $content . '</div>
              <div class="call_to_action col-xs-12">
                <a class="btn blog_cta--one__button"  data-toggle="modal" data-target="#pst-modal">
                  find out now
                </a>
              </div>
            </div>
          </div>';
}

add_shortcode('cta-one', 'ctaOne');

function ctaTwo($atts, $content = null) {
  $atts = shortcode_atts(
    array(
      'link' => 'http://technologyadvice.com'
    ), $atts, 'cta');
  return '<div class="blog_cta blog_cta--two">
            <div class="blog_cta--two__content">
              <div class="image col-xs-12 col-sm-6"></div>
              <div class="message col-xs-12 col-sm-6">
                <span>' . $content . '</span>
                <div class="call_to_action col-xs-12">
                  <a class="btn blog_cta--two__button"  data-toggle="modal" data-target="#pst-modal">
                    find out now
                  </a>
                </div>
                <div class="logo"></div>
              </div>
            </div>
          </div>';
}

add_shortcode('cta-two', 'ctaTwo');


add_action( 'after_setup_theme', 'mytheme_theme_setup' );

if ( ! function_exists( 'mytheme_theme_setup' ) ) {
    function mytheme_theme_setup() {

        add_action( 'init', 'mytheme_buttons' );

    }
}

function Zumper_widget_enqueue_script()
{
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'js/scripts.js', array('jquery'), '1.0.0', false );
}
add_action('wp_enqueue_scripts', 'Zumper_widget_enqueue_script');


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
        array_push( $buttons, 'cta-menu' );
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
