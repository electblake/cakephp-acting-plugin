<?php
App::uses('AppModel', 'Model');
/**
 * ActStage Model
 *
 */
class Stage extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
/**
 * Becuase "Stage" is nicer to worth with, lets depart from database name
 */
	public $useTable = 'act_stages';
}