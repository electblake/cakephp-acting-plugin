<?php

class StageBehavior extends ModelBehavior {
 
 var $_defaults = array();
 
/**
 * Initiate behaviour
 *
 * @param object $Model
 * @param array $settings
 */
	public function setup(Model $Model, $settings = array()) {
		$this->settings[$Model->alias] = array_merge($this->_defaults, $settings);
		$this->Model = $Model;
		$this->alias = $Model->alias;
	}
	
	/*
	public function actingStats($results, $primary = false) {
		
		$alias = $this->alias;
    
  	$this->ActingStats->recursive = 2;
    $stats = $this->ActingStats->find('all', array(
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
    
    Debugger::dump($stats);
	}
	*/
}

?>