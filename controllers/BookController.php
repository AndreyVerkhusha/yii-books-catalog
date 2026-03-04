<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookSearch;
use app\services\BookSubscriptionNotificationService;
use app\services\SmsPilotService;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


class BookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @return string|Response
     * @throws Exception|Throwable
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($this->request->isPost && $this->saveBook($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException|Exception|Throwable
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $model->author_ids = array_column($model->authors, 'id');

        if ($this->request->isPost && $this->saveBook($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id): Book
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @throws Throwable
     * @throws Exception
     */
    private function saveBook(Book $model): bool
    {
        $isNewBook = $model->isNewRecord;
        $transaction = Book::getDb()->beginTransaction();

        try {
            if (!$model->load($this->request->post()) || !$model->save()) {
                $transaction->rollBack();

                return false;
            }

            $model->syncAuthors();
            $transaction->commit();

            if ($isNewBook) {
                $notificationService = new BookSubscriptionNotificationService(
                    new SmsPilotService()
                );

                try {
                    $notificationService->notifyAboutNewBook($model);
                } catch (Throwable $exception) {
                    Yii::warning($exception->getMessage(), 'sms');
                    Yii::$app->session->setFlash('warning', 'Книга сохранена, но SMS не отправлена: ' . $exception->getMessage());
                }
            }

            return true;
        } catch (Throwable $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }
}
