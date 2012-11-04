<?php
App::uses('AppModel', 'Model');
/**
 * ActionableType Model
 *
 */
class ActType extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	public function beforeValidate(array $options = array()) {  	
	
  	if ($this->data[$this->alias]) {
    	$obj = $this->data[$this->alias];
    	
    	if (!empty($obj['name'])) {
      	$obj['name'] = str_replace(' ', '_', strtolower($obj['name']));	
    	}
    	
    	if (!empty($obj['frequency_one']) and !empty($obj['frequency_two'])) {
      	$obj['unique_to'] = $obj['frequency_one'].'.'.$obj['frequency_two'];
      	unset($obj['frequency_one'], $obj['frequency_two']);
    	}
    	
    	$this->data[$this->alias] = $obj;
    	
  	}
  	return true;
  	
	}
	
	public function afterFind($results, $primary = false) {
  	
  	foreach ($results as $i => $row) {
    	
    	if (!empty($row[$this->alias]['unique_to'])) {
      	
      	$unique_to = explode('.', $row[$this->alias]['unique_to']);
      	
      	foreach ($unique_to as $j => $field) {
        	
        	if ($field == 'actor_id') {
          	$unique_to[$j] = 'Actor';
        	}
        	
        	if ($field == 'ref_id') {
          	$unique_to[$j] = 'Object';
        	}
        	
        	if ($field == 'type_id') {
          	$unique_to[$j] = 'Type';
        	}
        	
      	}
      	
      	$row[$this->alias]['displayUniqueTo'] = $unique_to[0].' per '.$unique_to[1];
      	
    	}
    	
    	$results[$i] = $row;
    	
  	}
  	
  	return $results;
  	
	}
	
}
