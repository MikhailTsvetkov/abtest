<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$page_title} — Блог</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<a class="skip-link" href="#main">Перейти к содержимому</a>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="/" aria-label="Блог — главная страница">Блог<span class="brand-dot">.</span></a>
        <nav aria-label="Основная навигация"><a href="/">Главная</a></nav>
    </div>
</header>
<main id="main" class="container">
    {block name="content"}{/block}
</main>
<footer class="site-footer">
    <div class="container footer-inner"><span>Блог · Истории и идеи на каждый день</span><a href="#main">Наверх ↑</a></div>
</footer>
</body>
</html>
