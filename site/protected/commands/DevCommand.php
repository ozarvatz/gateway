<?php

class DevCommand extends CConsoleCommand 
{

	public function actionNexmo()
	{
		// curl -X POST  https://rest.nexmo.com/sms/json \
		// -d api_key=f3d4a3a0 \
		// -d api_secret=5ed2853a5cb17743 \
		// -d to=972503220778 \
		// -d from="NEXMO" \
		// -d text="Hello from Nexmo"
		
		$serverBaseUrl = 'https://rest.nexmo.com/';
		$rest = new RESTClient();
		// $rest->option('USERPWD', 'admin:oJq4sQ7D');
		// $rest->option('SSL_VERIFYPEER', false);
		$rest->initialize(array('server' => $serverBaseUrl));

		$params = 
		[
			'api_key' => 'f3d4a3a0',
			'api_secret' => '5ed2853a5cb17743',
			'to' => '972503220778',
			'from' => 'Arliku',
			'text' => 'בדיקה בדיקה',
			'type' => 'unicode',
		];
		$result = json_decode($rest->post('sms/json', $params), true);
		// $result = $rest->get($url);
		DevUtils::cl($result, '$result', __METHOD__, __LINE__);

		//result 
		/*array
		(
		    'message-count' => '1'
		    'messages' => array
		    (
		        0 => array
		        (
		            'to' => '972503220778'
		            'message-id' => '0C00000011CB622D'
		            'status' => '0'
		            'remaining-balance' => '1.97920000'
		            'message-price' => '0.01040000'
		            'network' => '42503'
		        )
		    )
		)*/
	}

	public function actionNexmo2()
	{
		$smsObj = new Nexmo();
		$params = 
		[
			'to' => '972503220778',
			'from' => 'Arliku',
			'text' => '2בדיקה בדיקה',
		];

		$respons = $smsObj->send($params);

		DevUtils::cl($respons, '$respons', __METHOD__, __LINE__);
	}


}