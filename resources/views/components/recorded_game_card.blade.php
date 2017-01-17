@inject('helpers', 'App\Services\BladeHelpersService')

<div class="column">
  <div class="RecordedGameCard" style="margin: auto">
    <div class="RecordedGameCard-main">
      <a href="{{ action('GamesController@show', $rec->slug) }}">
        <figure class="RecordedGameCard-image">
          <img src="{{ asset($rec->minimap_url) }}" alt="">
        </figure>
      </a>
      <div class="RecordedGameCard-data">
        <p>
          <strong>Version</strong>
          @lang('recanalyst::game_versions.' . $rec->analysis->game_version)
        <p>
          <strong>Map</strong>
          {{ $rec->analysis->map_name }}
          <strong>Size</strong>
          @lang('recanalyst::ageofempires.map_sizes.' . $rec->analysis->map_size)
        </p>
        <p>
          <strong>Duration</strong>
          {{ $helpers->formatGameTime($rec->analysis->duration) }}
        </p>
        <p><strong>Players</strong></p>
        <div class="columns">
          @foreach ($rec->analysis->players->groupBy('team') as $team)
            <div class="column">
              @foreach ($team as $player)
                @include('components.player_badge', [
                  'name' => $player->name,
                  'civilization' => $player->civilization,
                  'color' => $player->color,
                ])
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
    </div>
    <div class="RecordedGameCard-name">
      <p>{{ $rec->filename }}</p>
    </div>
    <div class="RecordedGameCard-footer">
      <time class="RecordedGameCard-time"
            datetime="{{ $rec->created_at->toW3CString() }}"
            title="{{ $rec->created_at }}">
        {{ $rec->created_at->diffForHumans() }}
      </time>
      <a class="RecordedGameCard-link" href="{{ action('GamesController@show', $rec->slug) }}">View</a>
      <a class="RecordedGameCard-link" href="{{ action('GamesController@download', $rec->slug) }}">Download</a>
    </div>
  </div>
</div>
