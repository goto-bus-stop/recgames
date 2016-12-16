
<div class="Chat-pregame" style="margin-bottom: 20px">
  <h3 class="subtitle">Pre-game</h3>
  @forelse ($pregame as $message)
    <div class="ChatMessage">
      <span class="ChatMessage-sender">{{ $message->player()->name }}</span>:
      {{ $message->message }}
    </div>
  @empty
    <p class="notification">No chat messages.</p>
  @endforelse
</div>
<div class="Chat-ingame">
  <h3 class="subtitle">In-game</h3>
  @forelse ($ingame as $message)
    @continue($message->group === 'Rating')

    <div class="ChatMessage">
      <span class="ChatMessage-time">
        {{ $message->formattedTime() }}
      </span>
      @if ($message->player)
        <span class="ChatMessage-sender is-player-{{ $message->player()->color }}">
          {{ $message->player()->name }}
        </span>:
        {{ $message->message }}
      @else
        <em>{{ $message->message }}</em>
      @endif
    </div>
  @empty
    <p class="notification">No chat messages.</p>
  @endforelse
</div>