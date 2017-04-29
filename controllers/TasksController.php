<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\models\Token;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Security;
use yii\helpers\Url;
/**
 * TasksController implements the CRUD actions for Task model.
 */
class TasksController extends Controller
{
	private static $err_response = ['code'=>1,'text'=>'Ошибка сервера'];
	private static $success_response = ['code'=>0,'text'=>'Операция выполнена успешно'];
    /**
     * @inheritdoc
     */
	 
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
	 public function actionRestore($token)
	 {
		 $session = Yii::$app->session;
		 $model = Token::findOne(['token'=>$token]);
		 if(!is_null($model))
		 {
			 $session->set('token',$token);
			 return $this->redirect(['index']);
		 } else {
			 throw new \yii\base\ErrorException('Записи с таким токеном пользователя не найдены', 404);
		 }
	 }
    public function actionIndex()
    {
		$session = Yii::$app->session;
		$model = new Task();
		$restore_url = Url::base()."/index.php?r=tasks/restore&token=".$session->get("token");
        $dataProvider = new ActiveDataProvider([
            'query' => Task::find(),
        ]);
    
        return $this->render('index', [
		    'model'=>$model,
			'restore_url'=>$restore_url,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new Task();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $model;
        } else {
            return self::$err_response;
        }
    }
	//Здесь мы проверяем наличие созданного токена. Понимаю, что нужно было создать отдельную модель и контроллер для авторизации, но не было времени
public function beforeAction($action)
{
  
    if (!parent::beforeAction($action)) {
        return false;
    }
	

  $session = Yii::$app->session;
 if (is_null($session->get('token')) ) 
 {
	 $session->open();
	 
	 $token = (new Security())->generateRandomString(30);
	 $model = new Token();
	 $model->token = $token;
	 $session->set("token",$token);
	 return $model->save();
 } 
  

    return true; // or false to not run the action
}
    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$model = $this->findModel($id);
	

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return self::$success_response;
        } else {
           return self::$err_response;
        }
		
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
 
    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
