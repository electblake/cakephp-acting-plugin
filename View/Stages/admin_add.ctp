<?php echo $this->element('menu/admin', array('active' => 'stages'), array('plugin' => 'Acting')); ?>
<?php echo $this->Form->create('Stage'); ?>
<?php echo $this->Form->input('name'); ?>
<?php echo $this->Form->input('return_to', array(
  'type' => 'hidden',
  'name' => 'return_to',
  'value' => '/admin/acting/stages',
)); ?>
<?php echo $this->Form->end(array('label' => 'Add Acting Stage', 'class' => 'btn')); ?>