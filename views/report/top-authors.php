<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $year */
/** @var array<int, array<string, mixed>> $rows */

$this->title = 'TOP 10 authors by year';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top-authors">

    <h1><?= Html::encode($this->title) ?></h1>

    <form method="get" action="">
        <input type="hidden" name="r" value="report/top-authors">
        <div style="display: flex; gap: 12px; align-items: end; margin-bottom: 20px;">
            <div>
                <label for="year">Year</label>
                <input
                    id="year"
                    name="year"
                    type="number"
                    value="<?= Html::encode((string) $year) ?>"
                    min="1000"
                    max="2100"
                    class="form-control"
                >
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Show</button>
            </div>
        </div>
    </form>

    <?php if ($rows === []): ?>
        <p>No data for selected year.</p>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Author</th>
                <th>Books count</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $index => $row): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode((string) $row['full_name']) ?></td>
                    <td><?= Html::encode((string) $row['book_count']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>