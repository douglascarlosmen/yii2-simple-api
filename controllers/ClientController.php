<?php

namespace app\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use app\models\User;
use app\models\Client;

class ClientController extends ActiveController
{
    public $modelClass = 'app\models\Client';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['authenticator']);

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);

        $actions['index']['prepareDataProvider'] = function ($action) {
            $modelClass = $this->modelClass;

            return new ActiveDataProvider([
                'query' => $modelClass::find(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        };

        return $actions;
    }

    public function actionCreate()
    {
        $model = new Client();
        $model->load(Yii::$app->request->post(), '');
        $model->photo = UploadedFile::getInstanceByName('photo');

        if ($model->photo && $model->validate(['photo'])) {
            $uploadPath = Yii::getAlias('@webroot/uploads');
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }

            $filePath = $uploadPath . '/' . $model->photo->baseName . '.' . $model->photo->extension;
            if ($model->photo->saveAs($filePath)) {
                $model->photo = $filePath;
            } else {
                return [
                    'error' => 'Não foi possível salvar o arquivo.'
                ];
            }
        }

        if ($model->save()) {
            return $model;
        } else {
            return $model->getErrors();
        }
    }
}
