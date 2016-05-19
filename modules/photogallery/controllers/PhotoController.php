<?php

namespace app\modules\photogallery\controllers;

use Yii;
use app\modules\photogallery\models\Photo;
use app\modules\photogallery\models\PhotoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\BaseFileHelper;
use yii\web\UploadedFile;
use Imagine\Image\Point;
use yii\imagine\Image;
use Imagine\Image\Box;

/**
 * PhotoController implements the CRUD actions for Photo model.
 */
class PhotoController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Photo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PhotoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewPhotos($parent_id)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
                'query' => Photo::find()->where(['parent_id'=>$parent_id]),
        ]);
        return $this->render('view-photos', [
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Photo model.
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
     * Creates a new Photo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Photo();
        if ($model->load(Yii::$app->request->post())) {

            $pathSave = "/uploads/album".uniqid();
            $path = Yii::getAlias("@app/web" . $pathSave);
            BaseFileHelper::createDirectory($path);
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {
                $name = $model->file->name;
                $thumbnailName = "thumbnail_" . $name;
                $model->file->saveAs($path . DIRECTORY_SEPARATOR . $name);

                $imageUri = $path . DIRECTORY_SEPARATOR . $name;
                $imageUriThumbnail = $path . DIRECTORY_SEPARATOR . $thumbnailName;

                Image::getImagine()->open($imageUri)
                ->thumbnail(new Box(250, 250))->save($imageUriThumbnail, ['quality' => 90]);

                $model->file = $pathSave. DIRECTORY_SEPARATOR .$name;
                $model->thumbnail = $pathSave. DIRECTORY_SEPARATOR .$thumbnailName;
                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    return $this->render('create', [
                            'model' => $model,
                    ]);
                }
            }
        }
        else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Photo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Photo model.
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
     * Finds the Photo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Photo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Photo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
