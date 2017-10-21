<?php
/**
 * @var $provider \yii\data\ActiveDataProvider
 * @var $model \backend\models\UserList
 */

use yii\bootstrap\Html;

echo \yii\grid\GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $model,
    'columns' => [
        'id',
        'username',
        'serial_number',
        'personal_account',
        [
            'label' => '',
            'content' => function($data){
                return Html::a('Просмотр', Yii::$app->urlManager->createUrl(['site/user', 'id' => $data['id']]));
            }
        ]
    ]
]);