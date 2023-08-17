<script type="text/javascript">
    /* Modal Scripts */
    // Article Image Cropper
    $(document).on('show.bs.modal', '#addArticleImage', function () {
        $('input[id="image"]').click();
    });

    $(document).on('show.bs.modal', '[data-action="Open"]', function () {
        let id = $(this).attr('data-message');

        getAsync('{{ route('messages.index') }}', {"message_id": id}, 'JSON', beforeSend, onSuccess);

        function beforeSend() {
            // not necessary
        }

        function onSuccess(result) {
            messagesTable.ajax.reload(null, false);
        }
    });

    $(document).on('show.bs.modal', '#new-featured', function () {
        $('#featured_indie_artist_id').on('change', function () {
            let indie_artist_id = $(this).val();

            getAsync('{{ url('indiegrounds') }}' + '/' + indie_artist_id, null, 'JSON', beforeSend, onSuccess);

            function beforeSend() {

            }

            function onSuccess(result) {
                if (result.indieground.introduction === "No Data") {
                    Toast.fire({
                        'icon': 'info',
                        'title': 'Indieground needs an introduction',
                    });
                } else if (result.indieground.introduction != null) {
                    tinymce.get('content').setContent('<p>' + result.indieground.introduction + '</p>');

                    Toast.fire({
                        'icon': 'info',
                        'title': 'Indieground already has introduction',
                    });
                } else {
                    $('#content').attr('required', 'required');
                }
            }
        });
    });

    $(document).on('hide.bs.modal', '#update-artist-image, #update-profile, #update-header', function () {
        $('#custom').removeAttr('hidden');
        $('#artistCropButton').removeAttr('disabled').attr('hidden', 'hidden');
        $('#cancelButton').attr('hidden', 'hidden');
        $('#doneButton').removeAttr('hidden');
        $('.cropper').removeClass('ready');
        $('#croppingImage').croppie('destroy');
    });

    // for clossing the current modal that's opened or active.
    $(document).on('show.bs.modal', '.modal', function () {
        $('.modal').modal('hide');
    });
    /* End */
</script>
