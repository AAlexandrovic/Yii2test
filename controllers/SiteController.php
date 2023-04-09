<?php

namespace app\controllers;

use app\models\Category;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\controllers\CustomController;

class SiteController extends CustomController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public $Password;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        return $this->render('index'/*, compact('model')*/);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        $this->setMeta('авторизация');
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
     * @throws Exception
     */
    public function actionRegistration(): Response|string
    {
        $this->setMeta('Регистрация');

        $registration = new User();

        $registration->scenario = 'registration';

        if ($registration->load(Yii::$app->request->post())) {
            $this->Password = $registration->password;
            $registration->password = Yii::$app->security->generatePasswordHash($registration->password);

            if ($registration->save()) {
                Yii::$app->session->setFlash('success', 'Регистрация прошла успешно');
                return $this->goHome();
            } else {
                $registration->password = $this->Password;
                return $this->render('reglog', compact('registration'));
            }
        }
        return $this->render('reglog', compact('registration'));
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionUsers()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = User::find()->all();
        $delete = new User();
        $user = User::findOne([
            'id' => $_SESSION['__id'],
        ]);
        if ($delete->load(Yii::$app->request->post())) {
            $id = Yii::$app->request->post('User');
            $customer = User::findOne($id['id']);
            $customer->delete();
            $this->refresh();
        }
        return $this->render(
            'users',
            compact('model', 'delete', 'user')
        );
    }
}
