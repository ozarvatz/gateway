<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
	$spl = preg_split('/(?=[A-Z])/', $this->modelClass, -1, PREG_SPLIT_NO_EMPTY);
	$underlined = strtolower(implode('_', $spl));
?>
<?php $ajax = ($this->enable_ajax_validation) ? 'true' : 'false'; ?>
<?php echo '<?php $errors = $model->getErrors(); ?>'; ?>


<div class="col-md-12">
	<div class="panel panel-primary" data-collapsed="0">
		<?php echo "<?php if(\$model->hasErrors()) { ?>\n"; ?>
			<div class="panel-heading">
				<div class="panel-title">
					<?php echo "<?php echo CHtml::errorSummary(\$model); ?>\n"; ?>
				</div>
				
				<div class="panel-options">

				</div>
			</div>
		<?php echo "<?php } ?>\n"; ?>
		
		<div class="panel-body">
			<?php echo '<?php '; ?>$form = $this->beginWidget('SyrupActiveForm', array(
					'id' => '<?php echo $this->class2id($this->modelClass); ?>-form',
					'enableAjaxValidation' => <?php echo $ajax; ?>,
					'enableClientValidation' => true,
					'htmlOptions' => array(
						'class' => 'form-horizontal form-groups-bordered',
						'novalidate' => 'novalidate',
						'role' => 'form',
				    ),
				));
			<?php echo '?>'; ?>
			
			
<?php foreach ($this->tableSchema->columns as $column) { ?>
<?php 	if($column->name == ($underlined . '_deleted')) continue; ?>
<?php 	if(!$column->autoIncrement) { ?>
				<div class="form-group <?php echo "<?php echo isset(\$errors['" . $column->name . "']) ? 'has-error' : ''; ?>"; ?>">
					<?php echo "<?php echo \$form->labelEx(\$model, '" . $column->name . "', array('class' => 'col-sm-3 control-label')); ?>\n"; ?>
					<div class="col-sm-5">
						<?php echo "<?php " . $this->generateActiveField($this->modelClass, $column) . "; ?>\n"; ?>
					</div>
				</div><?php echo "\n\n"; ?>
<?php 	} ?>
<?php } ?>
<?php echo "
				<div class=\"form-group\">
					<div class=\"col-sm-offset-3 col-sm-5\">
						<?php 
							echo GxHtml::submitButton(
								Yii::t('app', 'Save'),
								array(
									'value' => 'Submit',
									'name' => 'submit_button',
									'class' => 'btn btn-success',
								)
							); 
						?>	
					</div>
				</div>
					
				<?php \$this->endWidget(); ?>
			</div>
		</div>
	</div>"; ?>