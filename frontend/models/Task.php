<?php

namespace frontend\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $description
 * @property int $executor_id
 * @property string $due_date
 * @property int $type_id
 * @property string $dt_add
 * @property int $deal_id
 *
 * @property Deal $deal
 * @property User $executor
 * @property TaskType $type
 */
class Task extends ActiveRecord
{
    public $search;

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if ($params) {
            $this->load($params);

            if ($this->search) {
                $query->filterWhere(['like', 'description', $this->search]);
            }
        }

        return $dataProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'executor_id', 'type_id', 'deal_id'], 'required', 'on' => 'insert'],
            [['description', 'executor_id', 'type_id', 'deal_id', 'search'], 'safe'],
            [['description'], 'string'],
            [['executor_id', 'type_id', 'deal_id'], 'integer'],
            [['due_date', 'dt_add'], 'safe'],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Deal::className(), 'targetAttribute' => ['deal_id' => 'id']],
            [['executor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['executor_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Описание',
            'executor_id' => 'Исполнитель',
            'due_date' => 'Срок исполнения',
            'type_id' => 'Тип',
            'dt_add' => 'Дата создания',
            'deal_id' => 'Сделка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeal()
    {
        return $this->hasOne(Deal::className(), ['id' => 'deal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TaskType::className(), ['id' => 'type_id']);
    }

    public static function getExpiredCount()
    {
        return self::find()->where(['<', 'due_date', new Expression('NOW()')])->count();
    }
}
