<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task_list".
 *
 * @property integer $id
 * @property string $name
 * @property string $create_date
 * @property integer $done
 * @property integer $token_id
 */
class Task extends \yii\db\ActiveRecord
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_date'], 'safe'],
            [['done', 'token_id'], 'integer'],
            [['name'], 'string', 'max' => 400],
        ];
    }

    /**
     * @inheritdoc
     */
	 public function beforeSave($insert)
	 {
		 if(parent::beforeSave($insert))
		 {
			 if($insert) {
				$session = Yii::$app->session;
				 $token = Token::findOne(['token'=>$session->get('token')]);
				 if(is_null($token)) return false;
				 
			 $this->create_date = date("Y-m-d H:i");
			 $this->token_id = $token->id;
			 $this->done = 0;
			 }
			 return true;
		 } else {
			 return false;
		 }
	 }
    public function attributeLabels()
    {
        return [
            'id' => 'Номер',
            'name' => 'Название задания',
            'create_date' => 'Дата создания',
            'done' => 'Статус',
            'token_id' => 'Token ID',
        ];
    }

    /**
     * @inheritdoc
     * @return TaskListQuery the active query used by this AR class.
     */
    public static function find()
    {
			$session = Yii::$app->session;
		$token = Token::findOne(['token'=>$session->get('token')]);
        return(new TaskListQuery(get_called_class()))->where(['token_id'=>$token->id]);
    }
}
