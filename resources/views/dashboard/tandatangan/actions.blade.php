<div class="btn-group" role="group" aria-label="First group">
    <a href="{{ route('dashboard.tandatangan.edit', $item->id) }}" type="button" class="btn btn-primary btn-icon"><i
            class="la la-edit"></i></a>
    <a href="{{ route('dashboard.tandatangan.show', $item->id) }}" type="button" class="btn btn-warning btn-icon"><i
            class="la la-eye"></i></a>
    <button type="button" data-id="{{ $item->id }}" class="btn btn-danger btn-icon btn-delete" data-toggle="modal"
        data-target="#staticBackdrop"><i class="la la-trash"></i></button>
</div>
