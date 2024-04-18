<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;

class AuthController extends Controller
{   
    public $enableCsrfValidation = false;

    /**
     * Realiza a autenticação de um usuário e retorna um JWT em caso de sucesso.
     * @return \yii\web\Response
     */
    public function actionLogin()
    {
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            $user = $model->getUser();
            $jwt = Yii::$app->jwt;
            $token = $jwt->createToken(['id' => $user->id]);

            return $this->asJson([
                'success' => true,
                'token' => $token,
            ]);
        }

        Yii::$app->response->statusCode = 401;
        return $this->asJson([
            'success' => false,
            'message' => 'Authentication failed',
        ]);
    }

}