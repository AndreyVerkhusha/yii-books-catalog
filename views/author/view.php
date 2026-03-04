<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */
/** @var app\models\Subscription $subscriptionModel */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>

    <?php if (Yii::$app->user->isGuest): ?>
        <div class="card mb-3">
            <div class="card-header">Подписка на новые книги автора</div>
            <div class="card-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['subscribe', 'id' => $model->id],
                    'method' => 'post',
                ]); ?>

                <?= $form->field($subscriptionModel, 'phone')
                    ->textInput(['maxlength' => true, 'placeholder' => '+79991234567']) ?>

                <div class="form-group">
                    <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
        ],
    ]) ?>

</div>