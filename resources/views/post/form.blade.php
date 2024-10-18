<x-app-layout>
        <div class="w-75 mx-auto">
            <form id="form" action="#" method="post">
                <div class="fv-row mb-7">
                    <!--begin::Label-->
                    <label class="fs-6 fw-semibold form-label mt-3">
                        <span class="required">Create You Own Post</span>
                        <span class="ms-1" data-bs-toggle="tooltip" title="Please enter rules.">
                            <i class="ki-duotone ki-information fs-7">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                    </label>
                    <textarea class="form-control form-control-solid" name="post_value" id="post" rows="3"></textarea>
                    <span class="text-danger" id="error-post"></span>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('welcome') }}" class="btn btn-light me-3">
                        Back
                    </a>
                    <button id="create_form_post_submit" class="btn btn-primary">
                        <span class="indicator-label">
                            @if (isset($admin))
                                Update
                            @else
                                Save
                            @endif Admin
                        </span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>&emsp;
                </div>
            </form>
        </div>
    <script>
        $(document).ready(function() {
        var simplemde = new SimpleMDE({
            element: document.getElementById('post'),
            toolbar: [
                "bold", "italic", "strikethrough", "|", "code", "unordered-list", "ordered-list",
                "|", "table", "|", "preview"
            ]
        });

            $('#create_form_post_submit').click(function(e) {
                e.preventDefault();

                // Sync the editor content with the textarea
                $('#post').val(simplemde.value());

                // Toggle loading state
                $(this).addClass('disabled'); // Disable button
                $(this).find('.indicator-label').addClass('d-none'); // Hide label
                $(this).find('.spinner-border').removeClass('d-none'); // Show spinner

                // Initialize FormData object
                var formData = new FormData($('#form')[0]);

                // Set up AJAX request with CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // AJAX request
                $.ajax({
                    url: "{{ route('save.post') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('.text-danger').text('');
                        if (response['success']) {
                            Swal.fire({
                                text: response['message'],
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showCancelButton: false,
                                showCloseButton: false
                            }).then((result) => {
                                if (response['redirection']) {
                                    window.location.href = response['redirectUrl'];
                                }
                            });
                        } else {
                            toastr.error(response['message']);
                        }
                    },
                    error: function(response) {
                        // Handle errors
                        $('.text-danger').text('');
                        if (response.status === 422) {
                            var errors = response.responseJSON.errors;
                            for (var key in errors) {
                                $('#error-' + key).text(errors[key][0]);
                            }
                        }
                    },
                    complete: function() {
                        // Revert loading state
                        $('#create_form_post_submit').removeClass('disabled');
                        $('#create_form_post_submit').find('.indicator-label').removeClass('d-none');
                        $('#create_form_post_submit').find('.spinner-border').addClass('d-none');
                    }
                });
            });
        });

    </script>





</x-app-layout>
