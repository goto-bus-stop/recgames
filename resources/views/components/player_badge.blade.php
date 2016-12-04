<a class="tag player-badge is-player-{{ $color }}-bg"
   href="{{ action('GamesController@list', ['filter' => ['player' => $name]]) }}">
  <img class="civ-icon"
       src="{{ asset('vendor/recanalyst/civs/' . $color . '/' . $civilization . '.png') }}"
       alt="@lang('recanalyst::ageofempires.civilizations.' . $civilization)">
  {{ $name }}
</a>
