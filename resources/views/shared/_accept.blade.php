@can ('accept', $model)
    <a title="Mark this answer as best answer" class="{{ $model->status }} mt-2" onclick="event.preventDefault(); document.getElementById('accept-answer-{{ $model->id }}').submit(); ">
        <i class="fas fa-check fa-2x"></i>
        <span class="favorites-count"></span>
    </a>
    <form id="accept-answer-{{ $model->id }}" action="{{ route('answer.accept', $model->id) }}" method="post" style="display: none;">
        @csrf
    </form>
@else
    @if($model->is_best)
        <a title="The question owner accepted this answer as best answer" class="{{ $model->status }} mt-2">
            <i class="fas fa-check fa-2x"></i>
            <span class="favorites-count"></span>
        </a>
    @endif
@endcan
