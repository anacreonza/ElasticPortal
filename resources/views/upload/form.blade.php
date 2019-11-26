@extends('layouts.app')
@section('navbar')
    @component('layouts.navbar')
    @endcomponent
@endsection
@section('header')
    <meta name="_token" content="{{csrf_token()}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/dropzone.js"></script>
    {{-- <script> 
    $(document).ready(function() { 
        let token = $('form').find('input[name="_token"]').val();
        let myData = $('form').find('input[name="dropzone"]').val();
        $('form').submit(function() { 
            $.ajax({ 
                type:'POST', 
                url:'/ajax', 
                data: {_token: token, my_data: myData}
                // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, // unnecessary 
                // other ajax settings
            }); 
            return false;
        }); 
    }); 
</script> --}}

@endsection
@section('content')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container">
    <h1>Upload Files</h1>
    <p>(Note: This is not working yet.)</p>
    <form action="{{url('upload/files')}}" class="dropzone" id="dropzone" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="dz-message" data-dz-message><span>Click or drag files here to upload.</span></div>
    </form>
    <script type="text/javascript">
        Dropzone.options.dropzone =
         {
            maxFilesize: 90,
            renameFile: function(file) {
                var dt = new Date();
                var time = dt.getTime();
               return time+file.name;
            },
            // acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 5000,
            success: function(file, response) 
            {
                console.log(response);
            },
            error: function(file, response)
            {
               return false;
            }
};
</script>
</div>    
@endsection