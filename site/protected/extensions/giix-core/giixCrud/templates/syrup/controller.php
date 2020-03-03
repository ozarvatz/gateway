<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
 
$spl = preg_split('/(?=[A-Z])/', $this->modelClass, -1, PREG_SPLIT_NO_EMPTY);
$underlined = strtolower(implode('_', $spl));

?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass; ?> {

    public function filters()
	{
        return array(
            'accessControl',
        );
    }

    public function accessRules()
	{
        return array(
		/* - add '<?php echo $this->modelClass; ?>' task to rbac
            array('allow',
                'actions'=>array(),
                'roles'=>array('<?php echo $this->modelClass; ?>'),
            ),
            array('deny',
                'users'=>array('*'),
            ),
		*/
        );
    }

	public function actions()
	{
        return array(
			
        );
    }
	
    public function actionIndex()
	{
        $model = new <?php echo $this->modelClass; ?>('search');
        $model->unsetAttributes();
		
		$this->setFilters($model, '<?php echo $this->modelClass; ?>');
		
		$this->breadcrumbs = array(
			array('target' => null, 'label' => $model->label(2)),
		);
		
		$this->contentTitle = Yii::t('app', 'Manage') . ' ' . GxHtml::encode($model->label(2));
		
        $this->render('index', array('model' => $model));
    }

	public function actionCreate()
	{
		$model = new <?php echo $this->modelClass; ?>();
<?php if ($this->enable_ajax_validation): ?>

		$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
<?php endif; ?>

		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->setAttributes($_POST['<?php echo $this->modelClass; ?>']);
			
			if($model->save())
			{
				if(Yii::app()->getRequest()->getIsAjaxRequest())
					Yii::app()->end();
				else
					$this->redirect(array('index', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>));
			}
		}

		$this->breadcrumbs = array(
			array('target' => 'index', 'label' => $model->label(2)),
			array('target' => null, 'label' => Yii::t('app', 'Create')),
		);
		
		$this->contentTitle = Yii::t('app', 'Create') . ' ' . GxHtml::encode($model->label());
		
		$this->render('_form', array('model' => $model));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, '<?php echo $this->modelClass; ?>');
<?php if ($this->enable_ajax_validation): ?>

		$this->performAjaxValidation($model, '<?php echo $this->class2id($this->modelClass)?>-form');
<?php endif; ?>
			
		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
			$model->setAttributes($_POST['<?php echo $this->modelClass; ?>']);

			if($model->save())
			{
				$this->redirect(array('index', 'id' => $model-><?php echo $this->tableSchema->primaryKey; ?>));
			}
		}
		
		$this->breadcrumbs = array(
			array('target' => 'index', 'label' => $model->label(2)),
			array('target' => null, 'label' => Yii::t('app', 'Update')),
		);
		
		$this->contentTitle = implode(' ', array(
			Yii::t('app', 'Update'),
			GxHtml::encode($model->label()),
			'<span class="update_title_content">',
			GxHtml::encode(GxHtml::valueEx($model)),
			'</span>',			
			'(#' . $model->primaryKey . ')',
		));
		
		$this->render('_form', array('model' => $model));
	}
	
	public function actionDelete($id)
	{
		if(Yii::app()->getRequest()->getIsPostRequest())
		{
			$model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
			
<?php if($this->hasColumnThatEndsWith('_deleted')) { ?>
			$model->setAttribute('<?php echo $underlined; ?>_deleted', 1);
			$model->update();
<?php } else { ?>
			$model->delete();
<?php } ?>
			
			if(!Yii::app()->getRequest()->getIsAjaxRequest())
			{
				$this->redirect(array('index'));
			}
		}
		else
		{
			throw new CHttpException(400, Yii::t('app', 'Your request is invalid.'));
		}
	}
	
<?php if($this->hasColumnThatEndsWith('_active')) { ?>
	public function actionUpdateActive($id, $state)
	{
		$state = $state == 1 ? 1 : 0;
		
		$model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
		$model->setAttribute('<?php echo $underlined; ?>_active', $state);
		
		echo $model->save() ? 'OK' : 'NOT OK';
	}
<?php } ?>
}