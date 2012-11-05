<?php
App::uses('AppModel', 'Model');
/**
 * Actionable Model
 *
 * @property Type $Type
 */
class ActStats extends AppModel {

  public $useTable = 'act_aggregates';
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
	);
	
}
