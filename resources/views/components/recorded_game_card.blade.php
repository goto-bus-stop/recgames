<div class="column">
  <div class="card" style="margin: auto">
    <div class="card-image">
      <a href="{{ action('GamesController@show', $rec->slug) }}">
        <figure class="image is-2by1">
          <img src="{{ asset($rec->minimap_url) }}" alt="">
        </figure>
      </a>
    </div>
    <div class="card-content">
      <p>{{ $rec->filename }}</p>
    </div>
    <div class="card-footer">
      <time class="card-footer-item"
            datetime="{{ $rec->created_at->toW3CString() }}"
            title="{{ $rec->created_at }}">
        {{ $rec->created_at->diffForHumans() }}
      </time>
      <a class="card-footer-item" href="{{ action('GamesController@show', $rec->slug) }}">View</a>
      <a class="card-footer-item" href="{{ action('GamesController@download', $rec->slug) }}">Download</a>
    </div>
  </div>
</div>
