<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AngularAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'angular/angular.min.js',
        'angular-bootstrap/ui-bootstrap.min.js',
        'angular-bootstrap/ui-bootstrap-tpls.min.js'
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];

}