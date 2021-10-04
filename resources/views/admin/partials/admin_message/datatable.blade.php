@extends('admin.master')
@section('adminContent')


<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Admin Massage</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-hover datatable">    
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Comment</th>
                            <th>Response</th>
                            <th>Read Status</th>
                            <th>Created at</th>
                            <th style="min-width: 80px"></th>
                        </tr>
                    </thead>
                </table>
            </div>            
        </div>
        <!-- /.box -->
    </div>    
</div>

<!--<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>-->
<script type="text/javascript">
    var oTable;
    $(document).ready(function () {
        oTable = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("datatable/get_admin_messages_data") }}',
            columns: [
                {data: 'admin_message_id', name: 'admin_messages.admin_message_id'},
                {data: 'name', name: 'users.name'},
                {data: 'comment', name: 'admin_messages.comment'},
                {data: 'response', name: 'admin_messages.response'},
                {data: 'read_status', name: 'admin_messages.read_status'},
                {data: 'created_at', name: 'admin_messages.created_at'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });
    });

    $(document).on("click", ".dtbutton", function () {
        if ($(this).attr('id') === 'external') {
            window.open($(this).data('href'));
        } else {
            $.get($(this).data('href'), function (data) {
                oTable.ajax.reload(null, false);
            });

        }
    });
</script>

@endsection