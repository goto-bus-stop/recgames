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
          <a href="{{ action('GamesController@download', $rec->slug) }}">Download</a>
        </li>
      </ul>
    </div>
    <div class="tab-panel is-active" id="general" role="tabpanel">
      <h2 class="tab-title title nojs-hidden">General</h2>
      @include('analysis.general', [
        'analysis' => $analysis,
        'pov' => $analysis->pov(),
        'mapPath' => $rec->minimap_url,
      ])
    </div>
    <div class="tab-panel" id="advancing" role="tabpanel">
      <h2 class="tab-title title">Advancing</h2>
      @include('analysis.advancing', [
        'analysis' => $analysis,
      ])
    </div>

    @if ($achievements)
      <div class="tab-panel" id="achievements" role="tabpanel">
        <h2 class="tab-title title">Achievements</h2>
        @include('analysis.achievements', [
          'id' => 'achievements',
          'players' => $analysis->players(),
        ])
      </div>
    @endif
    <div class="tab-panel" id="chat" role="tabpanel">
      <h2 class="tab-title title">Chat</h2>
      @include('analysis.chat', [
        'ingame' => $analysis->ingameChat(),
        'pregame' => $analysis->pregameChat(),
      ])
    </div>
    <div class="tab-panel" id="researches" role="tabpanel">
      <h2 class="tab-title title">Researches</h2>
      @include('analysis.researches', [
        'players' => $analysis->players(),
      ])
    </div>
    <div class="tab-panel" id="sharing" role="tabpanel">
      <h2 class="tab-title title">Share</h2>
      @include('analysis.share', [
        'rec' => $rec,
      ])
    </div>
  </div>
</section>
