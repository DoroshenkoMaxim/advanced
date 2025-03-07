<?php if (!Yii::$app->user->isGuest): $this->title = 'Welcome to advanced!'; ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?= \hail812\adminlte\widgets\Alert::widget([
                'type' => 'success',
                'body' => '<h3>Login Success!</h3>',
            ]) ?>
        </div>
    </div>
</div>
<?php else: $this->title = 'Login to advanced!'; ?>
<?php endif; ?>
