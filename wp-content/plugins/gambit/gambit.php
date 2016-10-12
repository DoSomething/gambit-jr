<?php
/*
Plugin Name: Gambit
Description: Custom plugin to modify WP REST API responses.
Author: DoSomething.org
Version: 1.0
*/

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
