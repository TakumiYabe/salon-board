<div class="flash-message-block">
    @if (session('flash_message'))
    @if (session('flash_message.success'))
    <div class="flash_message_success">
        {{ session('flash_message.success') }}
    </div>
    @endif
    @if (session('flash_message.fail'))
    <div class="flash_message_fail">
        {{ session('flash_message.fail') }}
    </div>
    @endif
    @endif
</div>
