<?php
namespace backend\controllers;

use backend\models\UserList;
use common\models\CommunicationStat;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'user-list', 'user'],
                        'allow' => true,
                        'roles' => ['Admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(Yii::$app->urlManager->createUrl('site/user-list'));
    }

    /**
     * Страница списка пользователей
     * @return string
     */
    public function actionUserList(){
        $model = new UserList();
        $model->load(Yii::$app->request->get());
        $provider = new ActiveDataProvider([
           'query' =>  $model->query(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('user-list', [
            'provider' => $provider,
            'model' => $model
        ]);
    }

    /**
     * Страница пользователя
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUser(){

        $user = User::findIdentity(Yii::$app->request->get('id'));
        if(!$user){
            throw new NotFoundHttpException('Пользователь не найден');
        }
        $data = CommunicationStat::statistic($user->serial_number);
        $val_tariff = CommunicationStat::value_tariff($user->serial_number);
        $recentTestimonies = CommunicationStat::recentTestimonies($user->serial_number);
        $recentTestimonies1 = CommunicationStat::recentTestimonies1($user->serial_number);
        return $this->render('@common/views/users/user', [
            'user' => $user,
            'data' => $data,
            'val_tariff' => $val_tariff,
            'recentTestimonies1' =>$recentTestimonies1,
            'recentTestimonies' => $recentTestimonies
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
