@extends('layouts.app')

@section('content')
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
    <div id="toast-delete" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            user successfully deleted
        </div>
    </div>
</div>

<div class="container">

    @if(session('msg'))
    <div class="alert alert-success">
        {{ session('msg') }}
    </div>
    @endif

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        Create user
    </a>

    <table
        id="table"
        data-search="true"
        data-show-columns-toggle-all="true"
        data-pagination="true"
        data-id-field="id"
        data-page-list="[10, 25, 50, 100]"
        data-side-pagination="server"
        data-url="{{ route('users.index') }}"
        data-response-handler="responseHandler">
    </table>
</div>

<script>
    let $table = $('#table');
    let selections = [];

    function responseHandler(res) {
        $.each(res.rows, function (i, row) {
            row.state = $.inArray(row.id, selections) !== -1
        });

        return res;
    }

    function operateFormatter(value, row, index) {
        return [
            '<a class="btn btn-primary edit" href="javascript:void(0)" title="Edit">Edit</a> ',
            '<a class="btn btn-danger delete" href="javascript:void(0)" title="Delete">Delete</a>'
        ].join('');
    }

    window.operateEvents = {
        'click .edit': function (e, value, row, index) {
            let url = '{{ route("users.edit", ":id") }}';
            url = url.replace(':id', row.id);

            window.location.href = url;
        },
        'click .delete': function (e, value, row, index) {
            let url = '{{ route("users.destroy", ":id") }}';
            url = url.replace(':id', row.id);

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: (resp) => {
                    let toast = new bootstrap.Toast(document.getElementById('toast-delete'));
                    toast.show();
                    initTable();
                }
            });
        }
    }

    function initTable() {
        $table.bootstrapTable('destroy').bootstrapTable({
            locale: 'en-US',
            columns: [
                {
                    title: 'ID',
                    field: 'id',
                    align: 'center',
                    sortable: true,
                },
                {
                    title: 'Name',
                    field: 'name',
                    align: 'center',
                    sortable: true,
                },
                {
                    title: 'Email',
                    field: 'email',
                    align: 'center',
                    sortable: true,
                },
                {
                    title: 'Phone',
                    field: 'phone',
                    align: 'center',
                    sortable: true,
                },
                {
                    title: 'Age',
                    field: 'age',
                    align: 'center',
                    sortable: true,
                    sortName: 'birthday',
                },
                {
                    title: 'Identification',
                    field: 'identification',
                    align: 'center',
                    sortable: true,
                },
                {
                    title: 'City',
                    field: 'city.name',
                    align: 'center',
                    sortable: true,
                    sortName: 'city_id',
                },
                {
                    title: 'Action',
                    field: 'operate',
                    align: 'center',
                    clickToSelect: false,
                    events: window.operateEvents,
                    formatter: operateFormatter
                }
            ]
        })
    }

    $(function() {
        initTable();
    })
</script>
@endsection
