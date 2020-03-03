<?php

abstract class AbsAction extends CAction
{
	const ADDITIONAL_URL_PARAM = 
	[	
		'_arkcampaign' => 0,
		'_arkpublisher' => 0,
	];
	protected function get(&$input)
	{
		$this->methodNotSupport('GET');
	}
	protected function post(&$input)
	{
		$this->methodNotSupport('POST');

	}
	protected function put(&$input)
	{
		$this->methodNotSupport('PUT');

	}
	protected function delete(&$input)
	{
		$this->methodNotSupport('DELETE');

	}
	

	public function run()
	{
		
		$request_method = $_SERVER['REQUEST_METHOD'];
		// print_r($_SERVER);
		// return;
		$result = null;
		switch ($request_method) {
			case 'GET':
				$input = isset($_GET['input']) ? json_decode($_GET['input'], true) : null;
				null === $input && !empty($_GET) ? $input = $_GET : null;
				$result = $this->serveGet($input);
				break;
			case 'POST':
				$input = isset($_POST['input']) ? json_decode($_POST['input'], true) : null;
				null === $input && !empty($_POST) ? $input = $_POST : null;
				$result = $this->servePost($input, $request_method);
				break;
			case 'PUT':
				$result = $this->servePut();
				break;
			case 'DELETE':
				$result = $this->serveDelete();
				break;	
		}
		echo json_encode($result);
	}

	private function serveGet($input)
	{
		$this->addUrlParams($input);
		// DevUtils::cl($input, 'input', __METHOD__, __LINE__);exit;
		return $this->get($input);
	}

	private function servePost($input)
	{
		$this->addUrlParams($input);
		return $this->post($input);
	}

	private function servePut()
	{
		$input = [];
		$this->controller->parse_raw_http_request($input);
		$this->addUrlParams($input);
		return $this->put($input);
	}


	private function serveDelete()
	{
		$input = [];
		$this->controller->parse_raw_http_request($input);
		$this->addUrlParams($input);
		$urlParams = $this->getUrlParamsDimensions('user');
		if(!empty($urlParams))
		{
			$input['url_params'] = $urlParams;
		}
		
		return $this->delete($input);
	}

	private function addUrlParams(&$input)
	{
		foreach (self::ADDITIONAL_URL_PARAM as $paramName => $defaultValue) 
		{
			$x = Yii::app()->getRequest()->getQuery($paramName);
			$input[$paramName] = empty($x) ? $defaultValue : $x;
		}
	}

	private function methodNotSupport($mathod)
	{
		$mathod = mb_strtolower($mathod);
		return json_encode(array('status' => 0, 'message' => " '{$mathod}' method not supported"));
	}

	protected function getUrlParamsDimensions($urlControllerName)
	{
		// empty($urlControllerName)
		$request_uri = $_SERVER['REQUEST_URI'];
		$parts = explode('/', $request_uri);
		$parts = array_filter($parts, function($val){return !empty($val);});
		$element = end($parts);
		$elements = [];
		$element == $urlControllerName ? null : array_unshift($elements, $element);
		DevUtils::cl($_SERVER, '$_SERVER', __METHOD__, __LINE__);exit;
		DevUtils::cl(Yii::app()->urlManager->rules, 'rules', __METHOD__, __LINE__);exit;
		DevUtils::cl(get_class($this->controller), 'get class', __METHOD__, __LINE__);exit;
		$i = 0;
		while($i++ < 10 && $element != $urlControllerName)
		{
			$element = prev($parts);
			$element == $urlControllerName ? null : array_unshift($elements, $element);
			
			// $fieldName = Yii::t('LeadAggregationDic', $element);

			// DevUtils::c($fieldName, 'fieldName');exit;
		}

		return $elements;

	}
}

