<?php
/**
 * Snip.it button.
 * @author Cedric Han <cedric@snip.it>
 * @author Snipbot
 * @copyright 2012 Snipbook, Inc
 */
abstract class Snipit_Button {

  /**
   * Prints out the <div> that will eventually get replaced
   * with a snip.it button to snip the current post.
   * @see https://snip.it/toolkit
   * @param array $attrs Attributes given to the shortcode.
   * @return the markup to replace the shortcode.
   */
  public static function RenderShortcode($attrs) {
    $attrs = wp_parse_args($attrs, array('permalink' => get_permalink()));
    return self::GetPlaceholder($attrs['permalink']) . self::GetScript();

  }

  public static function GetScript() {
    ob_start();?>
<script type="text/javascript">
  (function(id) { if(document.getElementById(id)){return;}
    var s = document.createElement('script');
    s.id = id;
    s.type = 'text/javascript';
    s.async = true;
    s.src = '//cdn.snip.it/javascripts/button.js';
    var x = document.getElementsByTagName('script')[0];
    x.parentNode.insertBefore(s, x);
  })("snipit-button");
</script>
<?php
    return ob_get_clean();
  }

  public static function GetPlaceholder($url) {
    ob_start();?>
<div class="snip-it-btn" data-href="<?php echo esc_attr($url)?>"></div>
<?php
    return ob_get_clean();
  }
}
