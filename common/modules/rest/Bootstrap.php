<?php

namespace common\modules\rest;

use yii\rest\UrlRule;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getRequest()->parsers['application/json'] = 'yii\web\JsonParser';
        $app->getUrlManager()->addRules(
            [
                [
                    'class' => UrlRule::class,
                    'controller' => ['users' => 'rest/users'],
                    'prefix' => 'api',
                ]
            ]
        );
    }

}