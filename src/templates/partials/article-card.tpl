<article class="card">
    <a class="card-link" href="/articles/{$article->id}">
        {if $article->image}
            <img class="card-image" src="{$article->image}" alt="" width="600" height="400">
        {else}
            <div class="card-image image-placeholder" aria-hidden="true">Без изображения</div>
        {/if}
        <div class="card-body">
            <div class="meta">
                <time datetime="{$article->createdTs|date_iso}">{$article->createdTs|date_utc}</time>
                <span>Просмотры: {$article->viewsCount}</span>
            </div>
            <h3>{$article->title}</h3>
            {if $article->description}<p>{$article->description}</p>{/if}
            <span class="read-more">Читать статью <span aria-hidden="true">→</span></span>
        </div>
    </a>
</article>
