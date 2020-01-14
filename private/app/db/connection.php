<?php
include_once(appPath() . '/classes/PDOWrapper.php');
if ( getenv('APP_ENV') === 'local' ) { // local homestead
  Flight::register('sql', 'PDOWrapper', array('localhost', 'homestead', 'secret', 'l_appstarter_com'));
} else { // procuction
  Flight::register('sql', 'PDOWrapper', array('db_host', 'db_user', 'db_pass', 'db_name'));
}
