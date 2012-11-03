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
class TypesController extends ActingAppController {
  
  var $components = array('Administer.Administer');
  public $name = 'Types';
  public $uses = array('Acting.Type');
  
  public function admin_index() {
    $this->set('types', $this->Type->find('all'));
  }
  
  public function admin_add() {
    $this->Administer->admin_add();
  }
  
  public function admin_edit() {
    $this->Administer->admin_edit();
  }
  

}

