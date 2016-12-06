<span class="tag player-badge is-player-{{ $color }}-bg">
  @if ($civilization < 0)
    <div class="civ-icon">&nbsp;</div>
  @else
    <img class="civ-icon"
        src="{{ asset('vendor/recanalyst/civs/' . $color . '/' . $civilization . '.png') }}"
        alt="@lang('recanalyst::ageofempires.civilizations.' . $civilization)">
  @endif
  {{ $name }}
</span>
