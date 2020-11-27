<?php
namespace frontend\controllers;
use frontend\models\LoginForm;
use frontend\models\User;
use yii\web\Controller;

class UserController extends Controller
{
    public $layout = 'anon';

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionLogin()
    {
        $loginForm = new LoginForm();

        if (\Yii::$app->request->getIsPost()) {
            $loginForm->load(\Yii::$app->request->post());

            if ($loginForm->validate()) {
                $user = $loginForm->getUser();

                \Yii::$app->user->login($user);

                return $this->goHome();
            }
        }

        return $this->render('login', ['model' => $loginForm]);
    }
}
