<script>
  recgames = {!!
    json_encode([
      'upload' => action('GamesController@upload'),
      'api' => [
        'recordedGames' => [
          'create' => action('API\\GamesController@create'),
          'upload' => action('API\\GamesController@upload', '%ID%'),
        ],
      ],
    ])
  !!}
</script>
