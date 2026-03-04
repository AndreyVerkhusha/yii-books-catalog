<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Каталог книг';
?>
<div class="site-index">
    <div class="jumbotron text-center bg-transparent">
        <h1>Каталог книг</h1>

        <p>
            <?= Html::a('Перейти к книгам', ['/book/index'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Перейти к авторам', ['/author/index'], ['class' => 'btn btn-primary']) ?>
        </p>
    </div>
</div>