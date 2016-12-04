<span class="tag player-badge is-player-{{ $color }}-bg">
  <img class="civ-icon"
       src="{{ asset('vendor/recanalyst/civs/' . $color . '/' . $civilization . '.png') }}"
       alt="@lang('recanalyst::ageofempires.civilizations.' . $civilization)">
  {{ $name }}
</span>
