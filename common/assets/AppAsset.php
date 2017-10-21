<?php


namespace common\assets;
use yii\web\AssetBundle;
/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@common/assets';

    public $css = [
        'css/common.css'
    ];
    public $js = [

    ];
    public $depends = [
    ];
}