<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use backend\models\SingUpForm;
use common\models\User;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','verificacion'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * {@inheritdoc}
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionVerificacion()
    {
        $this->layout='blank';
        $model = new SingUpForm();
       // var_dump($_POST["SingUpForm"] ["password"]);die;
        if($_GET['token'] && !isset($_POST["SingUpForm"]['password']))
        {
            $token = $_GET['token'];
            
            $data = User::findOne(['verification_token' => $token, 'status'=>9]);
            
            
            if($data)
            {
                return $this->render('verificacion', [
                    'model' => $model,
                ]);
            }
            else
            {


                return $this->render('token_inv');
            }
        }
        else if($_POST["SingUpForm"] ["password"])
        {
            //var_dump($_POST);die;
            $token = $_GET['token'];
            $user = User::findOne(['verification_token' => $token]);
         //   var_dump($user->username);die;
            

            $user->setPassword($_POST["SingUpForm"]['password']);
            $user->generateAuthKey();
            $user->updated_at = strtotime('today');
            $user->status = 10;
            $user->verification_token = null;
            $user->save();

            return $this->redirect(['login']);
        }
        

       
    //    if($_GET == )


        return $this->render('verificacion', [
            'model' => $model,
        ]);
    }
}
