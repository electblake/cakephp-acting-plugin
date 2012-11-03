<?php
class ActingComponent extends Component {
  
  public $components = array('Session');
  
  public function initialize(&$c, $settings = array()) {
    parent::initialize($c, $settings);
    $this->controller = $c;
  }
  
  public function startup() {
    $this->controller->set('Acting.actor.id', $this->getActorId());
  }
  
  public function getActorId() {
    
    if ($id = $this->Session->read('Acting.actor.id')) {
      return $id;
    } else {
      $id = session_id();
      $this->Session->write('Acting.actor.id', $id);
      return $id;
    }
    
    return false; // never should happen
    
  }
  
}