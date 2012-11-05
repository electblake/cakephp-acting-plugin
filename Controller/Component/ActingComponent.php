<?php
class ActingComponent extends Component {
  
  public $components = array('Session');
  
  public $uses = array('Acting.ActRaw', 'Acting.ActType', 'Acting.ActAggregate', 'Acting.ActPurge');
  
  public function initialize(&$c, $settings = array()) {
    parent::initialize($c, $settings);
    $this->controller = $c;
  }
  
  public function startup() {
    $this->controller->set('Acting.actor.id', $this->getActorId());
    
    // load some models we'll use
    $this->controller->loadModel('Acting.Act');
    $this->controller->uses = array_merge($this->controller->uses, $this->uses);
    App::uses('CakeTime', 'Utility');
  }
  
  public function stats($ref_id = null, $full = false) {
    
    // full populates all of the rows
    if (!$full) {
      $this->controller->ActAggregate->recursive = 2;
      $this->controller->ActAggregate->find('all', array(
        'fields' => array(
          'SUM(count) as total',
          'type_id',
          'ref_id',
        ),
        'conditions' => array(
          'ref_id' => $ref_id,
        ),
        'group' => array('type_id')
      ));
    } else { // not full only populates stats
      
      
    }
    
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
    
      $unique_to = explode('.', $type['Type']['unique_to']);
      $existingRaw = $this->controller->ActRaw->find('first', array(
        'conditions' => array(
          'ActRaw.'.$unique_to[0] => $$unique_to[0],
          'ActRaw.'.$unique_to[1] => $$unique_to[1],
          'ActRaw.actor_id' => $actor_id
        ),
        'order' => 'ActRaw.created DESC',
      ));
      
      $existingPurge = $this->controller->ActPurge->find('first', array(
        'conditions' => array(
          'ActPurge.'.$unique_to[0] => $$unique_to[0],
          'ActPurge.'.$unique_to[1] => $$unique_to[1],
          'ActPurge.actor_id' => $actor_id
        ),
        'order' => 'ActPurge.created DESC',
      ));
      
      if ($existingRaw or $existingPurge) {
        $frequency = $type['Type']['frequency_duration'];
        
        if ($existingPurge) {
          $existing['ActPurge'] = $existingPurge;
        }
        if ($existingRaw) {
          $existing['ActRaw'] = $existingRaw;
        }
        
        $master_refresh_date = 0;
        
        foreach ($existing as $alias => $existing) {
          
          $refresh_date = CakeTime::format('U', '+'.$frequency, $existing[$alias]['created']);
          
          if ($refresh_date > $master_refresh_date) {
            $master_refresh_date = $refresh_date;  
          }
          
        }

        if ($master_refresh_date <= time()) {
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
      if ($result = $this->controller->ActRaw->save(array('ActRaw' => $actData))) {
        $message = 'Success! Actor :actor_id acted out a :act_type on :ref_model with id :ref_id';
        $code = 1;
      } else {
        $message = 'Oops! Unable to get Actor :actor_id to act out a :act_type on :ref_model :ref_id';
        $code = -1;
      }
    } else {
      $message = 'Sorry! Actor :actor_id has already acted this way. A :ref_model :act_type can only be acted out every :frequency';
      $code = 0;
    }
    
    $message = String::insert($message, $actData);
    
    return array('code' => $code, 'body' => $message);
    
  }
  
  public function aggregateActs() {
    
    $types = $this->controller->ActRaw->Type->find('list');
    $message = 'Something went wrong or nothing to do.';
    foreach($types as $type_id => $type_name) {
      
      $this->controller->ActRaw->recursive = -1;
      $dayRawRows = $this->controller->ActRaw->find('all', array(
        'fields' => array(
          'id',
          'actor_id',
          'ref_model',
          'type_id',
          'ref_id',
          'created',
          'date(ActRaw.created) as day',
          'count(ActRaw.id) as count',
        ),
        'group' => array('ref_id'),
        'conditions' => array('ActRaw.type_id' => $type_id)
      ));
      
      $responseData['dayRawCount'] = count($dayRawRows);
      
      if ($dayRawRows) {
        /**
         * Take our aggregate query and format into queries that we can pass
         * into purgers etc.
         */
        foreach ($dayRawRows as $i => $row) {
        
          // start with found ActRaw
          $agRow = $row['ActRaw'];
          
          
          // reformat for ActAggregate
          unset($agRow['id']);
          $agRow['scope'] = $row[0]['day'];
          $agRow['count'] = $row[0]['count'];
          $agregateRows[] = array('ActAggregate' => $agRow);
          
          // reformat for ActPurge
          $purgeRawIds[] = $row['ActRaw']['id'];
          $purgeRows[] = array('ActPurge' => $row['ActRaw']['id']);
          unset($row);
        }
        
        if ($gregRow = $this->controller->ActAggregate->smartSaveMany($agregateRows)) {
        
          if ($rawCleanup = $this->controller->ActRaw->deleteAll(array('ActRaw.id' => $purgeRawIds), false, true)) {
            $message = String::insert('Aggregated (and purged) :dayRawCount rows', $responseData);  
          } else {
            $message = 'Problem deleting purge.';
          }
          
          
        } else {
          $message = 'Failed.';
        }
        
      } else {
        
        $message = 'There was nothing to do.';
        
      }
    }
    
    $this->controller->autoRender = false;
    $this->controller->layout = false;
    return array('body' => $message);
    
  }
  
  private function getType($type) {
    
    $type_id = $typeObj = null;

    if (is_int($type)) {
      $type_id = $type;
    } elseif (is_string($type) and $this->isUUID($type)) {
      $type_id = $type;
    } elseif (is_string($type)) {
      $typeObj = $this->controller->ActRaw->Type->findByName($type);
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