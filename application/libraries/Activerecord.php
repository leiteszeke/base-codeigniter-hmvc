<?php
  # inclue the ActiveRecord library
  require_once FCPATH . 'vendor/php-activerecord/php-activerecord/ActiveRecord.php';

  class Activerecord {
    function __construct() {
      include APPPATH . '/config/database.php';
      $dsn = [];

      if ($db) {
        foreach ($db as $name => $db_values) {
          $dsn[$name] = $db[$name]['dbdriver'] .
          '://' . $db[$name]['username'] .
          ':' . $db[$name]['password'] .
          '@' . $db[$name]['hostname'] .
          '/' . $db[$name]['database'];
        }
      }

      ActiveRecord\Config::initialize(function($cfg) use($dsn, $active_group){
        $cfg->set_model_directory(APPPATH . '/models');
        $cfg->set_connections($dsn);
        $cfg->set_default_connection($active_group);
      });
    }
  }