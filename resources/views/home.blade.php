<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">


    <title>CRUD | Ajax</title>

</head>

<body>
    <header class="mt-5 mb-3">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2>Laravel CRUD Application with Ajax!</h2>
                    <hr>
                </div>
            </div>
        </div>
    </header>

    <section class="body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">All Employee</h4>
                            
                            <a href="javascript:void(0)" id="createNewEmployee" class="btn btn-primary" data-toggle="modal" data-target="#createTask">Add Employee</a>

                        </div>
                        <div class="card-body">
                            <table class="table table-bordered data-table">
                                <thead>
                                    <tr>
                                        <th> No. </th>
                                        <th> Name </th>
                                        <th> Email Address </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Start Create Employee Modal -->
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="createTaskTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="employeeForm" name="employeeForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalHeading"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="employee_id" id="employee_id"> 
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter the employee's name..." required>
                        </div>
                        <div class="form-group">
                            <label for="">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter the employee's email address..." required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="saveBtn" value="create">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Create Employee Modal -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
</body>

<script type="text/javascript">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({

            serverSide: true,
            processing: true,
            ajax: "{{ route('home.index') }}",
            columns: [{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'action', name: 'action' },
            ]

        });

        $("#createNewEmployee").click(function(){
            $('#employee_id').val('');
            $('#employeeForm').trigger("reset");
            $('#modalHeading').html("Add Employee");
            $('#ajaxModal').modal('show');
        });

        $(document).on("click","#saveBtn",function(e){
            e.preventDefault();
            $(this).html('Submit'); 

            $.ajax({
                data:$("#employeeForm").serialize(),
                url:"{{route('home.store')}}",
                type:"POST",
                dataType:'json',
                success:function(data){
                    $('#employeeForm').trigger("reset");
                    $('#ajaxModal').modal('hide');
                    table.draw();
                },
                error:function(data){
                    console.log('Error:',data);
                    $("#saveBtn").html('Submit');
                }
            });
        });

        $('body').on('click','.deleteEmployee',function(){
            var employee_id = $(this).data("id");
            confirm("Are you sure delete this employee ?");
            $.ajax({
                type:"DELETE",
                url:"{{route('home.store')}}"+'/'+employee_id,
                success:function(data){
                    table.draw();
                },
                error:function(data){
                    console.log('Error:',data);
                }

            });
        });

        $('body').on('click','.editEmployee',function(){
            var employee_id = $(this).data('id');
            $.get("{{route('home.index')}}"+"/"+employee_id+"/edit",function(data){
                $('#modalHeading').html("Edit Employee");
                $('#ajaxModal').modal('show');
                $("#employee_id").val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
            });
        });

    });
</script>

</html>