<?php 
/**
 * Users CakePHP Plugin
 *
 * Copyright 2010 - 2011, Cake Development Corporation
 *                 1785 E. Sahara Avenue, Suite 490-423
 *                 Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @Copyright 2010 - 2011, Cake Development Corporation
 * @link      http://github.com/CakeDC/users
 * @package   plugins.users.config.schema
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class actingSchema extends CakeSchema {
	var $name = 'acting';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $acts = array(
		'id'        => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
		'actor_id'  => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'type_id'   => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36),
		'ref_id'  => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'ref_model'  => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 255),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
	);
}
