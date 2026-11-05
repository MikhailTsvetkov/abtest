{extends file='layout.tpl'}
{block name='content'}
    <section class="page-intro">
        <p class="eyebrow">Истории, идеи, впечатления</p>
        <h1>Читайте по интересам</h1>
        <p class="lead">Последние статьи из каждой категории. Выберите то, что интересно вам.</p>
    </section>

    {foreach $categories as $item}
        <section class="category-section" aria-labelledby="category-{$item.category->id}">
            <div class="section-heading">
                <div>
                    <h2 id="category-{$item.category->id}"><a href="{$item.all_articles_url}">{$item.category->name}</a></h2>
                    {if $item.category->description}<p>{$item.category->description}</p>{/if}
                </div>
                <a class="button button-outline" href="{$item.all_articles_url}">Все статьи <span aria-hidden="true">→</span></a>
            </div>
            <div class="card-grid">
                {foreach $item.articles as $article}
                    {include file='partials/article-card.tpl' article=$article}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <section class="empty-state"><h2>Скоро здесь появятся статьи</h2><p>Загляните к нам немного позже.</p></section>
    {/foreach}
{/block}
