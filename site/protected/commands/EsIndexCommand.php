<?php

class EsIndexCommand extends CConsoleCommand 
{

	public function actionGermans()
	{
		$a = [
			'b' =>['c' => 44],
		];
		DevUtils::c($a['b.c'], 'b.c test');
	}

	public function actionCreateInstructionBall()
	{
		$indexName = EsInstructionBallHelper::ES_INDEX_PREFIX();
		// echo "\n---------------{$indexName}\n";exit;
		ElasticsearchApi::createEmptyIndex(Yii::app()->params['elasticsearch_base_url'], $indexName);
	}

	public function actionCreateCandidateBall()
	{
		$indexName = EsCandidateBallHelper::ES_INDEX_PREFIX();
		// echo "\n---------------{$indexName}\n";exit;
		$response = ElasticsearchApi::createEmptyIndex(Yii::app()->params['elasticsearch_base_url'], $indexName);

		echo '<pre>'; print_r($response);
	}
// delete old log documents from ES
	public function actionDeleteOldEsLogs()
	{
		$daysToKeep = Yii::app()->params['elasticsearch_max_log_days'];
		$all_time_alias = EsLogHelper::getAlias(EsLogHelper::ES_INDEX_ALIAS_ALL_TIME_SEARCH);
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url'], $all_time_alias);
		$indexes = array_keys($aliasList);
		arsort($indexes);
		$this->deleteOldIndex($indexes, $daysToKeep);
		// DevUtils::c($indexes, 'days to delete $indexes');exit;
		// while(count($indexes) > $daysToKeep)
		// {
		// 	$indexToDelete = array_pop($indexes);
		// 	$response = ElasticsearchApi::deleteIndex(Yii::app()->params['elasticsearch_base_url'], $indexToDelete);
		// }
	}

	public function actionDeleteOldSMSMessage()
	{
		$daysToKeep = Yii::app()->params['elasticsearch_max_message_days'];
		$all_time_alias = EsMessageHelper::getAlias(EsMessageHelper::ES_INDEX_ALIAS_ALL_TIME_SEARCH);
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url'], $all_time_alias);
		$indexes = array_keys($aliasList);
		arsort($indexes);
		$this->deleteOldIndex($indexes, $daysToKeep);
	}

	//delete old version indecess from ES DB
	public function actionDeleteOldIndexVersion($indexPrefix)
	{
		if(empty($indexPrefix))
		{
			return 'error empty indexprefix';
		}
		$indexPrefix .= '*';
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url'], $indexPrefix);
		$indexes = array_keys($aliasList);
		
		$this->deleteIndexesList($indexes);
	}
	

	private function deleteIndexesList($indexes)
	{
		while(count($indexes) > 0)
		{
			$indexToDelete = array_pop($indexes);
			$response = ElasticsearchApi::deleteIndex(Yii::app()->params['elasticsearch_base_url'], $indexToDelete);
			DevUtils::c($response, "response of delete index {$indexToDelete}");
		}
	}
	public function actionReIndex($map)
	{
		$mapObj = new $map();
		$response = $mapObj->reIndex();
		DevUtils::c($response, 'response');
		// DevUtils::c($mapObj, 'mapObj');
	}	

	public function actionReIndexAll($map)
	{
		$mapObj = new $map();
		$response = $mapObj->reIndexAll();
		DevUtils::c($response, 'response');
		// DevUtils::c($mapObj, 'mapObj');
	}


// map builder 
	public function actionApplyMap($map)
	{

		$mapObj = new $map();
		$response = $mapObj->createMap();
		DevUtils::c($response, 'response');
		DevUtils::c($mapObj, 'mapObj');
	}

	public function actionGetCostumLostLeads()
	{
		$response = ElasticsearchApi::searchDocument( Yii::app()->params['elasticsearch_base_url'],
															'production_syrup_conversion_alias_insert',
															'conversion',
															['size' => 0]);
		DevUtils::cl($response, '$response', __METHOD__, __LINE__);
	}

	public function actionInsertIndexFromJson()
	{
		$newLeads = Yii::app()->basePath . '/runtime/newLeads.json';
		$leads = file_get_contents($newLeads);
		$response = json_decode($leads, true);

		foreach ($response['hits']['hits'] as $input) 
		{

			$index = 'production_syrup_v2_conversion_2015-12';//'oz_test_syrup_v2_conversion_2015-12';
			$input['_index'] = $index;

			DevUtils::cl($input['_source']['metadata']['conversion_unique'], 'conversion_unique', __METHOD__, __LINE__);
			$response = ElasticsearchApi::upsertDocument( Yii::app()->params['elasticsearch_base_url'],
															$input['_index'],
															$input['_type'],
															null,//$input['_id'],
															$input['_source'],
															false);
			
			DevUtils::cl($response, '$response', __METHOD__, __LINE__);
		}
		
	}
	public function actionReIndexAllConversionByLast($type = null, $esHelperClass = 'EsConversionHelper')
	{
 
		$aliasList = ElasticsearchApi::getAliasList(Yii::app()->params['elasticsearch_base_url']);
		$mapping = ElasticsearchApi::getMapping(Yii::app()->params['elasticsearch_base_url'], $esHelperClass::getAlias($esHelperClass::ES_INDEX_ALIAS_INSERT), null);
		$lastIndexName = array_keys($mapping)[0];
		$mapping = $mapping[$lastIndexName];
		
		$toIndexPrefix = $esHelperClass::ES_INDEX_PREFIX();
		$fromIndexPrefix = $esHelperClass::ES_INDEX_PREFIX($esHelperClass::FROM_VERSION);

		$currentIndex = 
		null === $newIndex ? $newIndex = $index : null;
		$this->createMapClass($index, $newIndex, $type);
	}

	public function actionCreateMapClass($index, $newIndex = null, $type = null, $esHelperClass = 'EsConversionHelper')
	{
		if(null === $newIndex || empty($newIndex))
		{
			$newIndex = $esHelperClass::ES_INDEX_PREFIX();
			$postfix = $esHelperClass::getPostfixDate();
			empty($postfix) ? null : $newIndex .= '_' . $postfix;
		}
		// DevUtils::c($newIndex, 'esIndex newIndex');exit;
		$this->createMapClass($index, $newIndex, $type, $esHelperClass);
	}

	public function createMapClass($index,  $newIndex, $type, $esHelperClass = 'EsConversionHelper')
	{

		$typeName = null === $type || empty($type) ? '' : "__{$type}";
		$className = "map__{$newIndex}{$typeName}__2vx";
		$className = preg_replace('~-~', '_', $className);
		$fname = dirname(__FILE__) . "/../esIndexing/{$className}.php";
		// DevUtils::c($fname, 'fname');exit;
		$mapping = ElasticsearchApi::getMapping(Yii::app()->params['elasticsearch_base_url'], $index, $type);

		$fileContent = $this->buildMapFileTemplate($mapping, $className, $index, $newIndex, $type, $esHelperClass);

		$fileHandler = null;
		try{
			if(file_exists($fname))
			{
				unlink($fname);
			}
			$fileHandler = fopen($fname, "w") or die("Unable to open file! {$fname}");
			fwrite($fileHandler, $fileContent);
		}
		catch(Exception $e)
		{}

		try{fclose($fileHandler);}catch(Exception $e){}
		
		return $className;
		// print_r ($fileContent);
	}

	private function deleteOldIndex(&$indexes, $daysToKeep)
	{
		
		// DevUtils::c($indexes, 'days to delete $indexes');exit;
		while(count($indexes) > $daysToKeep)
		{
			$indexToDelete = array_pop($indexes);
			$response = ElasticsearchApi::deleteIndex(Yii::app()->params['elasticsearch_base_url'], $indexToDelete);
		}
	}

	private function buildMapFileTemplate(&$mapping, $className, $index, $newIndex, $type, $esHelperClass)
	{

		$mapping_str = null;
		$mappingToExport = null === $type ? $mapping[$index] : $mapping[$index]['mappings'];
		$mapping_str = var_export($mappingToExport, true);

		$content = 
"<?php

class {$className} extends EsMappingIndexingAbs
{
	public function getNewIndex()
	{
		" . '$esHelperClass = $this->getEsHelperClass();
    	return $esHelperClass::ES_INDEX_PREFIX() . "_" . date("Y-m", time()); ' . "
	}

	public function getOldIndex()
	{
		return '{$index}';
	}

	public function getType()
	{
		return '{$type}';
	}

	public function getEsHelperClass()
	{
		return '{$esHelperClass}';
	}

	public function getMapping()
	{
		return {$mapping_str};
	}
	// end of getMapping
}
		";

		return $content;
		

	}

}
