<?php echo $this->element('menu/admin', array('active' => strtolower($pluralModel)), array('plugin' => 'Acting')); ?>
<?php echo $this->Form->create('Type'); ?>
<?php echo $this->Form->input('name'); ?>
<legend>Frequency Limitations</legend>
<?php echo $this->Form->input('frequency_one', array(
  'type' => 'select',
  'label' => 'Limit Each',
  'class' => 'span3',
  'options' => array(
    ''  => 'None',
    'actor_id' => 'Actor',
    )
  )); ?>
<?php echo $this->Form->input('frequency_two', array(
  'type' => 'select',
  'label' => 'Per',
  'class' => 'span3',
  'options' => array(
    ''  => 'None',
    'ref_id' => 'Referencing Object (News Item, Article ID etc)',
    'type_id' => 'Type (View, Bookmark, etc)'
    )
  )); ?>
<?php echo $this->Form->input('frequency_three', array(
  'label' => 'For (Frequency Duration)',
  'class' => 'span3',
  )); ?>
<?php echo $this->Form->input('return_to', array(
  'type' => 'hidden',
  'name' => 'return_to',
  'value' => '/admin/acting/'.strtolower($pluralModel),
)); ?>
<?php echo $this->Form->end(array('label' => 'Update Acting '.$singleModel, 'class' => 'btn')); ?>