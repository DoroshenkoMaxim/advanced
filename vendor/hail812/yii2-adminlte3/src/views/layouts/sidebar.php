<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= \yii\helpers\Url::home() ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">advanced</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php if (!Yii::$app->user->isGuest): // Показываем меню только авторизованным 
        ?>
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?= Yii::$app->user->identity->username ?></a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <?php
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'My workspace', 'header' => true],
                        [
                            'label' => 'advanced',
                            'icon' => 'tachometer-alt',
                            'badge' => '<span class="right badge badge-info">2</span>',
                            'items' => [
                                ['label' => 'Main', 'url' => ['/site/index'], 'iconStyle' => 'far'],
                                ['label' => 'Posts', 'url' => ['/posts/index'], 'iconStyle' => 'far'],
                                ['label' => 'Visitors', 'url' => ['/posts-visitors/index'], 'iconStyle' => 'far'], // Исправленный URL
                                ['label' => 'Track', 'url' => ['/posts-track/index'], 'iconStyle' => 'far'], // Исправленный URL
                            ]
                        ],
                    ],
                ]);
                ?>
            </nav>
        <?php else: ?>
            <!-- Показываем Login только неавторизованным пользователям -->
            <nav class="mt-2">
                <?php
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
                    ],
                ]);
                ?>
            </nav>
        <?php endif; ?>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>