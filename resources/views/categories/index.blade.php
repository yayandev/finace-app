@extends('layouts.app')

@section('title', 'Categories')

@push('css')
<link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="/assets/vendor/libs/typeahead-js/typeahead.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css"/>
<link rel="stylesheet" href="/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
<link rel="stylesheet" href="/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
<link rel="stylesheet" href="/assets/vendor/libs/flatpickr/flatpickr.css" />
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between mb-3">
        <h5 class="m-0">Daftar Kategori</h5>
        {{-- button modal add --}}
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add">
            <i class="mdi mdi-plus"></i> Tambah Kategori
        </button>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
          <table id="tbl_list" class="datatables-basic table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Actions</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
  </div>


  {{-- modal add --}}
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="modal-add" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{route('categories.store')}}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body
            ">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Tipe</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="masuk">Masuk</option>
                        <option value="keluar">Keluar</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
        </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form action="" id="form-edit" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">Tambah Kategori</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body
        ">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="masuk">Masuk</option>
                    <option value="keluar">Keluar</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
<script>
 $(document).ready(function () {
    $('#tbl_list').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url()->current() }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            {
                data: 'type',
                name: 'type',
                render: function (data, type, row) {
                    if (data === 'masuk') {
                        return '<span class="badge bg-success">Masuk</span>';
                    } else if (data === 'keluar') {
                        return '<span class="badge bg-danger">Keluar</span>';
                    }
                    return data;
                }
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modal-edit" data-id="${data}" data-name="${row.name}" data-type="${row.type}">
                            <i class="mdi mdi-pencil"></i>
                        </button>
                            <form action="/categories/${data}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                    `;
                }
            }
        ]
    });
});

// edit
$('#modal-edit').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var name = button.data('name');
    var type = button.data('type');

    var modal = $(this);
    modal.find('.modal-body #name').val(name);
    modal.find('.modal-body #type').val(type);
    modal.find('#form-edit').attr('action', '/categories/' + id);
});
</script>
@endpush
