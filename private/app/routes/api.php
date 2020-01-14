<?php
Flight::route('/api/v1/@section:[a-z,\-]+(/@id:[0-9]+)', function($section, $id){
  $api = new ApiController();
  Flight::json($api->makeCall(Flight::request()->method, $section, $id, $_POST, $_FILES));
});
?>
