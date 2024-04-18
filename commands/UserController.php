<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

class UserController extends Controller
{
    /**
     * Cria um novo usuário com o nome de usuário e senha fornecidos.
     * Uso: yii user/create [username] [password]
     *
     * @param string $username O nome de usuário desejado.
     * @param string $password A senha para o usuário.
     */
    public function actionCreate($username, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->authKey = Yii::$app->security->generateRandomString();
        if ($user->save()) {
            echo "Usuário '{$username}' criado com sucesso.\n";
            return Controller::EXIT_CODE_NORMAL;
        } else {
            echo "Erro ao criar usuário.\n";
            return Controller::EXIT_CODE_ERROR;
        }
    }
}
