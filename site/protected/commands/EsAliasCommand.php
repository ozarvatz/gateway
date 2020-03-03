<?php

class EsAliasCommand extends CConsoleCommand 
{
	public function actionGermans($esHelperClass = 'EsConversionHelper')
	{
		$tmp = $esHelperClass::CONVERSION_ES_INDEX_PREFIX();
		DevUtils::c($tmp, '$the germans got there first 1');
		// $tmp = EsConversionHelper::$tmp();

		// DevUtils::c($tmp, '$the germans got there first 2');
	}
	public function actionCandidateBallAlias($esHelperClass = 'EsCandidateBallHelper')
	{
		$this->actionCampaignBallAlias($esHelperClass);
	}
	//wright now only 2 alias for instaction  
	public function actionCampaignBallAlias($esHelperClass = 'EsInstructionBallHelper')
	{
		$actions = [];
		$aliasList = [];
		$indexName = $esHelperClass::ES_INDEX_PREFIX($esHelperClass::VERSION);
		$formerIndex = $esHelperClass::ES_INDEX_PREFIX($esHelperClass::FROM_VERSION);
		$this->createIndexIfNecessary($indexName, $formerIndex, $aliasList);

		Yii::log(get_class($this) . '::' . __FUNCTION__  . " INFO create new aliases for index : {$indexName}", 'info', 'system');
		// DevUtils::c($aliasList[$indexName],'$aliasList');exit;
		if(!array_key_exists($indexName, $aliasList))
		{
			throw new Exception("Error faild to create index {$indexName}", 1);
		}

		$this->addAliasToArray($actions, $aliasList, $indexName, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_INSERT), 'add');
		$this->addAliasToArray($actions, $aliasList, $indexName, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_ALL_TIME_SEARCH), 'add');
		
		$response = ElasticsearchApi::indexAlias(Yii::app()->params['elasticsearch_base_url'], $actions);

		$json_response = json_encode($response);
		Yii::log(get_class($this) . '::' . __FUNCTION__  . " INFO create new aliases for index {$indexName} response {$json_response}", 'info', 'system');
		
		DevUtils::cl($actions, '$actions',  __METHOD__, __LINE__);
		DevUtils::cl($response, '$response',  __METHOD__, __LINE__);


	}

	//run evry day at the first 00:01 o'clock  
	public function actionDurationAlias($esHelperClass = 'EsConversionHelper', $duration = 'm')
	{
		$actions = [];
		$aliasList = [];
		// $currdate = '2016-08-10';
		$currdate = date('Y-m-d', time());

		switch ($duration) {
			case 'm':
				$currMonth = date('Y-m', strtotime($currdate));
				$lastMonth = date('Y-m', strtotime("-1 month",strtotime($currdate)));
				$last2Month = date('Y-m', strtotime("-2 month",strtotime($currdate)));
				$last3Month = date('Y-m', strtotime("-3 month",strtotime($currdate)));
				break;
			case 'd':
				$currMonth = date('Y-m-d', strtotime($currdate));
				$lastMonth = date('Y-m-d', strtotime("-1 day",strtotime($currdate)));
				$last2Month = date('Y-m-d', strtotime("-2 day",strtotime($currdate)));
				$last3Month = date('Y-m-d', strtotime("-3 day",strtotime($currdate)));
				break;
			default:
					DevUtils::c("duration {$duration}, UNSUPPORT"); exit;
				break;
		}
		// DevUtils::c("$currMonth");
		// DevUtils::c("$lastMonth");
		// DevUtils::c("$last2Month");
		// DevUtils::c("$last3Month");exit;
		// DevUtils::c($esHelperClass, __CLASS__ . '(' . __LINE__ .') esHelperClass');exit;

		$indexName = sprintf("%s_%s", $esHelperClass::ES_INDEX_PREFIX($esHelperClass::VERSION), $currMonth);
		// $deleteIndexAlias =  sprintf("%s%s", $esHelperClass::CONVERSION_ES_INDEX_PREFIX, $currMonth);
		$indexName1m = sprintf("%s_%s", $esHelperClass::ES_INDEX_PREFIX($esHelperClass::VERSION), $lastMonth);
		$indexName2m = sprintf("%s_%s", $esHelperClass::ES_INDEX_PREFIX($esHelperClass::VERSION), $last2Month);
		$indexName3m = sprintf("%s_%s", $esHelperClass::ES_INDEX_PREFIX($esHelperClass::VERSION), $last3Month);

		Yii::log(get_class($this) . '::' . __FUNCTION__  . " INFO create new aliases for index : {$indexName}", 'info', 'system');
		// DevUtils::c($esHelperClass::ES_INDEX_PREFIX());
		// DevUtils::c("$indexName");
		// DevUtils::c("$indexName1m");
		// DevUtils::c("$indexName2m");
		// DevUtils::c("$indexName3m");exit;

		$this->createIndexIfNecessary($indexName, $indexName1m, $aliasList);

		// DevUtils::c($aliasList[$indexName],'$aliasList');exit;
		if(!array_key_exists($indexName, $aliasList))
		{
			throw new Exception("Error faild to create index {$indexName}", 1);
			
		}

		$this->addAliasToArray($actions, $aliasList, $indexName, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_ALL_TIME_SEARCH), 'add');
		$this->addAliasToArray($actions, $aliasList, $indexName1m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_ALL_TIME_SEARCH), 'add');
		$this->addAliasToArray($actions, $aliasList, $indexName2m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_ALL_TIME_SEARCH), 'add');
		$this->addAliasToArray($actions, $aliasList, $indexName3m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_ALL_TIME_SEARCH), 'add');

		$this->addAliasToArray($actions, $aliasList, $indexName, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_LAST_3TU_SEARCH), 'add');
		
		$this->addAliasToArray($actions, $aliasList, $indexName1m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_LAST_3TU_SEARCH), 'add');

		$this->addAliasToArray($actions, $aliasList, $indexName2m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_LAST_3TU_SEARCH), 'add');

		$this->addAliasToArray($actions, $aliasList, $indexName3m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_LAST_3TU_SEARCH), 'remove');


		
		
		$this->addAliasToArray($actions, $aliasList, $indexName1m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_INSERT), 'remove');
		
		$this->addAliasToArray($actions, $aliasList, $indexName2m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_INSERT), 'remove');
		
		$this->addAliasToArray($actions, $aliasList, $indexName3m, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_INSERT), 'remove');

		$this->addAliasToArray($actions, $aliasList, $indexName, $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_INSERT), 'add');
		
		$response = ElasticsearchApi::indexAlias(Yii::app()->params['elasticsearch_base_url'], $actions);

		
		$json_response = json_encode($response);
		Yii::log(get_class($this) . '::' . __FUNCTION__  . " INFO create new aliases for index {$indexName} response {$json_response}", 'info', 'system');

		DevUtils::c($actions, '$actions');
		DevUtils::c($response, '$response');

	}

	private function addAliasToArray(&$actions, &$aliasList, $indexName, $aliasName, $action = 'add')
	{
		$isAdd = strpos($action, 'add') !== false;
		$isRemove = !$isAdd;
		if($isRemove && !empty($aliasList[$indexName]) && !empty($aliasList[$indexName]['aliases']) && array_key_exists($aliasName, $aliasList[$indexName]['aliases']))
		{
			$actions[] = [
				'remove' => [
					'index' => $indexName,
					'alias' => $aliasName,
				],
			];
		}

		// DevUtils::c($aliasList , '$aliasList');exit;
		if($isAdd && array_key_exists($indexName, $aliasList) && empty($aliasList[$indexName]['aliases'][$aliasName]))
		{
			$actions[] = [
				'add' => [
					'index' => $indexName,
					'alias' => $aliasName,
				],
			];
		}
	}
	private function createIndexIfNecessary($indexName, $oldIndex, &$aliasList)
	{
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url']);
		$response = false;
		// DevUtils::cl($indexName, 'indexName', __METHOD__, __LINE__);
		// DevUtils::cl($aliasList, 'aliasList', __METHOD__, __LINE__);exit;

		if(!array_key_exists($indexName, $aliasList))
		{	
			// DevUtils::c($oldIndex, 'oldIndex');
			null === $oldIndex ? $oldIndex = $indexName : null;
			// DevUtils::c($oldIndex, 'oldIndex2');exit;
			$mapping = ElasticsearchApi::getMapping(Yii::app()->params['elasticsearch_base_url'], $oldIndex);
			$response = null;
			if(empty($mapping[$oldIndex]['mappings']))
			{
				$response = ElasticsearchApi::createEmptyIndex(Yii::app()->params['elasticsearch_base_url'], $indexName);
			}
			else
			{
				$response = ElasticsearchApi::createIndexWithMap(Yii::app()->params['elasticsearch_base_url'], $indexName, $mapping[$oldIndex]);
			}

			$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url']);
		}

		return $response;
	}

	public function actionCopyAlias($oldIndexPrefix, $newIndexPrefix, $removeInsertAlias = true)
	{
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url']);
		$indexes = array_keys($aliasList);
		$actions = [];
		$response = null;

		foreach ($aliasList as $index => $aliases) 
		{
			// DevUtils::c($index, __CLASS__ . ' index');
			// DevUtils::c($oldIndexPrefix, __CLASS__ . ' oldIndexPrefix');
			if(strpos($index, $oldIndexPrefix) !==false)
			{
				// DevUtils::c($index, __CLASS__ . ' index');exit;
				if(empty($aliases['aliases']))
				{
					continue;
				}

				foreach ($aliases['aliases'] as $alias => $aliasSettings) 
				{
					$newIndex = preg_replace("~{$oldIndexPrefix}~", $newIndexPrefix, $index);
					// DevUtils::c($newIndex, 'newIndex');
					// DevUtils::c($index, 'oldIndex');exit;
					if(in_array($newIndex, $indexes))
					{
						$this->addAliasToArray($actions, $aliasList, $newIndex, $alias, 'add');	
						if(($removeInsertAlias === true || $removeInsertAlias == 'true' || $removeInsertAlias = 1) && strpos($alias, 'insert') !== false)
						{
							$this->addAliasToArray($actions, $aliasList, $index, $alias, 'remove');
						}
					}
				}
			}
		}

		empty($actions) ? null : $response = ElasticsearchApi::indexAlias(Yii::app()->params['elasticsearch_base_url'], $actions);
		DevUtils::cl($response, 'response', __METHOD__, __LINE__);
		DevUtils::cl($actions, '$actions',  __METHOD__, __LINE__);
		// return $response;
	}
	public function actionDeleteAlias($oldIndexPrefix)
	{
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url']);
		$actions = [];
		foreach ($aliasList as $index => $aliases) 
		{
			if(strpos($index, $oldIndexPrefix) !==false)
			{
				if(empty($aliases['aliases']))
				{
					continue;
				}

				foreach ($aliases['aliases'] as $alias => $aliasSettings) 
				{
					$this->addAliasToArray($actions, $aliasList, $index, $alias, 'remove');
				}
			}
		}
		$response = ElasticsearchApi::indexAlias(Yii::app()->params['elasticsearch_base_url'], $actions);
		// DevUtils::c($actions, '$actions');exit;
		return $response;
	}
}