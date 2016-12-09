<?php $players = collect($filter['player'] ?? []) ?>

<form action="{{ action('GamesController@list') }}" method="get">
  @foreach ($players as $name)
    <input type="hidden" name="filter[player][]" value="{{ $name }}">
  @endforeach
  <div class="control is-grouped">
    <?php $id = uniqid() ?>
    <div class="control-label is-narrow">
      <label class="label" for="{{ $id }}">Filter players:</label>
    </div>
    <div class="control is-expanded">
      <p><input class="input" id="{{ $id }}" type="text" name="filter[player][]" placeholder="Player name"></p>
      @if (!$players->isEmpty())
        <p style="margin-top: 5px">
          @foreach ($players as $i => $name)
            <span class="tag">
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
        </p>
      @endif
    </div>
    <div class="control">
      <button class="button is-primary" type="submit">
        Add
      </button>
    </div>
  </div>
</form>
