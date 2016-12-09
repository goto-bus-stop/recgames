<?php $players = collect($filter['player'] ?? []) ?>

<form action="{{ action('GamesController@list') }}" method="get">
  @foreach ($players as $name)
    <input type="hidden" name="filter[player][]" value="{{ $name }}">
  @endforeach
  <div class="columns">
    <?php $id = uniqid() ?>
    <div class="column is-narrow">
      <div class="control-label">
        <label class="label" for="{{ $id }}">Filter players:</label>
      </div>
    </div>
    <div class="column is-narrow">
      @if (!$players->isEmpty())
        <div class="control">
          @foreach ($players as $i => $name)
            <span class="tag is-medium is-info">
              {{ $name }}
              <a class="delete is-small"
                  href="{{ action('GamesController@list', [
                    'filter' => array_merge($filter, [
                      'player' => $players->except($i)->all(),
                    ]),
                  ]) }}">
              </a>
            </span>
          @endforeach
        </div>
      @endif
    </div>
    <div class="column">
      <div class="control is-grouped">
        <div class="control is-expanded">
          <input class="input" id="{{ $id }}" type="text" name="filter[player][]" placeholder="Player name">
        </div>
        <div class="control">
          <button class="button is-primary" type="submit">
            Add
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
