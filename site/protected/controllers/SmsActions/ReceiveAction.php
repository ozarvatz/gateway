<?php

class ReceiveAction extends AbsAction
{
	//listen for third party SMS push 
	//use es service
	public function post(&$input)
	{
		// DevUtils::cl($input, 'input', __METHOD__, __LINE__);
		if(count($input) < 2)
		{
			return HtmlResponse::returnArray(HtmlResponse::NO_CONTENT);
		}

		//call to main 
		//call to main with the new sms.
		$response = $this->_receiveNewMessage($input);
		
		return HtmlResponse::returnArray($response['code']);
	}

	private function _receiveNewMessage($input)
	{
		$rest = new RESTClient();
		$rest->initialize(array('server' => Yii::app()->params['main_service_url']));
		// DevUtils::cl($input, 'input', __METHOD__, __LINE__);	
		$response = $rest->put('/message/sms', $input);

		$response = json_decode($response, true);
		// DevUtils::cl($response, 'response', __METHOD__, __LINE__);exit;	
		empty($response) 
			? $response['code'] = HtmlResponse::INTERNAL_SERVER_ERROR 
			: null;

		return $response;
	}

}