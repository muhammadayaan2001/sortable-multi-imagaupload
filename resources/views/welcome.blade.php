<h1>Welcome default pagee</h1>
<div class="container">
    <h2>Upload and Drag Multiple Images</h2>

    <form id="upload-form" method="POST" action="{{ route('upload.images') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="file" name="images[]" multiple>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>

    <div class="image-gallery">
        @foreach($images as $image)
        <div class="draggable-item" data-id="{{ $image->id }}">
            <img src="{{ asset('uploads/' . $image->images) }}" alt="Image" width="100">
        </div>
        @endforeach
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    $('.image-gallery').sortable({
        update: function(event, ui) {
            var images = [];

            $(this).find('.draggable-item').each(function() {
                images.push({
                    id: $(this).data('id'),
                    position: $(this).index()
                });
            });

            $.ajax({
                url: '{{ route("update.image.position") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    images: images,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    // Add any success handling here
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    // Add any error handling here
                }
            });
        }
    });

    $('#upload-form').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                // Add any success handling here
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                // Add any error handling here
            }
        });
    });
});
</script>