<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tokens".
 *
 * @property integer $id
 * @property string $token
 * @property string $create_date
 */
class Token extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tokens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_date'], 'safe'],
            [['token'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'create_date' => 'Create Date',
        ];
    }

    /**
     * @inheritdoc
     * @return TaskListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaskListQuery(get_called_class());
    }
}
