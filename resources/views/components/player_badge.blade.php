<span class="PlayerBadge is-player-{{ $color }}-bg">
  @if ($civilization < 0)
    <div class="PlayerBadge-civIcon">&nbsp;</div>
  @else
    <img class="PlayerBadge-civIcon"
         src="{{ asset('vendor/recanalyst/civs/' . $color . '/' . $civilization . '.png') }}"
         alt="@lang('recanalyst::ageofempires.civilizations.' . $civilization)"
         title="@lang('recanalyst::ageofempires.civilizations.' . $civilization)">
  @endif
  {{ $name }}
</span>
