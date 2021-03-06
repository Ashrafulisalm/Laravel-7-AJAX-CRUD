<!DOCTYPE html>
<html>
<head>
    <title>Laravel-7_Ajax CRUD</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                    <!-- Button trigger modal -->
                    <a class="btn btn-success" style="float: right" href="javascript:void(0)" id="createContact"> Create Contact</a>

                </div>

                <div class="card-body">
                    <table class="table table-bordered  data-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th width="280px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="ajaxModel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modelHeading"></h4>
                        </div>
                        <div class="modal-body">
                            <form id="contactForm" name="contactForm" class="form-horizontal">
                                <input type="hidden" name="contact_id" id="contact_id">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Email</label>
                                    <div class="col-md-12">
                                        <input id="email" name="email" required="" placeholder="Enter Email" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Phone</label>
                                    <div class="col-md-12">
                                        <input id="phone" name="phone" required="" placeholder="Enter Phone" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('contacts.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#createContact').click(function () {
            $('#saveBtn').html("Save Contact");
            $('#modelHeading').html('Create New Contact');
            $('#contact_id').val('');
            $('#contactForm').trigger('reset');
            $('#ajaxModel').modal('show');
        });

        $('#saveBtn').click(function (e) {
            e.preventDefault();

            $.ajax({
                data: $('#contactForm').serialize(),
                url: "{{route('contacts.store')}}",
                type:"POST",
                datatype:"JSON",
                success:function (data) {
                    $('#contactForm').trigger('reset');
                    $('#ajaxModel').modal('hide');
                    table.draw();
                },
                error:function (data) {
                    console.log('Error:' , data);
                }
            });
        });

        $('body').on('click','.deleteContact',function () {
            var del_id=$(this).data('id');
            var statement=confirm('Are you want do Delete!');

            if(statement==true){
                $.ajax({
                    type:'DELETE',
                    url:"{{route('contacts.store')}}" + '/' + del_id,
                    success:function (data) {
                        table.draw();
                    },
                    error:function (data) {
                        console.log('Error',data);
                    }
                });
            } else {
                table.draw();
            }
        });

        $('body').on('click','.editContact',function () {
            var edit_id=$(this).data('id');

            $.get("{{route('contacts.index')}}" + '/' +edit_id+ '/edit',function (data) {
                $('#modelHeading').html('Update Contact');
                $('#saveBtn').html('Update');
                $('#ajaxModel').modal('show');
                $('#contact_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
            });

        });

    });
</script>


</body>
</html>
