<?php

namespace app\modules\photogallery;

/**
 * photogallery module definition class
 */
class Module extends \yii\base\Module
{
    public $defaultRoute = 'photogallery';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\photogallery\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setLayoutPath('photogallery/views/layouts');
    }
}
