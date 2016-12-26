@inject('helpers', 'App\Services\BladeHelpersService')

<div class="columns">
  <div class="column" style="order: 2">
    <img src="{{ asset($mapPath) }}" alt="Minimap">
  </div>
  <div class="column" style="order: 1">
    <table class="table">
      <tbody>
        <tr>
          <th>Version</th>
          <td>{{ $analysis->versionName() }}</td>
        </tr>
        <tr>
          <th>Duration</th>
          <td>{{ $helpers->formatGameTime($analysis->duration) }}</td>
        </tr>
        <tr>
          <th>Type</th>
          <td>{{ $analysis->gameTypeName() }}</td>
        </tr>
        <tr>
          <th>Map</th>
          <td>{{ $analysis->mapName() }}</td>
        </tr>
        <tr>
          <th>PoV</th>
          <td>
            @if ($pov)
              <a href="{{ action('GamesController@list', $helpers->filterPlayerName($pov->name)) }}">
                @include('components.player_badge', [
                  'name' => $pov->name,
                  'civilization' => $pov->civilization,
                  'color' => $pov->color,
                ])
              </a>
            @endif
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="columns is-multiline">
  @foreach ($analysis->teams() as $index => $team)
    <div class="column is-half-tablet is-quarter-desktop">
      <h4 class="subtitle">Team {{ $index }}</h4>
      @foreach ($team as $player)
        <div class="media">
          <figure class="media-left">
            <p class="image">
              <img src="{{ asset('vendor/recanalyst/civs/' . $player->color . '/' . $player->civilization . '.png') }}"
                    alt="{{ $player->civName() }}"
                    title="{{ $player->civName() }}">
            </p>
          </figure>
          <div class="media-content">
            <a href="{{ action('GamesController@list', $helpers->filterPlayerName($player->name)) }}"
                class="title is-player-{{ $player->color }}">
              {{ $player->name }}
            </a>
            <p>{{ $player->civName() }}</p>
          </div>
        </div>
      @endforeach
    </div>
  @endforeach
</div>

@if (count($analysis->spectators()))
  <h4 class="subtitle">Spectators</h4>
  <div class="columns is-multiline">
    @foreach ($analysis->spectators() as $spectator)
      <div class="column">
        <p>
          <a href="{{ action('GamesController@list', $helpers->filterPlayerName($spectator->name)) }}"
              class="title is-player-{{ $spectator->color }}">
            {{ $spectator->name }}
          </a>
        </p>
      </div>
    @endforeach
  </div>
@endif
