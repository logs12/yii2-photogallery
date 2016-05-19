<?php
/**
 * Created by PhpStorm.
 * User: vasil
 * Date: 19.05.2016
 * Time: 13:01
 */

namespace app\behaviors;

use yii\base\InvalidCallException;
use yii\db\BaseActiveRecord;
use yii\behaviors\AttributeBehavior;

class TimestampPhotoBehavior extends AttributeBehavior
{
	public $createdAtAttribute = 'created_at';

	public $value;


	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if (empty($this->attributes)) {
			$this->attributes = [
					BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute],
			];
		}
	}

	/**
	 * @inheritdoc
	 *
	 * In case, when the [[value]] is `null`, the result of the PHP function [time()](http://php.net/manual/en/function.time.php)
	 * will be used as value.
	 */
	protected function getValue($event)
	{
		if ($this->value === null) {
			return time();
		}
		return parent::getValue($event);
	}

	/**
	 * Updates a timestamp attribute to the current timestamp.
	 *
	 * ```php
	 * $model->touch('lastVisit');
	 * ```
	 * @param string $attribute the name of the attribute to update.
	 * @throws InvalidCallException if owner is a new record (since version 2.0.6).
	 */
	public function touch($attribute)
	{
		/* @var $owner BaseActiveRecord */
		$owner = $this->owner;
		if ($owner->getIsNewRecord()) {
			throw new InvalidCallException('Updating the timestamp is not possible on a new record.');
		}
		$owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
	}

}