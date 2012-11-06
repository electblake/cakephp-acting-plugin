<?php
App::uses('AppModel', 'Model');
/**
 * ActsAggregate Model
 *
 * @property Ref $Ref
 * @property Type $Type
 */
class ActPurge extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
/*
  public $useTable   = 'act_purges';
	public $rawTable   = 'acts_raw';
	public $purgeTable = 'acts_purge';
*/
	

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Type' => array(
			'className' => 'Acting.Type',
			'foreignKey' => 'type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
