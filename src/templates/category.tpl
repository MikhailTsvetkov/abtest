{extends file='layout.tpl'}
{block name='content'}
    <nav class="breadcrumbs" aria-label="Хлебные крошки"><a href="/">Главная</a><span aria-hidden="true">/</span><span aria-current="page">{$category->name}</span></nav>
    <header class="page-intro page-intro-compact">
        <p class="eyebrow">Категория</p>
        <h1>{$category->name}</h1>
        {if $category->description}<p class="lead">{$category->description}</p>{/if}
    </header>

    <form class="filters" method="get" action="/categories/{$category->id}">
        <label for="sort">Сортировать
            <select id="sort" name="sort">
                <option value="date"{if $sort == 'date'} selected{/if}>По дате публикации</option>
                <option value="views"{if $sort == 'views'} selected{/if}>По просмотрам</option>
            </select>
        </label>
        <label for="direction">Порядок
            <select id="direction" name="direction">
                <option value="desc"{if $direction == 'desc'} selected{/if}>По убыванию</option>
                <option value="asc"{if $direction == 'asc'} selected{/if}>По возрастанию</option>
            </select>
        </label>
        <label for="per-page">На странице
            <input id="per-page" type="number" name="per_page" min="1" max="100" value="{$pagination.per_page}" required>
        </label>
        <button class="button" type="submit">Применить</button>
    </form>

    <section aria-labelledby="articles-heading">
        <div class="list-heading"><h2 id="articles-heading">Статьи</h2><span class="muted">Всего: {$pagination.total}</span></div>
        {if $articles}
            <div class="card-grid">
                {foreach $articles as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>
        {else}
            <div class="empty-state"><h3>Пока нет статей</h3><p>В этой категории ещё ничего не опубликовано.</p><a href="/">Посмотреть другие категории →</a></div>
        {/if}
    </section>

    {if $pagination.total_pages > 1}
        <nav class="pagination" aria-label="Страницы статей">
            {if $pagination.previous_url}<a class="button button-outline" rel="prev" href="{$pagination.previous_url}">← Назад</a>{else}<span class="button button-disabled" aria-disabled="true">← Назад</span>{/if}
            <span class="page-number">Страница {$pagination.page} из {$pagination.total_pages}</span>
            {if $pagination.next_url}<a class="button button-outline" rel="next" href="{$pagination.next_url}">Далее →</a>{else}<span class="button button-disabled" aria-disabled="true">Далее →</span>{/if}
        </nav>
    {/if}
{/block}
