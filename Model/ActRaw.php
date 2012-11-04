<?php
App::uses('AppModel', 'Model');
/**
 * Actionable Model
 *
 * @property Type $Type
 */
class ActRaw extends AppModel {

/*   public $useTable = 'act_raws'; */
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Type' => array(
			'className' => 'Acting.ActType',
			'foreignKey' => 'type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Purge' => array(
		  'className' => 'Acting.ActPurge',
		  'foreignKey' => false,
		),
	);
	
	public function beforeDelete($cascade = true) {
	
	  $fullRaw = $this->findById($this->id);
	  
	  if ($fullRaw) {
  	  $newPurge = $fullRaw['ActRaw'];
  	  $newPurge['ActRaw']['id'] = null;
  	  if ($newPurgeResult = $this->Purge->save($newPurge)) {
    	  return true;
  	  } else {
        return false;  	  
  	  }
	  } else {
  	  return true;
	  }
	  
  	return false;
	}
	
}
