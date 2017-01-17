<div class="Chat-pregame" style="margin-bottom: 20px">
  <h3 class="subtitle">Pre-game</h3>
  @forelse ($pregame as $message)
    <div class="ChatMessage">
      @if ($message->player())
        <span class="ChatMessage-sender
                    @if ($message->player()->colorId !== -1) is-player-{{ $message->player()->color }} @endif">
          {{ $message->player()->name }}
        </span>:
      @else
        <span class="ChatMessage-sender" style="background: #f00; color: #fff">
          Unknown player
        </span>:
      @endif
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
      @if ($message->player())
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
