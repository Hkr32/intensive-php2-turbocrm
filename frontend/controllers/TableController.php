<?php


namespace frontend\controllers;

use Yii;
use frontend\models\Contact;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\widgets\ActiveForm;

class TableController extends SecuredController
{

    /**
     * @var ActiveRecord
     */
    protected $entity;

    protected $alias;

    public function actionCreate()
    {
        $model = $this->entity;
        $model->setScenario('insert');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            if ($model->save()) {
                Yii::$app->getSession()->setFlash($this->alias . '_create');

                return $this->redirect('/' . $this->alias);
            }
        }
    }

    public function actionIndex()
    {
        $model = $this->entity;
        $dataProvider = $model->search(Yii::$app->request->get());

        $dataProvider = Yii::configure($dataProvider, [
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $dataProvider->getModels();

        return $this->render('index', ['dataProvider' => $dataProvider, 'model' => new Contact]);
    }
}
