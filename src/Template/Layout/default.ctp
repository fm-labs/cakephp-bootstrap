<?php //$this->loadHelper('Bootstrap.Bootstrap'); ?>
<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::locale(); ?>">
<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?> (Bootstrap)</title>
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="robots" content="noindex,nofollow">
    <?= $this->Html->meta('icon') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('headjs') ?>
</head>
<body>
<header>
</header>
<div id="page">
    <div id="flash" class="container">
        <?= $this->Flash->render(); ?>
        <?= $this->Flash->render('auth'); ?>
        <?= $this->fetch('flash') ?>
    </div>
    <main id="content" class="container">
        <?= $this->fetch('content') ?>
    </main>
    <footer class="container">
    </footer>
</div>
<?= $this->fetch('script') ?>
</body>
</html>
