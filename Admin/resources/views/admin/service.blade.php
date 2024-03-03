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
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ServiceModal">
        +Add
    </button>

    <!-- data showing table -->
    <table id="contacts-table" class="display">
        <thead>
            <tr>

                <th>Service Title</th>
                <th>Service Details</th>
                <th>Service Category</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>


                <td>{{ $row->service_name }}</td>
                <td>{{ $row->service_detail }}</td>
                <td>{{ $row->service_category }}</td>

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
                    <a href="{{url('service/delete',$row->id)}}" class="btn bnt-sm btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"> Update Service Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{url('service/update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="Edit_id" name="Edit_id">



                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input class="form-control" id="Edit_service_name" name="service_name" aria-describedby="detailError">
                            <span id="Edit_service_nameError" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Detail</label>
                            <textarea class="form-control" id="Edit_service_detail" name="service_detail" aria-describedby="detailError"></textarea>
                            <span id="Edit_service_detailError" class="text-danger"></span>
                        </div>
                        <div class="mb-3" id="service_category_container">
                            <label class="form-label">Select Service Category</label>
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
    <div class="modal fade" id="ServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Service Information</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="ServiceForm" action="{{ url('service/store') }}" method="post" enctype="multipart/form-data">

                        @csrf


                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input class="form-control" id="service_name" name="service_name" aria-describedby="detailError">
                            <span id="service_nameError" class="text-danger"></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Details</label>
                            <textarea class="form-control" id="service_detail" name="service_detail" aria-describedby="detailError"></textarea>
                            <span id="service_nameError" class="text-danger"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Service Category</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="service_category[]" id="apartmentsCheckbox" value="apartments">
                                <label class="form-check-label" for="apartmentsCheckbox">Apartments</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="service_category[]" id="foodLifeCheckbox" value="food_life">
                                <label class="form-check-label" for="foodLifeCheckbox">Food & Life</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="service_category[]" id="carsCheckbox" value="cars">
                                <label class="form-check-label" for="carsCheckbox">Cars</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="service_category[]" id="shoppingCheckbox" value="shopping">
                                <label class="form-check-label" for="shoppingCheckbox">Shopping</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="service_category[]" id="travelingCheckbox" value="traveling">
                                <label class="form-check-label" for="travelingCheckbox">Traveling</label>
                            </div>
                            <span id="service_categoryError" class="text-danger"></span>
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

        $.ajax({
            type: "GET",
            url: "{{ url('service/getData') }}/" + EditId,
            success: function(response) {
                console.log(response);

                $('#Edit_id').val(response.id);
                $('#Edit_service_name').val(response.service_name);
                $('#Edit_service_detail').val(response.service_detail);
                // Clear the container before adding new checkboxes
                $('#service_category_container').empty();

                // Generate checkboxes based on response data
                if (response.service_category) {
                    let categories = response.service_category.split(',');
                    categories.forEach(function(category) {
                        let checkbox = $('<div class="form-check">');
                        checkbox.append('<input class="form-check-input" type="checkbox" name="service_category[]" value="' + category + '">');
                        checkbox.append('<label class="form-check-label">' + category + '</label>');
                        $('#service_category_container').append(checkbox);
                    });
                }

                if (response.image) {
                    $('#Edit_image_preview').attr('src', 'data:image/jpeg;base64,' + response.image); // Assuming the image format is JPEG, adjust accordingly
                } else {
                    $('#Edit_image_preview').attr('src', ''); // Clear the image if no image is provided
                }
                $('#EditModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

</script>
<script>
    function validateForm() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="service_category[]"]');
        let checked = false;
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                checked = true;
            }
        });
        document.getElementById('service_categoryError').innerHTML = '';
        if (!checked) {
            document.getElementById('service_categoryError').innerText = 'Please select at least one service category.';
            return false;
        }

        var service_name = document.getElementById('service_name').value;
        var image = document.getElementById('image').value;

        // Clear previous error messages
        document.getElementById('service_nameError').innerHTML = '';
        document.getElementById('imageError').innerHTML = '';

        // Perform validation
        if (service_name.trim() === "") {
            document.getElementById('service_nameError').innerHTML = 'Please enter some details.';
            return false;
        }

        if (image.trim() === "") {
            document.getElementById('imageError').innerHTML = 'Please select an image.';
            return false;
        }

        // Submit the form if all validations pass
        document.getElementById('ServiceForm').submit();
    }
</script>
