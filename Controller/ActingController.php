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
    $news_id = '50908d3b-6290-45e3-89bb-3e19aee3e8ef';
    $record = $this->Acting->record($news_id, 'view', 'News');
    
    
    $this->set('response', $record);
	  /**
	   * Do JSON
	   */
	  if($this->RequestHandler->isAjax()) {
  	  $this->RequestHandler->respondAs('json');
	  }
	  
	  return new CakeResponse($record);
    
  }
  
  public function admin_test_record() {
    
  }
  

}

