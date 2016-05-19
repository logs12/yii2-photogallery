<?php
namespace app\validators;

use yii\validators\Validator;

class PhoneValidator extends Validator
{
	public $message;

	public function init(){
		$this->message = 'Номер телефона не соответсвует маске +7 (xxx) xxx-xx-xx';
	}

	public function validateAttribute($model,$attribute)
	{
		$value = $model->$attribute;
		if (!preg_match('/\+?(\d{1})\s+\((\d{3})\)\s+(\d{3})-(\d{2})-(\d{2})/s',$value))
			$this->addError($model, $attribute, $this->message);
	}
}