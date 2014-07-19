<?php
/*
* Plugin Name: WP No JS Social Share
* Description: This allows folk to share blog posts with social networks without needing to rely on JavaScript
* Version: 0.1
* Author: Toby Osbourn
* Author URI: http://www.tosbourn.com
* License: Apache 2.0
*/

$table_name = $wpdb->prefix . 'no-js-social-share';
$table_version = '0.1';

function social_activate() {
  global $wpdb, $table_name, $table_version;

  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT,
    network_name varchar(100) NOT NULL,
    link_pattern varchar(300) NOT NULL,
    network_active boolean NOT NULL,
    network_username varchar(300),
    UNIQUE KEY id(id)
  )";

  require_once ABSPATH . 'wp-admin/includes/upgrade.php';

  dbDelta($sql);

  $facebook = array(
    'network_name' => 'Facebook',
    'link_pattern' => 'http://www.facebook.com/sharer/sharer.php?u=^',
    'network_active' => true
  );

  $twitter = array(
    'network_name' => 'Twitter',
    'link_pattern' => 'https://twitter.com/intent/tweet?text=^&url=^&via=^',
    'network_active' => false
  );

  $google_plus = array(
    'network_name' => 'Google+',
    'link_pattern' => 'https://plus.google.com/share?url=^',
    'network_active' => false
  );

  $linkedin = array(
    'network_name' => 'LinkedIn',
    'link_pattern' => 'http://www.linkedin.com/shareArticle?mini=true&url=^&title=^&summary=^&source=^',
    'network_active' => false
  );

  $reddit = array(
    'network_name' => 'Reddit',
    'link_pattern' => 'http://www.reddit.com/submit?url=^',
    'network_active' => true
  );

  $hacker_news = array(
    'network_name' => 'Hacker News',
    'link_pattern' => 'http://news.ycombinator.com/submitlink?u=^&t=^',
    'network_active' => false
  );

  $buffer = array(
    'network_name' => 'Buffer',
    'link_pattern' => 'http://bufferapp.com/add?text=^&url=^',
    'network_active' => false
  );

  $wpdb->insert($table_name, $facebook);
  $wpdb->insert($table_name, $twitter);
  $wpdb->insert($table_name, $google_plus);
  $wpdb->insert($table_name, $linkedin);
  $wpdb->insert($table_name, $reddit);
  $wpdb->insert($table_name, $hacker_news);
  $wpdb->insert($table_name, $buffer);

  add_option('wp-no-js-social-share-version', $table_version);
}

function social_deactivate() {
  global $wpdb, $table_name;

  mysql_query("DROP TABLE $table_name") or die (mysql_error());
}

function social_menu() {
  add_options_page(
    'Social Sharing',
    'Social Sharing',
    'edit_plugins',
    'social-menu-button',
    'change_social'
  );
}

function change_social() {
  if (!current_user_can('edit_plugins')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }
  echo '<div class="wrap">';
  echo '<h1>Edit your social sharing options</h1>';
  echo '<p>Before you enable Twitter or Hacker News you must provide a username for them</p>';
  echo '</div>'; 
}

add_action('admin_menu', 'social_menu');
register_activation_hook(__FILE__, 'social_activate');
register_deactivation_hook(__FILE__, 'social_deactivate');
?>
