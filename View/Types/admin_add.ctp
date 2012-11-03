<?php echo $this->element('menu/admin', array('active' => strtolower($pluralModel)), array('plugin' => 'Acting')); ?>
<?php echo $this->Form->create('ActType'); ?>
<?php echo $this->Form->input('name'); ?>
<?php echo $this->Form->input('return_to', array(
  'type' => 'hidden',
  'name' => 'return_to',
  'value' => '/admin/acting/'.strtolower($pluralModel),
)); ?>
<?php echo $this->Form->end(array('label' => 'Add Acting '.$singleModel, 'class' => 'btn')); ?>