<div class="pull-right">
    <form action="{{ route('deploy-trigger') }}" method="POST">
        @csrf

        <input type="submit" class="btn btn-sm btn-primary" value="{{ __('messages.welcome') }}">
    </form>
</div>