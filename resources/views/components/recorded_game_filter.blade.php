<form action="{{ action('GamesController@list') }}" method="get">
  <div class="columns">
    <?php $id = uniqid() ?>
    <div class="column is-narrow is-hidden-mobile">
      <div class="field-label is-normal">
        <label class="label" for="{{ $id }}">Search:</label>
      </div>
    </div>
    <div class="column">
      <div class="field is-grouped">
        <div class="control is-expanded">
          <input class="input"
                id="{{ $id }}"
                type="text"
                name="filter"
                value="{{ $filter ?? '' }}"
                placeholder="Search players, maps, settings...">
        </div>
        <div class="control">
          <button class="button is-primary" type="submit">Search</button>
        </div>
      </div>
    </div>
  </div>
</form>
