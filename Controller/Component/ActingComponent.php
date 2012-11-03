<?php
class ActingComponent extends Component {
  
  public $components = array('Session');
  
  public $uses = array('Act', 'Type');
  
  public function initialize(&$c, $settings = array()) {
    parent::initialize($c, $settings);
    $this->controller = $c;
  }
  
  public function startup() {
    $this->controller->set('Acting.actor.id', $this->getActorId());
    
    // load some models we'll use
    $this->controller->loadModel('Acting.Act');
    App::uses('CakeTime', 'Utility');
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
  
  public function record($ref_id, $type, $model) {
    
    $this->controller->loadModel($model);
    $obj = new $model;
    $actor_id = $this->getActorId();
    $type = $this->getType($type);
    $ref_id = $ref_id;
    
    $add_new = false; // by default don't act a fool.
    
    $actData = array(
      'actor_id' => $actor_id,
      'type_id' => $type['Type']['id'],
      'ref_id' => $ref_id,
      'ref_model' => $model,
      'act_type' => $type['Type']['name'],
    );
    if (!empty($type['Type']['frequency_duration'])) {
      $actData['frequency'] = $type['Type']['frequency_duration']; 
    }
    // is this act available for this actor yet?
    if (!empty($type['Type']['unique_to']) and !empty($type['Type']['frequency_duration'])) {
      $act = new Act();
      $unique_to = explode('.', $type['Type']['unique_to']);
      
      $existing = $act->find('first', array(
        'conditions' => array(
          $unique_to[0] => $$unique_to[0],
          $unique_to[1] => $$unique_to[1]
        ),
        'order' => 'Act.created DESC',
      ));
      
      if ($existing) {
        $frequency = $type['Type']['frequency_duration'];
        $refresh_date = CakeTime::format('U', '+'.$frequency, $existing['Act']['created']);
        if ($refresh_date <= time()) {
          $add_new = true;
        } else {
          $add_new = false;
        }
      } else {
        $add_new = true;
      }
      
    } else {
      $add_new = true;
    }
        
    if ($add_new) {
      $act = new Act();
      if ($result = $act->save(array('Act' => $actData))) {
        $message = String::insert('Success! Actor :actor_id acted out a :act_type on :ref_model with id :ref_id', $actData);
        $code = 1;
      } else {
        $message = String::insert('Oops! Unable to get Actor :actor_id to act out a :act_type on :ref_model :ref_id', $actData);
        $code = -1;
      }
    } else {
      
      $message = String::insert('Sorry! Actor :actor_id has already acted this way. A :ref_model :act_type can only be acted out every :frequency', $actData);
      $code = 0;
      
    }
    
    return array('code' => $code, 'body' => $message);
    
  }
  
  private function getType($type) {
    
    $type_id = $typeObj = null;

    if (is_int($type)) {
      $type_id = $type;
    } elseif (is_string($type) and $this->isUUID($type)) {
      $type_id = $type;
    } elseif (is_string($type)) {
      $typeObj = new Act();
      $typeObj = $typeObj->Type->findByName($type);
    }
    
    if (!$typeObj and $type_id) {
      $typeObj = new Act();
      $typeObj = $typeObj->Type->findById($type_id);
    }
    return $typeObj;
  }
  
  private function isUUID($string) {
    
    $pattern = '#[0-9aA-zZ]{8}-[0-9aA-zZ]{4}-[0-9aA-zZ]{4}-[0-9aA-zZ]{4}-[0-9aA-zZ]{12}#';
    if (preg_match($pattern, $string)) {
      return true;
    } else {
      return false;
    }
    
    return false;
    
  }
  
}