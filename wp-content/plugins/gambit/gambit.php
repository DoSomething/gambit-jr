<?php
/*
Plugin Name: Gambit
Description: Custom plugin to modify WP REST API responses.
Author: DoSomething.org
Version: 1.0
*/

/**
 * Removes unused elements from WP admin bar.
 */
function gambit_admin_bar_render() {
  global $wp_admin_bar;

  $wp_admin_bar->remove_menu('comments');
  $wp_admin_bar->remove_node('new-content');
  $wp_admin_bar->remove_node('view');
  $wp_admin_bar->remove_node('wp-logo');

  $args = array(
    'id'    => 'gambit_docs',
    'title' => 'Gambit documentation',
    'href'  => 'https://github.com/DoSomething/gambit/wiki/Chatbot'
  );
  $wp_admin_bar->add_menu($args);
}
add_action( 'wp_before_admin_bar_render', 'gambit_admin_bar_render' );

/**
 * Adds CSS to WP post form to remove unused elements.
 */
function gambit_admin_head() {
  echo "
  <style type='text/css'>
  #edit-slug-box {
    display: none;
  }
  #pods-meta-more-fields h2, #pods-meta-more-fields .handlediv {
    display: none;
  }
  #post-status-info {
    display: none;
  }
  #wp-content-wrap {
    display: none;
  }
  </style>
  ";
}
add_action( 'admin_head', 'gambit_admin_head' );

/**
 * Removes unused elements from WP sidebar (admin menu).
 */
function gambit_admin_menu() {
  // @see https://managewp.com/wordpress-admin-sidebar-remove-unwanted-items
  $remove_menu_items = array(__('Comments'),__('Media'),__('Pages'),__('Posts'));
  global $menu;

  end ($menu);
  while (prev($menu)){
    $item = explode(' ',$menu[key($menu)][0]);
    if(in_array($item[0] != NULL?$item[0]:"" , $remove_menu_items)){
    unset($menu[key($menu)]);}
  }
}
add_action( 'admin_menu', 'gambit_admin_menu' );

/**
 * Removes and alters default WP REST API post properties for given $data.
 */
function gambit_filter_bot_json($data, $post, $context) {
  $remove = [
    'content',
    'date',
    'date_gmt',
    'guid',
    'link',
    'modified',
    'modified_gmt',
    'slug',
    'tags',
  ];
  foreach ($remove as $property) {
    if (isset($data->data[$property])) {
      unset($data->data[$property]);
    }
  }

  // Unsetting type doesn't work in our foreach loop, so we unset here.
  if (isset($data->data['type'])) {
    unset($data->data['type']);
  }

  $data->data['title'] = $data->data['title']['rendered'];

  return $data;
}
add_filter( 'rest_prepare_campaignbot', 'gambit_filter_bot_json', 10, 3 );
add_filter( 'rest_prepare_donorschoosebot', 'gambit_filter_bot_json', 10, 3 );

?>
