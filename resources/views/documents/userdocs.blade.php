@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">User Documents</div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table" id="table-documents">
                            <thead>
                                <tr>
                                    <th scope="col">File Description</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customjs')
    <script>
        $(document).ready(function(){
            $('#table-documents').DataTable( {
                destroy: true,
                scrollX:true,
                scrollY:true,
                processing: true,
                serverSide: true,
                "ajax": {
                    url: "{{ route('user.docs.list') }}",
                    error: function (errmsg) {
                        alert('Unexpected Error');
                        console.log(errmsg['responseText']);
                    }
                },
                "columns": [
                    { "data": "file_description" },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
            });
            $('#table-documents').css({"width":"100%"});
        });
    </script>
@endsection
