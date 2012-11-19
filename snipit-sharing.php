<?php
class Snipit_Sharing extends Sharing_Source {

  const ID = 'snipit';
  const NAME = 'Snip.it';

  public static function InjectService($services) {
    $services[self::ID] = 'Snipit_Sharing';
    return $services;
  }

  public function __construct( $id, array $settings ) {
    parent::__construct( $id, $settings );

    if ( 'official' == $this->button_style ) {
      $this->smart = true;
    } else {
      $this->smart = false;
    }
  }


  public function get_name() {
    return self::NAME;
  }


  public function get_display( $post ) {
    if(!method_exists($this, 'get_share_url') ||
       !method_exists($this, 'get_link') ||
       !function_exists('sharing_register_post_for_share_counts')) {
      return;
    }

    if($this->smart) {
      return Snipit_Button::GetPlaceholder($this->get_share_url($post->ID));
    } else {
      if ( 'icon-text' == $this->button_style || 'text' == $this->button_style ) {
        sharing_register_post_for_share_counts( $post->ID );
      }
      return $this->get_link( get_permalink( $post->ID ), $this->get_name(), 'Click to Snip this', 'share=snipit', 'sharing-snipit-' . $post->ID );
    }
  }


  public function display_footer() {
    if(!method_exists($this, 'js_dialog')) {
      return;
    }

    if($this->smart) {
      print Snipit_Button::GetScript();
    } else {
      $this->js_dialog(self::ID,
                       array('width' => 609,
                             'height' => 386,
                             'resizable' => 'no',
                             'scrollbars' => 'no',
                             'status' => 'no',
                             'menubar' => 'no',
                             'toolbar' => 'no',
                             'location' => 'no'));
    }
  }


  public function process_request( $post, $post_data ) {
    // Record stats
    parent::process_request( $post, $post_data );

    // Redirect to bookmarklet here
    $url = rawurlencode($this->get_share_url( $post->ID ));
    $bookmarklet_url = "https://snip.it/button?url={$url}";
    wp_redirect($bookmarklet_url);
    die();
  }

  public function has_custom_button_style() {
    return $this->smart;
  }

}
