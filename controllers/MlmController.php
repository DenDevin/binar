<?php

namespace app\controllers;

use Yii;
use app\models\MlmUser;
use app\models\search\SearchMlmUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MlmController implements the CRUD actions for MlmUser model.
 */
class MlmController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MlmUser models.
     * @return mixed
     */
    public function actionIndex()
    {

        $binar = new MlmUser();
        $roots = $binar->getRoots();


        if(Yii::$app->request->get('parent') &&
            Yii::$app->request->get('position'))
        {



           $parent_id = Yii::$app->request->get('parent');
            $position = Yii::$app->request->get('position');
            if($binar->checkPosition($parent_id, $position) == 'leftFilled'){
                Yii::$app->session->setFlash('warning', 'Ячейка слева заполнена! Вы можете заполнить правую ячейку у данного элемента.');

            }
            if($binar->checkPosition($parent_id, $position) == 'rightFilled'){
                Yii::$app->session->setFlash('warning', 'Ячейка справа заполнена! Вы можете заполнить левую ячейку у данного элемента.');

            }

            if($binar->checkPosition($parent_id, $position) == 'nodesFilled'){
                Yii::$app->session->setFlash('warning', 'Вы не можете добавить элемент, ячейки заполнены!');

            }

            if($binar->checkPosition($parent_id, $position) == 'invalid'){
                Yii::$app->session->setFlash('warning', 'Недопустимое значение позиции!');

            }



            if($binar->checkPosition($parent_id, $position) == 'pass'){


                $binar = new MlmUser();
                $binar->parent_id = $parent_id;
                $binar->position = $position;
                $binar->level = 1;
                $binar->save();
                  Yii::$app->session->setFlash('success', 'Узел успешно сохранен!');





            }


        }





        return $this->render('index', [
            'roots' => $roots,
            'binar' => $binar,

        ]);
    }


    public function actionGet($id = null){
        $binar = new MlmUser();

            $roots = $binar->getRoots();
            $parent = Yii::$app->request->get('parent');


        return $this->render('get', [
            'roots' => $roots,
            'binar' => $binar,
            'parent' => $parent,

        ]);


    }



    public function actionFill($parent)
    {


        $binar = new MlmUser();
        $roots = $binar->getRoots();


        if(Yii::$app->request->get('parent'))
        {
            $parent_id = Yii::$app->request->get('parent');

                if($binar->checkFilling($parent_id) == 'pass'){

                    $binar = new MlmUser();
                    $binar->parent_id = $parent_id;
                    $binar->position = null;
                    $binar->save(false);




                       for($i = 0; $i < 4; $i++){
                           if($binar->save()){
                             $current = $binar->id;

                           $binar = new MlmUser();
                           $binar->parent_id = $current;
                           $binar->position = null;
                           $binar->save(false);


                       }


                   }




                }






        }










        return $this->render('index', [
            'roots' => $roots,
            'binar' => $binar,

        ]);
    }





    /**
     * Displays a single MlmUser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MlmUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MlmUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MlmUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MlmUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MlmUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MlmUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MlmUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
