<?php
namespace frontend\controllers;
use frontend\models\Company;
use frontend\models\Contact;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ContactsController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['update'],
            'matchCallback' => function ($rule, $action) {
                $id = Yii::$app->request->get('id');
                $contact = Contact::findOne($id);

                return $contact->owner_id != Yii::$app->user->getId();
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    public function actionJson() {
        $contacts = Contact::find()->asArray()->all();

        $response = Yii::$app->response;
        $response->data = $contacts;
        $response->format = Response::FORMAT_JSON;

        return $response;
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Contact::find(),
            'pagination' => ['pageSize' => 5],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id)
    {
        $contact = Contact::findOne($id);

        if (!$contact) {
            throw new NotFoundHttpException("Контакт с ID #$id не найден");
        }

        return $this->render('update', ['contact' => $contact]);
    }

    public function actionShow($id)
    {
        $contact = Contact::findOne($id);

        if (!$contact) {
            throw new NotFoundHttpException("Контакт с ID $id не найден");
        }

        return $this->render('view', ['contact' => $contact]);
    }

    public function actionFilter($status)
    {
        $contacts = Contact::findAll(['status' => $status]);

        return $this->render('index', ['contacts' => $contacts]);
    }
}
