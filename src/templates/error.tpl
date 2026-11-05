{extends file='layout.tpl'}
{block name='content'}
    <section class="error-page">
        <p class="eyebrow">Ошибка {$status}</p>
        <h1>{$error}</h1>
        <p class="lead">Вернитесь на главную, чтобы продолжить чтение.</p>
        <a class="button" href="/">На главную</a>
    </section>
{/block}
