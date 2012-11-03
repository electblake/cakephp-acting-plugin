<?php

App::uses('ActingAppController', 'Acting.Controller');

/**
 * Acting Act Controller
 *
 * @package       Acting
 * @subpackage    Acting.Controller
 * @property	  AuthComponent $Auth
 * @property	  CookieComponent $Cookie
 * @property	  PaginatorComponent $Paginator
 * @property	  SecurityComponent $Security
 * @property	  SessionComponent $Session
 * @property	  User $User
 */
class ActingController extends ActingAppController {

  var $components = array('Acting.Acting', 'Session', 'RequestHandler');
  
  public function record() {
    
    $id = $this->Acting->getActorId();
    
	  /**
	   * Do JSON
	   */
	  if($this->RequestHandler->isAjax()) {
  	  $this->RequestHandler->respondAs('json');
	  }
    
  }
  
  public function test_record() {
    
  }
  

}

