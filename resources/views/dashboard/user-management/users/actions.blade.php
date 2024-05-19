<div class="btn-group" role="group" aria-label="First group">
    <a href="{{ route('dashboard.users.edit', $item->nip) }}" type="button" class="btn btn-primary btn-icon"><i
            class="la la-edit"></i></a>
    @if (auth()->id() != $item->id)
        <button type="button" data-id="{{ $item->nip }}" class="btn btn-danger btn-icon btn-delete" data-toggle="modal"
            data-target="#staticBackdrop"><i class="la la-trash"></i></button>
    @endif
</div>
