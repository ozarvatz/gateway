<?php
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
class RabbitMqCommand extends CConsoleCommand 
{
	const QUEUE_TEST_RIGHT_NOW = 'testQueueRightNow';

	public function actionGermans()
	{
		Yii::app()->rabbitMQ->printMe();
		echo "the germans got there first";
	}

    public function actionConsumer() 
    {
    	Yii::app()->rabbitMQ->initiateConsumer('testQueueRightNow', 'MqCallback::doTestConsumeCallback');
    }

    public function actionProducer() 
    {
    	$message = 
    	[
    		'message' => 'this is a realtime message',
    		'time' => time(),
    	];

  		Yii::app()->rabbitMQ->produce('testQueueRightNow', $message);
    }

    public function actionDelayedProducer() 
    {
    	$message = 
    	[
    		'message' => 'this is a delayed message',
    		'time' => time(),
    	];
  		Yii::app()->rabbitMQ->delayedProduce('testQueueRightNow', $message, 5);
    }

    public function actionSchedulerConsumer()
    {
        if(!$this->amIRunning(__FUNCTION__))
        {
    	   Yii::app()->rabbitMQ->initiateConsumer(RabbitMqHelper::RIGHT_NOW_SCHEDULER_QUEUE, 'MqCallback::doConsumeCallback');
        }
    }

    public function actionBulkUpdateConsumer()
    {
        if(!$this->amIRunning(__FUNCTION__))
        {
           Yii::app()->rabbitMQ->initiateConsumer(RabbitMqHelper::RIGHT_NOW_BULK_UPDATE_QUEUE, 'MqCallback::doConsumeCallback');
        }
    }

    public function actionBulkUpdateConsumer1()
    {
        if(!$this->amIRunning(__FUNCTION__))
        {
           Yii::app()->rabbitMQ->initiateConsumer(RabbitMqHelper::RIGHT_NOW_BULK_UPDATE_QUEUE, 'MqCallback::doConsumeCallback');
        }
    }


    public function actionLogConsumer()
    {
        if(!$this->amIRunning(__FUNCTION__))
        {
            Yii::app()->rabbitMQ->initiateConsumer(RabbitMqHelper::RIGHT_NOW_ENGINE_LOG_QUEUE, 'MqCallback::logConsumeCallback');
        }
    }
    
    public function actionTest()
    {
    	// echo '<pre>';
    	// print_r(get_include_path() . PATH_SEPARATOR . dirname(__FILE__)  . '/../extensions/php-amqplib/PhpAmqpLib');
    	// exit;
    	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__)  . '/../extensions/php-amqplib/PhpAmqpLib');
    	
    	require_once 'Channel/AbstractChannel.php';
    	require_once 'Connection/AbstractConnection.php';
    	require_once 'Connection/AMQPStreamConnection.php';
		require_once 'Connection/AMQPConnection.php';
		require_once 'Wire/IO/AbstractIO.php';
		require_once 'Wire/IO/StreamIO.php';
		require_once 'Wire/AbstractClient.php';
		require_once 'Wire/AMQPWriter.php';
		require_once 'Wire/AMQPAbstractCollection.php';
		require_once 'Wire/AMQPReader.php';
		require_once 'Helper/MiscHelper.php'; 
		require_once 'Wire/Constants091.php';
		require_once 'Helper/Protocol/Protocol091.php'; 
		require_once 'Helper/Protocol/Wait091.php'; 
		require_once 'Helper/Protocol/MethodMap091.php'; 
		require_once 'Channel/AMQPChannel.php';
		require_once 'Wire/GenericContent.php';
		require_once 'Message/AMQPMessage.php';

		$connection1 = new AMQPConnection('localhost', 5672, 'guest', 'guest');

		$channel1 = $connection1->channel();

		$channel1->queue_declare('hello', false, false, false, false);

		$msg = new AMQPMessage('Hello World!');
		$channel1->basic_publish($msg, '', 'hello');

		echo " [x] Sent 'Hello World!'\n";


    	$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
		$channel = $connection->channel();

		$channel->queue_declare('hello', false, false, false, false);

		echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

		$callback = function($msg) {
		  echo " [x] Received ", $msg->body, "\n";
		};

		$channel->basic_consume('hello', '', false, true, false, false, $callback);

		while(count($channel->callbacks)) {
		    $channel->wait();
		}
    }

    public function amIRunning($processName)
    {
        $osName = isset(Yii::app()->params['os_name']) ? Yii::app()->params['os_name'] : 'Linux';
        if(strpos($osName, 'Windows') !== false)
        {
            return false;
        }
        $processName = preg_replace('~action~', '', $processName);
        
        $curDirName = $this->getCurrentProjectDir();
        // DevUtils::c($curDirName, '$curDirName');exit;
        $watchdog_counter = `ps -ef | grep -i {$curDirName} | grep -i {$processName} | grep -v grep | wc -l`;
        if(Yii::app()->params['es_and_mq_index_prefix'] == 'production')
        {
            $watchdog_counter2 = `ps -ef | grep -i "\.{$curDirName}" | grep -i {$processName} | grep -v grep | wc -l`;
            $watchdog_counter -= $watchdog_counter2; // calc production the folder do not have stage prefix 
        }
        // DevUtils::c($watchdog_counter, '$watchdog_counter');exit;
        if($watchdog_counter > 2)
        {

            // DevUtils::c(get_class($this) . '::' . __FUNCTION__  . " Warning process allready running : {$processName}"); exit;
            Yii::log(get_class($this) . '::' . __FUNCTION__  . " Warning process allready running : {$processName}", 'warning', 'system');
            return true;
        }

        return false;
    }

    private function getCurrentProjectDir()
    {
        $url = `pwd`;
        $arr = explode('/', $url);
        foreach ($arr as $part) 
        {
            if(1 == preg_match('~\.~', $part))
            {
                return $part;
            }
        }

        return 'mcc';
    }
    
}