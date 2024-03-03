@extends('dashboard')
@section('content')

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About</title>
    <style>
    #contacts-table {
        border-collapse: collapse;
        width: 100%;
    }

    #contacts-table th,
    #contacts-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    #contacts-table th {
        background-color: #f2f2f2;
    }

    #contacts-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #contacts-table tbody tr:hover {
        background-color: #ddd;
    }

    #contacts-table img {
        max-width: 50px;
        max-height: 100px;
    }

</style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    @if(Session::has('success'))
    <div id="successAlert" class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    <script>
        setTimeout(function() {
            $('#successAlert').fadeOut('slow');
        }, 3000);
    </script>
    @elseif(Session::has('error'))
    <div id="errorAlert" class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
    <script>
        setTimeout(function() {
            $('#errorAlert').fadeOut('slow');
        }, 3000);
    </script>
    @endif

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#BlogModal">
        +Add
    </button>

    <!-- data showing table -->


<table id="contacts-table" class="display">
    <thead>
        <tr>

            <th>Title</th>
            <th>Details</th>
            <th>Date</th>

            <th>Home Page image</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->title }}</td>

            <td>{{ $row->details }}</td>
            <td>{{ $row->date}}</td>

            <td>
                @if (!empty($row->image))
                <?php
                    $imageData = base64_decode($row->image);
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $imageType = $finfo->buffer($imageData);
                ?>
                <img src='data:<?php echo $imageType; ?>;base64,<?php echo base64_encode($imageData); ?>' alt='Image' class="img-thumbnail">
                @else
                No Image
                @endif
            </td>
            <td>
                <a href="#" class="btn btn-sm btn-info edit" data-id="{{$row->id}}"><i class="fas fa-edit"></i> Edit</a>
                <a href="{{url('blog/delete',$row->id)}}" class="btn bnt-sm btn-danger"><i class="fas fa-trash"></i> Delete</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <!-- Update Modal -->

    <div class="modal fade" id="EditModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Update About Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{url('blog/update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="Edit_id" name="Edit_id">

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="Edit_title" name="title" aria-describedby="titleError">
                            <span id="Edit_titleError" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <input type="text" class="form-control" id="Edit_detail" name="detail" aria-describedby="titleError">
                            <span id="Edit_detailError" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="text" class="form-control" id="Edit_date" name="date" aria-describedby="nameError">
                            <span id="Edit_dateError" class="text-danger"></span>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <!-- Input for file upload -->
                            <input type="file" class="form-control" id="Edit_image" name="image" accept="image/*" aria-describedby="imageError">
                            <!-- Display the image -->
                            <img id="Edit_image_preview" src="" alt="Image Preview" style="max-width: 100%; max-height: 150px;">
                            <span id="Edit_imageError" class="text-danger"></span>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="BlogModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">About Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                <form id="BlogForm" action="{{ url('blog/store') }}" method="post" enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" aria-describedby="titleError">
                            <span id="titleError" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <textarea class="form-control" id="details" name="detail" aria-describedby="detailError"></textarea>
                            <span id="detailError" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" aria-describedby="nameError">
                            <span id="dateError" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" aria-describedby="imageError">
                            <span id="imageError" class="text-danger"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="validateForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
@endsection


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<script>
    $(document).ready(function() {
        $(document).on("click", ".edit", function() {

            let EditId = $(this).data('id');
            console.log(EditId);

            $.ajax({
                type: "GET",
                url: "{{ url('blog/getData') }}/" + EditId,
                success: function(response) {
                    console.log(response);
                    $('#Edit_id').val(response.id);

                    $('#Edit_title').val(response.title);
                    $('#Edit_detail').val(response.details);
                    $('#Edit_date').val(response.date);

                    if (response.image) {
                        $('#Edit_image_preview').attr('src', 'data:image/jpeg;base64,' + response.image); // Assuming the image format is JPEG, adjust accordingly
                    } else {
                        $('#Edit_image_preview').attr('src', ''); // Clear the image if no image is provided
                    }
                    $('#EditModal').modal('show');
                },

            });
        });
    });


</script>


<script>
   function validateForm() {

    var title = document.getElementById('title').value;
    var detail = document.getElementById('details').value;
    var date = document.getElementById('date').value;
    var image = document.getElementById('image').value;




    // Clear previous error messages
    document.getElementById('titleError').innerHTML = '';
    document.getElementById('detailError').innerHTML = '';
    document.getElementById('dateError').innerHTML = '';

    document.getElementById('imageError').innerHTML = '';

    // Perform validation
    if (title.trim() === "") {
        document.getElementById('titleError').innerHTML = 'Please enter Title.';
        return false;
    }
    if (detail.trim() === "") {
        document.getElementById('detailError').innerHTML = 'Please enter some details.';
        return false;
    }

    if (date.trim() === "") {
        document.getElementById('dateError').innerHTML = 'Please enter date';
        return false;
    }


    if (image.trim() === "") {
        document.getElementById('imageError').innerHTML = 'Please upload image';
        return false;
    }

    document.getElementById('BlogForm').submit();
}

</script>
