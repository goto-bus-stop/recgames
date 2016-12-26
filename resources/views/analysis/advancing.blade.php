@inject('helpers', 'App\Services\BladeHelpersService')

<div class="columns is-multiline">
  @foreach ($analysis->teams() as $index => $team)
    <div class="column is-half-tablet">
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
            <p>
              <span class="title is-5">
                <span class="is-player-{{ $player->color }}">{{ $player->name }}</span>
                ({{ $player->civName() }})
              </span> <br>
              <div class="is-inline-block">
                <figure class="image is-16x16">
                  <img src="{{ asset('vendor/recanalyst/researches/101.png') }}" alt="">
                </figure>
              </div>
              Feudal: {{ $helpers->formatGameTime($player->feudalTime()) }} <br>
              <div class="is-inline-block">
                <figure class="image is-16x16">
                  <img src="{{ asset('vendor/recanalyst/researches/102.png') }}" alt="">
                </figure>
              </div>
              Castle: {{ $helpers->formatGameTime($player->castleTime()) }} <br>
              <div class="is-inline-block">
                <figure class="image is-16x16">
                  <img src="{{ asset('vendor/recanalyst/researches/103.png') }}" alt="">
                </figure>
              </div>
              Imperial: {{ $helpers->formatGameTime($player->imperialTime()) }}
            </p>
          </div>
        </div>
      @endforeach
    </div>
  @endforeach
</div>
