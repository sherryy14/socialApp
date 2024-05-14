<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 ">
                    <form action="{{ url('ajax') }}" class="row g-3" id="form">
                        <p class="text-danger text-center" id="error"></p>
                        <p class="text-success text-center" id="success"></p>
                        @csrf
                        <div class="col-md-4 offset-md-1">
                            <label for="caption" class="form-label">Caption</label>
                            <input type="text" class="form-control" id="caption" name="caption">
                        </div>
                        <div class="col-md-4">
                            <label for="file" class="form-label">File</label>
                            <input type="file" class="form-control" id="file" name="file">
                        </div>

                        <div class="col-md-2 d-flex align-items-end justify-content-start">
                            <button class="btn btn-primary" type="submit">Post</button>
                        </div>
                    </form>
                </div>

                <div class="p-6 text-gray-900">
                    <div class="row" id="postData">

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    $(document).ready(function() {
        function fetchPost() {
            $('#postData').html('')
            $.ajax({
                url: "{{ url('fetch') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let cardHtml = '';
                    $.each(data.posts, function(key, item) {
                        var createdAt = new Date(item.created_at);

                        var formattedDate = createdAt.toLocaleDateString('en-US', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });

                        var formattedTime = createdAt.toLocaleString('en-US', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });

                        var cardHtml = `<div class="col-md-12">
                        <div class="card mb-3" >
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="uploads/${item.file}" class="img-fluid rounded-start" alt="${item.caption}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">${item.caption}</h5>
                                        <p class="card-text"><small class="text-muted">Posted: ${formattedDate} at ${formattedTime}</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                        $('#postData').append(cardHtml);
                    });
                }

            })
        }

        fetchPost()

        $('#form').on('submit', function(e) {
            e.preventDefault();

            var caption = $('#caption').val();
            var file = $('#file').val();

            if (caption.trim() === '' || file.trim() === '') {
                $('#error').text('Caption and file fields cannot be empty');
                return;
            }

            var formData = new FormData(this);

            $.ajax({
                url: "{{ url('ajax') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#success').text('');
                    $('#error').text('');
                    if (data.status == 'success') {
                        $('#success').text(data.message);
                    } else {
                        $('#error').text(data.message);
                    }
                    $('#form')[0].reset();
                    fetchPost()
                },
                error: function(data) {
                    $('#error').text(data.message);
                }
            });
        });
    });
</script>
