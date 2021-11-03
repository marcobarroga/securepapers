@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Register Document</div>

                <div class="card-body">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <form action="{{ route('docs.store') }}" method="POST" id="submitForm" enctype="multipart/form-data">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="file_description">File Description</label>
                                <input type="text" name="file_description" id="file_description" class="form-field form-control @error('file_description') is-invalid @enderror">
                                @error('file_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="pdf_file">PDF File</label>
                                <input id="pdf_file" type="file" class="form-control @error('pdf_file') is-invalid @enderror" name="pdf_file" value="{{ old("pdf_file") }}">
                                @error("pdf_file")
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Sumbit File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
