<div class="column is-half-mobile is-quarter-tablet">
  <div class="card">
    <div class="card-image">
      <a href="{{ action('GamesController@show', $rec->slug) }}">
        <figure class="image is-2by1">
          <img src="{{ asset(Storage::url('public/minimaps/' . $rec->slug . '.png')) }}" alt="">
        </figure>
      </a>
    </div>
    <div class="card-content">
      <p>{{ $rec->filename }}</p>
      <p>
        <time datetime="{{ $rec->created_at->toW3CString() }}" title="{{ $rec->created_at }}">
          {{ $rec->created_at->diffForHumans() }}
        </time>
      </p>
    </div>
  </div>
</div>
