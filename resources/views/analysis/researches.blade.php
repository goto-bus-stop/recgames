@inject('helpers', 'App\Services\BladeHelpersService')

<div class="PlayerResearches">
  <div class="PlayerResearches-players">
    @foreach ($players as $player)
      <div class="PlayerResearches-player is-player-{{ $player->color }}-bg">
        <img class="PlayerResearches-civ is-hidden-mobile"
             src="{{ asset('vendor/recanalyst/civs/' . $player->color . '/' . $player->civilization . '.png') }}"
             alt="{{ $player->civName() }}"
             title="{{ $player->civName() }}">
        {{ $player->name }}
      </div>
    @endforeach
    <div class="PlayerResearches-timeRow">
      <strong>Minutes</strong>
    </div>
  </div>
  <div class="PlayerResearches-scrollable">
    <table class="PlayerResearches-researches">
      <?php $table = $table ?? $helpers->buildResearchesTable($players->all()) ?>
      @foreach ($players as $player)
        <?php $timeline = $table[$player->index] ?>
        <tr class="PlayerResearches-row">
          @foreach ($timeline as $minute => $researches)
            <td class="PlayerResearches-minute">
              @foreach ($researches as $research)
                <img class="PlayerResearches-research"
                      src="{{ asset('vendor/recanalyst/researches/' . $research->id . '.png') }}"
                      title="{{ $research->name() }} ({{ $research->formattedTime() }})">
              @endforeach
            </td>
          @endforeach
        </tr>

        @if ($loop->last)
          <tr class="PlayerResearches-timeRow">
            @foreach ($timeline as $minute => $_)
              <td class="PlayerResearches-time">
                {{ $minute }}
              </td>
            @endforeach
          </tr>
        @endif
      @endforeach
    </table>
  </div>
</div>
