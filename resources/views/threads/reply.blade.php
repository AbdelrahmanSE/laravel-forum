<div class="card">
    <div class="card-header">
        <a href="#">{{ $reply->owner->name }}</a>
        said {{ $thread->created_at->diffForHumans() }}</div>

    <div class="card-body">
        <article>
            <div>{{ $reply->body }}</div>
        </article>
    </div>
</div>
<br>
