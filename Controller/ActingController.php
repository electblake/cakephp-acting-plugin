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
  
  public function cron() {
    
    
    if (!defined('CRON_DISPATCHER')) { $this->redirect('/'); exit(); }
    $results[] = $this->Acting->aggregateActs();
    return new CakeResponse(array('body' => json_encode($results)));
    
  }
  

}

