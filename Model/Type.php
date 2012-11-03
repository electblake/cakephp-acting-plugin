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
	
	public $useTable = 'act_types';
}
