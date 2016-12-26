<div>
  <h3 class="subtitle">Direct Link</h3>
  <p>
    Share this game with friends by sending them a link, or posting it to
    Facebook or Twitter.
  </p>
  <p>
    <input class="input"
          id="share-direct-link"
          type="text"
          value="{{ action('GamesController@show', $rec->slug) }}"
          onclick="this.select()"
          readonly>
  </p>
</div>
<br>
<div>
  <h3 class="subtitle">Embed</h3>
  <p>
    Embed this game on your own site using this HTML snippet.
  </p>
  <p>
    <textarea class="textarea"
              id="share-embed"
              type="text"
              onclick="this.select()"
              style="font-family: monospace"
              readonly>
<iframe src="{{ action('GamesController@embed', $rec->slug) }}" frameborder="0" width="100%" height="600">
<a href="{{ action('GamesController@show', $rec->slug) }}">View game</a>
</iframe>
</textarea>
  </p>
</div>
