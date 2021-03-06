<?php

class MessagesController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the messages page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}


	/**
	 * Displays the messages page
	 */
	public function actionSendByCedulas()
	{

		if(Yii::app()->user->isGuest)
		 if(!isset($_REQUEST[Yii::app()->params['secret_word']]))
			$this->redirect(Yii::app()->user->loginUrl);

		$model=new MessagesForm('send');

		$model->attributes = array(
			'suffix' => "\nenviado con textveloper",
			'preffix' => '',
		);
		if(isset($_REQUEST['cedulas'])){
			$temp_cedulas = $_REQUEST['cedulas'];
			$criteria = new CDbCriteria;
			$cedulas = array();
			foreach ($temp_cedulas as $ced) {
				    if(trim($ced)){
				    	$raws=preg_split('/[\s,]+/',$ced,-1,PREG_SPLIT_NO_EMPTY);
				    	if(count($raws) > 0){
				    		$cedulas = array_merge($cedulas,$raws);
				    	}
				    }
			}
			$criteria->addInCondition('cedula',$cedulas);
			$model->models = Participantes::model()->findAll($criteria);
			unset($_REQUEST['cedulas']);
		}
		if(isset($_REQUEST['body']) && !isset($_POST['MessagesForm'])){
			$_POST['MessagesForm']= array(
				'body' => $_REQUEST['body']
			);
		}
		if(isset($_POST['MessagesForm']))
		{
			$model->control = true;
		    $model->attributes=$_POST['MessagesForm'];
			if($model->validate() && $model->send() )
			{
				if(!$model->tasking()){
					$email = Yii::app()->params['adminEmail'];
					$name='=?UTF-8?B?'.base64_encode('App Joincic Messages').'?=';
					$subject='=?UTF-8?B?'.base64_encode('Envio de mensajes').'?=';
					$headers="From: $name <{$email}>\r\n".
						"Reply-To: {$email}\r\n".
						"MIME-Version: 1.0\r\n".
						"Content-Type: text/plain; charset=UTF-8";

					mail($email,$subject,$model->log,$headers);
					Yii::app()->user->setFlash('messages','Los Mensajes han sido enviados! Revise el correo '.Yii::app()->params['adminEmail'].' Para mayor informacion');

					$model->attributes = array(
						'body' => '',
					);
					$model->minuteCount = 0;
					$model->control = false;
				}
			}
		}
		$this->render('formCedula',array('model'=>$model));
	}

	/**
	 * Displays the messages page
	 */
	public function actionSend()
	{
		if(Yii::app()->user->isGuest && !isset($_REQUEST['no_validate']))
			$this->redirect(Yii::app()->user->loginUrl);

		$model=new MessagesForm('send');
		$model->attributes = array(
			'suffix' => "\nEnviado con textveloper",
			'preffix' => '',
			'models' => Participantes::model()->findAll()
		);
		if(isset($_POST['MessagesForm']))
		{
			$model->control = true;
		    $model->attributes=$_POST['MessagesForm'];

		    if(trim($model->rawNumbers)){
		    	$raws=preg_split('/[\s,]+/',$model->rawNumbers,-1,PREG_SPLIT_NO_EMPTY);
		    	if(count($raws) > 0){
			    	$model->models=$raws;
			    	$model->attrNumber = false;
		    	}
		    }

			if($model->validate() && $model->send() )
			{
				if(!$model->tasking()){

					$email = Yii::app()->params['adminEmail'];
					$name='=?UTF-8?B?'.base64_encode('App Joincic Messages').'?=';
					$subject='=?UTF-8?B?'.base64_encode('Envio de mensajes').'?=';
					$headers="From: $name <{$email}>\r\n".
						"Reply-To: {$email}\r\n".
						"MIME-Version: 1.0\r\n".
						"Content-Type: text/plain; charset=UTF-8";

					mail($email,$subject,$model->log,$headers);
					Yii::app()->user->setFlash('messages','Los Mensajes han sido enviados! Revise el correo '.Yii::app()->params['adminEmail'].' Para mayor informacion');
					$this->refresh();
				}
			}
		}
		$this->render('form',array('model'=>$model));
	}

}
