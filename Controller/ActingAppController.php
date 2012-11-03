<?php
class ActingAppController extends AppController {
  var $components = array('Acting.Acting', 'Session', 'RequestHandler');
  var $helpers = array('Mustache.Mustache');
}
?>