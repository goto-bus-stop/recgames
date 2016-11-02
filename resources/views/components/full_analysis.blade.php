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
        <li role="presentation">
          <a href="#chat" aria-controls="chat" role="tab">Chat</a>
        </li>
        <li role="presentation">
          <a href="#researches" aria-controls="researches" role="tab">Researches</a>
        </li>
      </ul>
    </div>
    <div class="tab-panel is-active" id="general" role="tabpanel">
      <h2 class="tab-title title">General</h2>
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
                <td>{{ $pov ? $pov->name : 'Unknown' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="columns is-multiline">
        @foreach ($rec->teams() as $team)
          <div class="column is-half-tablet is-quarter-desktop">
            <header>
              <h4 class="subtitle">Team {{ $team->index() }}</h4>
            </header>
            @foreach ($team->players() as $player)
              <div class="media">
                <figure class="media-left">
                  <p class="image">
                    <img src="{{ asset('vendor/recanalyst/civs/' . $player->colorId . '/' . $player->civId . '.png') }}"
                         alt="{{ $player->civName() }}">
                  </p>
                </figure>
                <div class="media-content">
                  <strong class="title is-player-{{ $player->colorId }}">
                    {{ $player->name }}
                  </strong>
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
            <header>
              <h4 class="subtitle">Team {{ $team->index() }}</h4>
            </header>
            @foreach ($team->players() as $player)
              <div class="media">
                <figure class="media-left">
                  <p class="image">
                    <img src="{{ asset('vendor/recanalyst/civs/' . $player->colorId . '/' . $player->civId . '.png') }}"
                         alt="{{ $player->civName() }}">
                  </p>
                </figure>
                <div class="media-content">
                  <p>
                    <span class="title">
                      {{ $player->name }} ({{ $player->civName() }})
                    </span> <br>
                    <figure class="image is-16x16">
                      <img src="{{ asset('vendor/recanalyst/researches/101.png') }}" alt="">
                    </figure>
                    Feudal: {{ $helpers->formatGameTime($player->feudalTime) }} <br>
                    <figure class="image is-16x16">
                      <img src="{{ asset('vendor/recanalyst/researches/102.png') }}" alt="">
                    </figure>
                    Castle: {{ $helpers->formatGameTime($player->castleTime) }} <br>
                    <figure class="image is-16x16">
                      <img src="{{ asset('vendor/recanalyst/researches/103.png') }}" alt="">
                    </figure>
                    Imperial: {{ $helpers->formatGameTime($player->imperialTime) }}
                  </p>
                </div>
              </div>
            @endforeach
          </div>
        @endforeach
      </div>
    </div>
    <div class="tab-panel" id="chat" role="tabpanel">
      <h2 class="tab-title title">Chat</h2>
      <h3 class="subtitle">Pre-game</h3>
      @foreach ($rec->header()->pregameChat as $message)
        <div class="ChatMessage">
          <span class="ChatMessage-sender">{{ $message->player->name }}</span>:
          {{ $message->msg }}
        </div>
      @endforeach
      <div class="Chat-ingame">
        <h3 class="subtitle">In-game</h3>
        @foreach ($rec->body()->chatMessages as $message)
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
        @endforeach
      </div>
    </div>
    <div class="tab-panel" id="researches" role="tabpanel">
      <h2 class="tab-title">Researches</h2>
      @foreach ($rec->players() as $player)
        <div class="row valign-wrapper">
          <div class="col s3 m2 l1">
            <img src="{{ asset('vendor/recanalyst/civs/' . $player->colorId . '/' . $player->civId . '.png') }}"
                 alt="{{ $player->civName() }}"
                 class="circle">
            <p class="title">
              <strong>{{ $player->name }}</strong> <br>
              {{ $player->civName() }}
            </p>
          </div>
          <div class="col s9 m10 l11">
            @foreach ($player->researches() as $research)
              <div class="center-align left">
                <div class="grey-text text-darken-3">
                  {{ $helpers->formatGameTime($research->time) }}
                </div>
                <img src="{{ asset('vendor/recanalyst/researches/' . $research->id . '.png') }}">
                <div>{{ $research->name() }}</div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
