<?php
App::uses('AppModel', 'Model');
/**
 * ActsAggregate Model
 *
 * @property Ref $Ref
 * @property Type $Type
 */
class ActAggregate extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
  //public $useTable   = 'act_aggregates';
	

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
		)
	);
	
	
	public function smartSaveMany($rows = array()) {
  	
  	$newRows = array();
    foreach ($rows as $i => $row) {
    	
    	/**
    	* check to see if there is an existing aggregate row
    	* we should be incrementing
    	*/
    	$existFind = array('conditions' => array(
    	 'type_id' => $row[$this->alias]['type_id'],
    	 'ref_id' => $row[$this->alias]['ref_id'],
    	 'scope' => $row[$this->alias]['scope']
    	));
    	
    	if ($exist = $this->find('first', $existFind)) {
    	
    	  $row[$this->alias]['id'] = $exist[$this->alias]['id'];
    	  $row[$this->alias]['count'] += $exist[$this->alias]['count'];
      	unset($exist);
    	}
    	
    	$rows[$i] = $row;
    	unset($row);
    	
  	}
  	return $this->saveMany($rows);
	}
	
}
