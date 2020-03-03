<?php

namespace common\modules\rest\controllers;

use common\modules\rest\models\User;

use Yii;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `apples` module
 */
class UsersController extends ActiveController
{

    public $modelClass = User::class;

    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            Cors::class,
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'update' || $action === 'delete') {
            if ($model->id !== Yii::$app->user->id)
                throw new ForbiddenHttpException(sprintf('You can only %s self', $action));
        }
    }

}
