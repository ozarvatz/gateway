<?php

class SmsController extends BaseController
{
	public function actions() {
        return array(
			'receive' => 'application.controllers.SmsActions.ReceiveAction',
        );
    }
}