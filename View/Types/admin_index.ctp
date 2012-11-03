<?php echo $this->element('menu/admin', array('active' => strtolower($pluralModel)), array('plugin' => 'Acting')); ?>
<?php echo $this->Mustache->render(strtolower($pluralModel).'/admin_index'); ?>