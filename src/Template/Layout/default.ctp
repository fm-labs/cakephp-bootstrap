<!DOCTYPE html>
<html lang="<?= Cake\I18n\I18n::getLocale(); ?>">
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
    <div id="page">
        <header id="header" class="container">
            <?= $this->fetch('header') ?>
        </header>
        <div id="flash" class="container">
            <?= $this->Flash->render(); ?>
            <?= $this->Flash->render('auth'); ?>
            <?= $this->fetch('flash') ?>
        </div>
        <main id="content" class="container">
            <?= $this->fetch('content') ?>
        </main>
        <footer id="footer" class="container">
            <?= $this->fetch('footer') ?>
        </footer>
    </div>
    <?= $this->fetch('script') ?>
</body>
</html>
