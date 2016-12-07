<?php $id = $id ?? str_random(3) ?>
<div class="tabs">
  <ul role="tablist">
    <li role="presentation" class="is-active">
      <a href="#{{ $id }}-general" aria-controls="{{ $id }}-general" role="tab">Scores</a>
    </li>
    <li role="presentation">
      <a href="#{{ $id }}-military" aria-controls="{{ $id }}-military" role="tab">Military</a>
    </li>
    <li role="presentation">
      <a href="#{{ $id }}-economy" aria-controls="{{ $id }}-economy" role="tab">Economy</a>
    </li>
    <li role="presentation">
      <a href="#{{ $id }}-technology" aria-controls="{{ $id }}-technology" role="tab">Technology</a>
    </li>
    <li role="presentation">
      <a href="#{{ $id }}-society" aria-controls="{{ $id }}-society" role="tab">Society</a>
    </li>
  </ul>
</div>

<div class="tab-panel is-active" id="{{ $id }}-general" role="tabpanel">
  <h2 class="tab-title title">Scores</h2>
  <table class="AchievementsGroup table" style="width: auto">
    <thead>
      <tr>
        <td class="AchievementsGroup-column"></td>
        <th class="AchievementsGroup-column">Military</th>
        <th class="AchievementsGroup-column">Economy</th>
        <th class="AchievementsGroup-column">Technology</th>
        <th class="AchievementsGroup-column">Society</th>
        <th class="AchievementsGroup-column">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rec->players() as $player)
        <tr>
          <td class="AchievementsGroup-player">
            @include('components.player_badge', [
              'name' => $player->name,
              'civilization' => $player->civId,
              'color' => $player->colorId,
            ])
          </td>
          <?php $achievements = $player->achievements() ?>
          <td>{{ $achievements->military->score }}</td>
          <td>{{ $achievements->economy->score }}</td>
          <td>{{ $achievements->tech->score }}</td>
          <td>{{ $achievements->society->score }}</td>
          <td>{{ $achievements->score }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="tab-panel" id="{{ $id }}-military" role="tabpanel">
  <h2 class="tab-title title">Military</h2>
  <table class="AchievementsGroup table" style="width: auto">
    <thead>
      <tr>
        <td class="AchievementsGroup-column"></td>
        <th class="AchievementsGroup-column">Units Killed</th>
        <th class="AchievementsGroup-column">Units Lost</th>
        <th class="AchievementsGroup-column">Buildings Razed</th>
        <th class="AchievementsGroup-column">Buildings Lost</th>
        <th class="AchievementsGroup-column">Units Converted</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rec->players() as $player)
        <tr>
          <?php $military = $player->achievements()->military ?>
          <td class="AchievementsGroup-player">
            @include('components.player_badge', [
              'name' => $player->name,
              'civilization' => $player->civId,
              'color' => $player->colorId,
            ])
          </td>
          <td title="Inflicted {{ $military->hitPointsKilled }} HP damage">
            {{ $military->unitsKilled }}
          </td>
          <td>{{ $military->unitsLost }}</td>
          <td>{{ $military->buildingsRazed }}</td>
          <td>{{ $military->buildingsLost }}</td>
          <td>{{ $military->unitsConverted }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="tab-panel" id="{{ $id }}-economy" role="tabpanel">
  <h2 class="tab-title title">Economy</h2>
  <table class="AchievementsGroup table" style="width: auto">
    <thead>
      <tr>
        <td class="AchievementsGroup-column"></td>
        <th class="AchievementsGroup-column">Food Gathered</th>
        <th class="AchievementsGroup-column">Wood Gathered</th>
        <th class="AchievementsGroup-column">Stone Gathered</th>
        <th class="AchievementsGroup-column">Gold Gathered</th>
        <th class="AchievementsGroup-column">Trade Profits</th>
        <th class="AchievementsGroup-column">Tributes Sent / Recvd.</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rec->players() as $player)
        <tr>
          <?php $economy = $player->achievements()->economy ?>
          <td class="AchievementsGroup-player">
            @include('components.player_badge', [
              'name' => $player->name,
              'civilization' => $player->civId,
              'color' => $player->colorId,
            ])
          </td>
          <td>{{ $economy->foodCollected }}</td>
          <td>{{ $economy->woodCollected }}</td>
          <td>{{ $economy->stoneCollected }}</td>
          <td>{{ $economy->goldCollected }}</td>
          <td>{{ $economy->tradeProfit }}</td>
          <td>{{ $economy->tributeSent }} / {{ $economy->tributeReceived }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="tab-panel" id="{{ $id }}-technology" role="tabpanel">
  <h2 class="tab-title title">Technology</h2>
  <table class="AchievementsGroup table" style="width: auto">
    <thead>
      <tr>
        <td class="AchievementsGroup-column"></td>
        <th class="AchievementsGroup-column">Feudal Age</th>
        <th class="AchievementsGroup-column">Castle Age</th>
        <th class="AchievementsGroup-column">Imperial Age</th>
        <th class="AchievementsGroup-column">Map Explored %</th>
        <th class="AchievementsGroup-column">Techs Researched</th>
        <th class="AchievementsGroup-column">Tech Tree Completion %</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rec->players() as $player)
        <tr>
          <?php $tech = $player->achievements()->tech ?>
          <td class="AchievementsGroup-player">
            @include('components.player_badge', [
              'name' => $player->name,
              'civilization' => $player->civId,
              'color' => $player->colorId,
            ])
          </td>
          <td>{{ $helpers->formatGameTime($tech->feudalTime === -1 ? 0 : $tech->feudalTime * 1000) }}</td>
          <td>{{ $helpers->formatGameTime($tech->castleTime === -1 ? 0 : $tech->castleTime * 1000) }}</td>
          <td>{{ $helpers->formatGameTime($tech->imperialTime === -1 ? 0 : $tech->imperialTime * 1000) }}</td>
          <td>{{ $tech->mapExploration }}%</td>
          <td>{{ $tech->researchCount }}</td>
          <td>{{ $tech->researchPercent }}%</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="tab-panel" id="{{ $id }}-society" role="tabpanel">
  <h2 class="tab-title title">Society</h2>
  <table class="AchievementsGroup table" style="width: auto">
    <thead>
      <tr>
        <td class="AchievementsGroup-column"></td>
        <th class="AchievementsGroup-column">Wonders Built</th>
        <th class="AchievementsGroup-column">Castles Built</th>
        <th class="AchievementsGroup-column">Relics Captured</th>
        <th class="AchievementsGroup-column">Relic Gold Earned</th>
        <th class="AchievementsGroup-column">Villager High</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($rec->players() as $player)
        <tr>
          <?php $society = $player->achievements()->society ?>
          <td class="AchievementsGroup-player">
            @include('components.player_badge', [
              'name' => $player->name,
              'civilization' => $player->civId,
              'color' => $player->colorId,
            ])
          </td>
          <td>{{ $society->totalWonders }}</td>
          <td>{{ $society->totalCastles }}</td>
          <td>{{ $society->relicsCaptured }}</td>
          <td>{{ $player->achievements()->economy->relicGold }}%</td>
          <td>{{ $society->villagerHigh }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
