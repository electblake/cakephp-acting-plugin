<?php

App::uses('ActingAppController', 'Acting.Controller');

/**
 * Acting Stages Controller
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
class StagesController extends ActingAppController {
  
  var $components = array('Administer.Administer');
  public $name = 'Stages';
  public function admin_index() {
    $this->set('stages', $this->Stage->find('all'));
  }
  
  public function admin_add() {
    $this->Administer->admin_add();
    
  }
  

}

