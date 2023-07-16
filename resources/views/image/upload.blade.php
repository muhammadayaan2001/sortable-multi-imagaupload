<form id="imageUploadForm" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="images[]" id="images" multiple>
    <button type="submit">Upload</button>
</form>

<div id="imageContainer">
    <ul id="sortable">
        <!-- Uploaded images will be displayed here -->
        @foreach ($images as $image)
            <li data-image-id="{{ $image->id }}">
                <img src="{{ asset('uploads/' . $image->name) }}" alt="Image" width="100">
            </li>
        @endforeach
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to handle file upload
        $('#imageUploadForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            $.ajax({
                url: '{{ route("image.upload") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Display the uploaded images
                    $('#sortable').empty(); // Clear existing images
                    $.each(response.images, function(index, image) {
                        var imgElement = $('<img>').attr('src', '/uploads/' + image.name).attr('alt', 'Image');
                        var liElement = $('<li>').attr('data-image-id', image.id).append(imgElement);
                        $('#sortable').append(liElement);
                    });

                    // Make the images sortable
                    $('#sortable').sortable({
                        update: function(event, ui) {
                            var imageOrder = [];
                            $('#sortable li').each(function() {
                                imageOrder.push($(this).data('image-id'));
                            });

                            // Update the image order in the database
                            updateImageOrder(imageOrder);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        // Function to update the image order
        function updateImageOrder(imageOrder) {
            $.ajax({
                url: '{{ route("image.updateOrder") }}',
                type: 'POST',
                data: {
                    order: imageOrder,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Handle the response if needed
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });
</script>
