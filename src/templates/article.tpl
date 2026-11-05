{extends file='layout.tpl'}
{block name='content'}
    <nav class="breadcrumbs" aria-label="Хлебные крошки"><a href="/">Главная</a><span aria-hidden="true">/</span><span aria-current="page">Статья</span></nav>
    <article class="article-page">
        <header class="article-header">
            {if $categories}
                <div class="tags" aria-label="Категории статьи">
                    {foreach $categories as $category}<a class="tag" href="/categories/{$category->id}">{$category->name}</a>{/foreach}
                </div>
            {/if}
            <h1>{$article->title}</h1>
            <div class="meta">
                <time datetime="{$article->createdTs|date_iso}">{$article->createdTs|date_utc}</time>
                <span>Просмотры: {$article->viewsCount}</span>
                {if $article->updatedTs != $article->createdTs}<span>Обновлено: <time datetime="{$article->updatedTs|date_iso}">{$article->updatedTs|date_utc}</time></span>{/if}
            </div>
            {if $article->description}<p class="lead">{$article->description}</p>{/if}
        </header>
        {if $article->image}<img class="article-cover" src="{$article->image}" alt="Иллюстрация к статье «{$article->title}»" width="1200" height="700" fetchpriority="high">{/if}
        <div class="article-content">{$article->content}</div>
    </article>

    {if $related_articles}
        <section class="related-section" aria-labelledby="related-heading">
            <div class="section-heading"><h2 id="related-heading">Читайте также</h2></div>
            <div class="card-grid">
                {foreach $related_articles as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
