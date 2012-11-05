<?php

class StatsBehavior extends ModelBehavior {
 
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
	
	public function afterFind($Model, $results, $primary = false) {
    $this->Stats =& ClassRegistry::init('Acting.ActStats');
    $this->Model = $Model;
  	$results = $this->addActStats($results); 
  	return $results;
	}
	
	public function addActStats($results = array()) {
	   if (is_object($results)) { return $results; }

	   foreach ($results as $i => $row) {
  	   if (!empty($row[$this->Model->alias]['id'])) {
    	   $ref_id = $row[$this->Model->alias]['id']; 
         $stats = $this->Stats->find('all', array(
          'fields' => array(
            'SUM(count) as total',
            'type_id',
            'ref_id',
            'Type.name',
          ),
          'join' => array(
            array(
              'table' => 'act_types',
              'alias' => 'Type',
              'type' => 'INNER',
              'conditions' => array(
                  'act_types.id = ref_id'
              )
            )
          ),
          'conditions' => array(
            'ref_id' => $ref_id,
          ),
          'group' => array('type_id')
        ));
        
      	foreach ($stats as $i => $stat) {
        	$stat['ActStats'] = array_merge($stats[$i]['ActStats'], $stats[$i][0]);
        	$type = $stat['Type']['name'];
        	$stats[$type] = $stat['ActStats'];
        	unset($stats[$i]);
      	}
      	
      	$row[$this->Model->alias]['Stats'] = $stats;	
      	
  	   }
  	   
  	   $results[$i] = $row;
  	 }
  	 
  	 return $results;
	}
}

?>