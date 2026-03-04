<?php

namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;

class ReportController extends Controller
{
    public function actionTopAuthors(): string
    {
        $year = Yii::$app->request->get('year', date('Y'));

        $rows = (new Query())
            ->select([
                'author.id',
                'author.full_name',
                'book_count' => 'COUNT(book.id)',
            ])
            ->from('author')
            ->innerJoin('book_author', 'book_author.author_id = author.id')
            ->innerJoin('book', 'book.id = book_author.book_id')
            ->where(['book.publish_year' => $year])
            ->groupBy(['author.id', 'author.full_name'])
            ->orderBy(['book_count' => SORT_DESC, 'author.full_name' => SORT_ASC])
            ->limit(10)
            ->all();

        return $this->render('top-authors', [
            'year' => $year,
            'rows' => $rows,
        ]);
    }
}