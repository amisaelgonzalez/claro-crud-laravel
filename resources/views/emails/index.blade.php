@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('msg'))
    <div class="alert alert-success">
        {{ session('msg') }}
    </div>
    @endif

    <a href="{{ route('emails.create') }}" class="btn btn-primary mb-3">
        Create email
    </a>

    <table
        id="table"
        data-show-columns-toggle-all="true"
        data-pagination="true"
        data-id-field="id"
        data-page-list="[10, 25, 50, 100]"
        data-side-pagination="server"
        data-url="{{ route('emails.index') }}">
    </table>
</div>

<script>
    let $table = $('#table');

    function statusFormatter(data) {
        switch (data) {
            case 'PENDING':
                return 'Pending';

            case 'SENT':
                return 'Send';

            default:
                return '';
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
                },
                {
                    title: 'Subject',
                    field: 'subject',
                    align: 'center',
                },
                {
                    title: 'To',
                    field: 'to',
                    align: 'center',
                },
                {
                    title: 'Message',
                    field: 'message',
                    align: 'center',
                },
                {
                    title: 'Status',
                    field: 'status',
                    align: 'center',
                    formatter: statusFormatter,
                },
            ]
        })
    }

    $(function() {
        initTable();
    })
</script>
@endsection
