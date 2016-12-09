@inject('helpers', 'App\Services\BladeHelpersService')

<section class="section">
  <div class="container">
    <div class="tabs">
      <ul role="tablist">
        <li role="presentation" class="is-active">
          <a href="#general" aria-controls="general" role="tab">General</a>
        </li>
        <li role="presentation">
          <a href="#advancing" aria-controls="advancing" role="tab">Advancing</a>
        </li>
        @if ($achievements)
          <li role="presentation">
            <a href="#achievements" aria-controls="achievements" role="tab">Achievements</a>
          </li>
        @endif
        <li role="presentation">
          <a href="#chat" aria-controls="chat" role="tab">Chat</a>
        </li>
        <li role="presentation">
          <a href="#researches" aria-controls="researches" role="tab">Researches</a>
        </li>
      </ul>
      <ul class="is-right">
        <li role="presentation">
          <a href="#sharing" aria-controls="sharing" role="tab">Share</a>
        </li>
        <li role="presentation">
          <a href="{{ action('GamesController@download', $model->slug) }}">Download</a>
        </li>
      </ul>
    </div>
    <div class="tab-panel is-active" id="general" role="tabpanel">
      <h2 class="tab-title title nojs-hidden">General</h2>
      <div class="columns">
        <div class="column" style="order: 2">
          <img src="{{ asset($mapPath) }}" alt="Minimap">
        </div>
        <div class="column" style="order: 1">
          <table class="table">
            <tbody>
              <tr>
                <th>Version</th>
                <td>{{ $rec->version()->name() }}</td>
              </tr>
              <tr>
                <th>Duration</th>
                <td>{{ $helpers->formatGameTime($rec->body()->duration) }}</td>
              </tr>
              <tr>
                <th>Type</th>
                <td>{{ $rec->gameSettings()->gameTypeName() }}</td>
              </tr>
              <tr>
                <th>Map</th>
                <td>{{ $rec->gameSettings()->mapName() }}</td>
              </tr>
              <tr>
                <th>PoV</th>
                <td>
                  @if ($pov)
                    <a href="{{ action('GamesController@list', ['filter' => ['player' => $pov->name]]) }}">
                      @include('components.player_badge', [
                        'name' => $pov->name,
                        'civilization' => $pov->civId,
                        'color' => $pov->colorId,
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
        @foreach ($rec->teams() as $team)
          <div class="column is-half-tablet is-quarter-desktop">
            <h4 class="subtitle">Team {{ $team->index() }}</h4>
            @foreach ($team->players() as $player)
              <div class="media">
                <figure class="media-left">
                  <p class="image">
                    <img src="{{ asset('vendor/recanalyst/civs/' . $player->colorId . '/' . $player->civId . '.png') }}"
                         alt="{{ $player->civName() }}"
                         title="{{ $player->civName() }}">
                  </p>
                </figure>
                <div class="media-content">
                  <a href="{{ action('GamesController@list', ['filter' => ['player' => $player->name]]) }}"
                     class="title is-player-{{ $player->colorId }}">
                    {{ $player->name }}
                  </a>
                  <p>{{ $player->civName() }}</p>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
    <div class="tab-panel" id="advancing" role="tabpanel">
      <h2 class="tab-title title">Advancing</h2>
      <div class="columns is-multiline">
        @foreach ($rec->teams() as $team)
          <div class="column is-half-tablet">
            <h4 class="subtitle">Team {{ $team->index() }}</h4>
            @foreach ($team->players() as $player)
              <div class="media">
                <figure class="media-left">
                  <p class="image">
                    <img src="{{ asset('vendor/recanalyst/civs/' . $player->colorId . '/' . $player->civId . '.png') }}"
                         alt="{{ $player->civName() }}"
                         title="{{ $player->civName() }}">
                  </p>
                </figure>
                <div class="media-content">
                  <p>
                    <span class="title is-5">
                      <span class="is-player-{{ $player->colorId }}">{{ $player->name }}</span>
                      ({{ $player->civName() }})
                    </span> <br>
                    <div class="is-inline-block">
                      <figure class="image is-16x16">
                        <img src="{{ asset('vendor/recanalyst/researches/101.png') }}" alt="">
                      </figure>
                    </div>
                    Feudal: {{ $helpers->formatGameTime($player->feudalTime) }} <br>
                    <div class="is-inline-block">
                      <figure class="image is-16x16">
                        <img src="{{ asset('vendor/recanalyst/researches/102.png') }}" alt="">
                      </figure>
                    </div>
                    Castle: {{ $helpers->formatGameTime($player->castleTime) }} <br>
                    <div class="is-inline-block">
                      <figure class="image is-16x16">
                        <img src="{{ asset('vendor/recanalyst/researches/103.png') }}" alt="">
                      </figure>
                    </div>
                    Imperial: {{ $helpers->formatGameTime($player->imperialTime) }}
                  </p>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>

    @if ($achievements)
      <div class="tab-panel" id="achievements" role="tabpanel">
        <h2 class="tab-title title">Achievements</h2>
        @include('components.achievements_tabs', [
          'id' => 'achievements',
          'players' => $rec->players(),
        ])
      </div>
    @endif
    <div class="tab-panel" id="chat" role="tabpanel">
      <h2 class="tab-title title">Chat</h2>
      <div class="Chat-pregame" style="margin-bottom: 20px">
        <h3 class="subtitle">Pre-game</h3>
        @forelse ($rec->header()->pregameChat as $message)
          <div class="ChatMessage">
            <span class="ChatMessage-sender">{{ $message->player->name }}</span>:
            {{ $message->msg }}
          </div>
        @empty
          <p class="notification">No chat messages.</p>
        @endforelse
      </div>
      <div class="Chat-ingame">
        <h3 class="subtitle">In-game</h3>
        @forelse ($rec->body()->chatMessages as $message)
          @continue($message->group === 'Rating')

          <div class="ChatMessage">
            <span class="ChatMessage-time">
              {{ $helpers->formatGameTime($message->time) }}
            </span>
            @if ($message->player)
              <span class="ChatMessage-sender is-player-{{ $message->player->colorId }}">
                {{ $message->player->name }}
              </span>:
              {{ $message->msg }}
            @else
              <em>{{ $message->msg }}</em>
            @endif
          </div>
        @empty
          <p class="notification">No chat messages.</p>
        @endforelse
      </div>
    </div>
    <div class="tab-panel" id="researches" role="tabpanel">
      <h2 class="tab-title title">Researches</h2>
      @include('components.researches_table', [
        'players' => $rec->players(),
      ])
    </div>
    <div class="tab-panel" id="sharing" role="tabpanel">
      <h2 class="tab-title title">Share</h2>
      <div>
        <h3 class="subtitle">Direct Link</h3>
        <p>
          Share this game with friends by sending them a link, or posting it to
          Facebook or Twitter.
        </p>
        <p>
          <input class="input"
                id="share-direct-link"
                type="text"
                value="{{ action('GamesController@show', $model->slug) }}"
                onclick="this.select()"
                readonly>
        </p>
      </div>
      <br>
      <div>
        <h3 class="subtitle">Embed</h3>
        <p>
          Embed this game on your own site using this HTML snippet.
        </p>
        <p>
          <textarea class="textarea"
                    id="share-embed"
                    type="text"
                    onclick="this.select()"
                    style="font-family: monospace"
                    readonly>
<iframe src="{{ action('GamesController@embed', $model->slug) }}" frameborder="0" width="100%" height="600">
  <a href="{{ action('GamesController@show', $model->slug) }}">View game</a>
</iframe>
</textarea>
        </p>
      </div>
    </div>
  </div>
</section>
