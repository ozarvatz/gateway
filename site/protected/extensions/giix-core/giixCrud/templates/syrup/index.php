<?php echo '<?php'; ?> $this->widget('application.components.SyrupGridView', array(
		'id' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
		'dataProvider' => $model->search(),
		'template' => '{pageSize}{items}{pager}',
		'filter' => $model,
		'filterCssClass' => 'replace-inputs',
		'cssFile' => '',
		'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
		'itemsCssClass' => 'table table-bordered datatable',
		'itemsId' => '<?php echo $this->class2id($this->modelClass); ?>-grid-item',
		'htmlOptions' => array(
			'class' => 'dataTables_wrapper',
			'role' => 'grid',
		),
		'toolbar' => array(
			array(
				'title' => 'Clear Filters',
				'href' => 'javascript: void(0);',
				'icon' => Yii::t('icon', 'Clear'),
				'class' => 'btn btn-info gridview-clear-filters',
				'htmlOptions' => array(
					'data-refresh-grid' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
				)
			),
			array(
				'title' => 'Add ' . $model->label(1),
				'href' => $this->createUrl('create'),
				'icon' => Yii::t('icon', 'Add'),
				'class' => 'btn btn-primary',
			),
		),
		'afterAjaxUpdate' => 'function(id, data){ gridViewUpdated(id, data) }',
		'ajaxUrl' => $this->createUrl('index'),
		'pageSize' => Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']),			
		'pager' => array(
			'cssFile' =>'',
			'header' => '',
			'footer' => '',
			'htmlOptions' => array(
				'class' => 'pagination pagination-sm',
			),
			'firstPageCssClass' => '',
			'lastPageCssClass' => '',
			'previousPageCssClass' => 'prev',
			'nextPageCssClass' => 'next',
			'internalPageCssClass' => '',
			'hiddenPageCssClass' => '',
			'selectedPageCssClass' => 'active',
			
			'firstPageLabel' => Yii::t('app', 'First'),
			'prevPageLabel' => Yii::t('app', 'Previous'),
			'nextPageLabel' => Yii::t('app', 'Next'),
			'lastPageLabel' => Yii::t('app', 'Last'),
		),
		'columns' => array(
	<?php
	$count = 0;
	$spl = preg_split('/(?=[A-Z])/', $this->modelClass, -1, PREG_SPLIT_NO_EMPTY);
	$underlined = strtolower(implode('_', $spl));
	
	foreach ($this->tableSchema->columns as $column)
	{
		if($column->name == ($underlined . '_deleted'))
		{
			continue;
		}
		else if($column->name == ($underlined . '_active'))
		{
			?>
			
			array(
	            'class' => 'SyrupDataColumn',
	 			'name' => '<?php echo $underlined; ?>_active',
				'filter' => CHtml::dropDownList('<?php echo $this->modelClass; ?>[<?php echo $underlined; ?>_active]', $model-><?php echo $underlined; ?>_active, array('' => '--', '1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')), array('class' => 'form-control yesno_filter')),
				'type' => 'raw',
				'value' => '
					\'<div class="toggle-active make-switch switch-mini" data-on-label="Yes" data-off-label="No" data-on="success" data-update-url="\' . Yii::app()->createUrl("<?php echo $this->controller; ?>/updateActive", array("id" => $data-><?php echo $underlined; ?>_id)) . \'">
						<input type="checkbox" \' . ($data-><?php echo $underlined; ?>_active ? "checked" : "") . \'/>
					</div>\'
				',
				'headerHtmlOptions' => array(
					'class' => 'checkbox_column',
				),								
			),
				
			<?php
		}
		else
		{
			if ($column->isPrimaryKey) {
				$id_columns_name = str_replace("'","",$this->generateGridViewColumn($this->modelClass, $column));
				$html_i = " . \' <i class=\"' . Yii::t('icon', 'Update') . '\"></i>\'";
			echo "\t\tarray(
	            'class' => 'CDataColumn',
	            'header' => '#',                                           
	            'type' => 'raw',
				'name' => '".$id_columns_name."',
	            'value' => 'CHtml::link("."$"."data->".$id_columns_name.$html_i.",Yii::app()->createUrl(\"".$this->controller."/update\", array(\"id\"=>"."$"."data->".$id_columns_name.")), array(\'class\' => \'btn btn-default btn-icon icon-left btn-xs grid-view-update\'))',
				'htmlOptions' => array(
					'title' => Yii::t('app', 'Update'),
				),
				'headerHtmlOptions'	=> array(
					'class' => 'id_column',
				),				
			),\n";
			}
			else {
				if (++$count == 7)
					echo "\t\t/*\n";
				echo "\t\t\t" . $this->generateGridViewColumn($this->modelClass, $column).",\n";
			}
		}
	}
	if ($count >= 7) {
		echo "\t\t*/\n";
	}
	?>			
                       array(
                            'class' => 'SyrupButtonColumn',
                            'template' => '{syrupDelete}',
                            'syrupDeleteButtonLabel' => '',
                            'syrupDeleteButtonOptions' => array(
					'class' => Yii::t('icon', 'SyrupDelete'),
                            ),
                            'headerHtmlOptions' => array(
					'class' => 'delete_column',                                      
                            ),
                       ),   
		),
	));
?>