<?php
/**
 * Snip.it widget plugin.
 * @author Cedric Han <cedric@snip.it>
 * @author Snipbot
 * @copyright 2012 Snipbook, Inc
 */
class Snipit_Collection_Widget extends WP_Widget {

  const SCRIPT_URL = '//cdn.snip.it/javascripts/widgets.js';
  const COLLECTION_URL_PATTERN = '|^(?:http(?:s?)://)snip.it/collections/(\d+)|';

  /**
   * Register widget with WordPress.
   */
  public function __construct() {
    parent::__construct(
      'snipit_collection_widget', // Base ID
      'Snip.it Collection', // Name
      array(
        'classname' => 'snipit_collection_widget',
        'description' => 'Display your Snip.it collection.'
      ) // Options
    );
  }

  /**
   * Render the widget
   *
   * @param array $args Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    $title = $instance['title'];
    $collection_url = $instance['collection_url'];
    $num_snips = $instance['num_snips'];
    if(!$collection_url || !$num_snips) {
      return;
    }

    if(!preg_match(self::COLLECTION_URL_PATTERN, $collection_url, $matches)) {
      return;
    }
    $folder_id = $matches[1];
    extract( $args, EXTR_SKIP );

    ?>
    <?php echo $before_widget;?>
    <?php if($title) echo $before_title . apply_filters('widget_title', $title) . $after_title; ?>
    <a href="<?php echo esc_attr($collection_url); ?>" class="snipit-widget" data-numsnips="<?php echo esc_attr($num_snips); ?>" data-folderid="<?php echo esc_attr($folder_id); ?>">Snip.it</a>
    <script type="text/javascript">
      (function(d,id) { if(d.getElementById(id)){return;}var s=d.createElement('script');s.type='text/javascript';s.async=true;s.id=id;s.src='<?php echo self::SCRIPT_URL; ?>?cb=' + Math.floor(Math.random()*1000000000);var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);})(document,"snipit-widget");
    </script>
    <?php echo $after_widget;?>
    <?php
  }


  /**
   * Sanitize widget parameters before saving.
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {

    // Setup some defaults
    $old_instance = wp_parse_args($old_instance, array('num_snips' => 5));

    // Sanitize num_snips
    $num_snips = (int)$new_instance['num_snips'];
    if(!$num_snips || !is_integer($num_snips) || $num_snips < 3 || $num_snips > 6) {
      $new_instance['num_snips'] = $old_instance['num_snips'];
    }

    // Sanitize collection_url
    $collection_url = trim($new_instance['collection_url']);
    if(!preg_match(self::COLLECTION_URL_PATTERN, $collection_url)) {
      $new_instance['collection_url'] = $old_instance['collection_url'];
    }

    return $new_instance;
  }


  /**
   * Renders the options form on the admin site.
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    $instance = wp_parse_args($instance, array(
      'title' => 'Snip.it',
      'num_snips' => 5
    ));
    $title = $instance['title'];
    $collection_url = $instance['collection_url'];
    $num_snips = $instance['num_snips'];
    ?>
      <p>
        Display an attractive widget showcasing a <a href="https://snip.it/" target="_blank">Snip.it</a> collection.
      </p>
      <p>
        Note, this widget will include a link to <a href="https://snip.it/" target="_blank">Snip.it</a> at the bottom.
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('collection_url'); ?>">Copy and paste the URL of the collection to show:</label>
        <input class="widefat" id="<?php echo $this->get_field_id('collection_url'); ?>" name="<?php echo $this->get_field_name('collection_url'); ?>" type="text" value="<?php echo esc_attr($collection_url); ?>" />
        <em style="color: #999; font-size: 11px; ">Example:<br/>https://snip.it/collections/27753-Cinema</em>
      </p>
      <p>
        <label for="<?php echo $this->get_field_id('num_snips'); ?>">Number of snips to show:</label>
        <select id="<?php echo $this->get_field_id('num_snips'); ?>" name="<?php echo $this->get_field_name('num_snips'); ?>">
          <?php for ($i=3; $i <= 6; $i++) { ?>
            <option <?php if($i == $num_snips) echo "selected"; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php } ?>
        </select>
      </p>
    <?php
  }

}
