<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\Product;
use yii\web\UploadedFile;
use yii\filters\auth\HttpBearerAuth;

class ProductController extends ActiveController
{
    public $modelClass = 'app\models\Product';

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
        return $actions;
    }

    public function actionCreate()
    {
        $model = new Product();
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
