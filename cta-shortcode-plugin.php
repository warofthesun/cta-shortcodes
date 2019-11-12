<?php
   /*
   Plugin Name: CTA Shortcodes
   description: Add CTA's to blog posts
   Version: 1.9.1
   Author: Dan
   Author URI: http://technologyadvice.com
   License: GPL2
   GitHub Plugin URI: https://github.com/warofthesun/cta-shortcodes
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
                <a class="btn blog_cta--one__button" data-backdrop="false" data-toggle="modal" data-target="#pst-modal">
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
                  <a class="btn blog_cta--two__button" data-backdrop="false" data-toggle="modal" data-target="#pst-modal">
                    find out now
                  </a>
                </div>
                <div class="logo"></div>
              </div>
            </div>
          </div>';
}

add_shortcode('cta-two', 'ctaTwo');

function pricingModal($atts, $content = null) {
    extract(shortcode_atts(array(
     'category' => 'CRM',
     'cta' => 'Get Pricing',
     'url' => 'copper-reviews',
     'width' => '200px'
    ), $atts));
    $home = get_home_url();
    $product = $home.'/blog/products/'.$url;
    $postid = url_to_postid( $product );
    query_posts($postid);
    if (have_posts()) :
    $product_title = get_the_title($postid);
    $product_logo = get_the_post_thumbnail($postid);
    wp_reset_query();
    endif;
    wp_register_script( 'techadvice-getPRicing', plugin_dir_url( __FILE__ ) . 'js/getPricing.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'techadvice-getPRicing?version='.RESOURCE_VERSION );
  return '<button class="btn btn-primary btn-block" style="width: ' . $width . '" data-toggle="modal" data-target="#requestQuoteModalBlog-' . $url . '">' . $cta . '</button>
  <div class="modal product-modal fade request-quote-modal_blog" id="requestQuoteModalBlog-' . $url . '" tabindex="-1" role="dialog" aria-labelledby="requestQuoteModalBlog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="product-modal__content modal-content">
      <div class="product-modal__header modal-header">
        <h3 class="product-modal__header--title">Get pricing for ' . $product_title . '</h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="product-modal__body modal-body">
        <div class="product-modal__body--header">
          <div class="row">
            <div class="col-xs-3">
              <div class="product-modal__body--product-logo">' . $product_logo . '</div>
            </div>
            <div class="col-xs-9">
              <h4 class="product-modal__body--header-text">How can we contact you with pricing info?</h4>
              <p>Make sure this product is right for your budget. Our experts will be in touch with all the pricing info you need.</p>
            </div>
          </div>
        </div>
        <form name="product-modal-pricing" class="product-modal__form" method="POST" action="">
          <input name="Lead Source" type="hidden" value="Get Pricing">
          <input name="Company" type="hidden" value="[not provided]">
          <input name="Category" type="hidden" value="' . $category . '">
          <input name="Last Name" type="hidden" value="[not provided]">
          <input name="Status" type="hidden" value="New">
          <input name="Products" type="hidden" value="' . $product_title . '">
          <input name="Sales Stage" type="hidden" value="MQL">
          <div class="product-modal__body--form-container">
            <div class="product-modal__input-container form-group">
              <label for="name">What is your name?</label>
              <span class="sr-only">Name</span>
              <input type="text" class="product-modal__input form-control name-input required" id="name" name="First Name">
            </div>
            <div class="product-modal__input-container form-group">
              <label for="phone">What is your phone number?</label>
              <span class="sr-only">Phone Number</span>
              <input type="text" class="product-modal__input form-control phone-input required" id="phone" name="Business Phone">
            </div>
            <div class="product-modal__input-container form-group">
              <label for="email">What is your email address?</label>
              <span class="sr-only">Email Address</span>
              <input type="text" class="product-modal__input form-control email-input required" id="email" name="Email">
            </div>
          </div>
          <div class="product-modal__footer">
            <button type="submit" value="Submit" class="product-modal__submit btn btn-primary btn-block">
              Get ' . $product_title . ' Pricing
            </button>
          </div>
        </form>
        <div class="modal__privacy-container">
          <p class="modal__privacy-guarantee mt-0">
            <i class="fa fa-lock lock-icon" aria-hidden="true"></i>By clicking the button above, I confirm that I have read and agree to the <a href="<?php echo home_url(); ?>/terms-conditions" target="_blank">Terms of Use</a> and <a href="<?php echo home_url(); ?>/privacy-policy" target="_blank">Privacy Policy.</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>';
}

add_shortcode('get-pricing', 'pricingModal');


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
        array_push( $buttons, 'cta-menu' );
        return $buttons;
    }
}


if ( ! function_exists( 'mytheme_add_buttons' ) ) {
    function mytheme_add_buttons( $plugin_array ) {
        $plugin_array['cta_buttons'] = plugin_dir_url( __FILE__ ) . 'js/tinymce_buttons.js';
        return $plugin_array;
        console.log('hey');
    }
}


//Add styles for the CTA:
$pluginURL = plugins_url("",__FILE__);
$CSSURL = "$pluginURL/css/styles.css";
wp_register_style( 'cta_styles', $CSSURL);
wp_enqueue_style('cta_styles');

?>
