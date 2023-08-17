<script type="text/javascript">
    /* Miscellaneous Scripts */
    // Upload Profile Picture
    $('#jockImage').on('change', function () {
        $('#custom').attr('hidden', 'hidden');
        $('#cropButton').removeAttr('hidden');
        $('#cancelButton').removeAttr('hidden');
        $('#doneButton').attr('hidden', 'hidden');
        $imageToBeCropped = $('#croppingImage').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 500
            }
        });

        readFile(this);
    });

    $('#cancelButton').on('click', function () {
        $('.cropper').removeClass('ready');
        $imageToBeCropped.croppie('destroy');
        $('#addArticleImage').modal('hide');
        $('#croppedArticleImage').val('');
        $('#update-profile').modal('hide');
        $('#update-artist-image').modal('hide');
        $('#dialog').modal('show');
        $('#jockImage').val('');
        $('#custom').removeAttr('hidden');
        $('#cropButton').attr('hidden', 'hidden');
        $('#cancelButton').attr('hidden', 'hidden');
        $('#doneButton').removeAttr('hidden');

        $('#noButton').on('click', function () {
            $('#update-profile').modal('show');
            $('#update-artist-image').modal('show');
        });
    });

    $('#cropButton').on('click', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: {width: 500, height: 500},
            quality: 1
        }).then(function (response) {
            $.ajax({
                url: '{{ route('users.photo.upload') }}',
                method: 'POST',
                data: {
                    'image': response
                },
                beforeSend: () => {
                    $('#cropButton').attr('disabled', 'disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Uploading profile picture please wait',
                    });
                },
                success: (result) => {
                    $('#cropButton').attr('hidden', 'hidden');
                    $('input[id="imageName"]').val(result);

                    Toast.fire({
                        icon: 'success',
                        title: 'Profile picture has been saved!',
                    });

                    setTimeout(() => {
                        $('#saveButton').click();
                    }, 2000);
                },
                error: (result) => {
                    manualToast.fire({
                        icon: 'error',
                        title: result.status + ' ' + result.statusText,
                    });
                }
            });
        });
    });

    // Upload Header Photo
    $('#header').on('change', function () {
        $('#headerCustom').attr('hidden', 'hidden');
        $('#cropHeaderButton').removeAttr('hidden');
        $('#cancelHeaderButton').removeAttr('hidden');
        $('#doneHeaderButton').attr('hidden', 'hidden');

        $imageToBeCropped = $('#croppingHeaderImage').croppie({
            enableExif: true,
            viewport: {
                width: 1920,
                height: 500,
                type: 'square'
            },
            boundary: {
                width: 1920,
                height: 800
            }
        });

        readFile(this);
    });

    $('#cancelHeaderButton').on('click', function () {
        $('.cropperHeader').removeClass('ready');
        $imageToBeCropped.croppie('destroy');
        $('#update-cover').modal('hide');
        $('#dialog').modal('show');
        $('#header').val('');
        $('#headerCustom').removeAttr('hidden');
        $('#cropHeaderButton').attr('hidden', 'hidden');
        $('#cancelHeaderButton').attr('hidden', 'hidden');
        $('#doneHeaderButton').removeAttr('hidden');

        $('#noButton').on('click', function () {
            $('#update-cover').modal('show');
        });
    });

    $('#cropHeaderButton').on('click', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: {width: 1920, height: 500},
        }).then(function (response) {
            $.ajax({
                url: '{{ route('users.photo.upload') }}',
                method: 'POST',
                data: {
                    'image': response
                },
                beforeSend: () => {
                    $('#cropHeaderButton').attr('disabled', 'disabled');
                    manualToast.fire({
                        icon: 'info',
                        title: 'Uploading header image please wait',
                    });
                },
                success: (result) => {
                    $('#cropHeaderButton').attr('hidden', 'hidden');
                    $('input[id="headerImageName"]').val(result);

                    Toast.fire({
                        icon: 'success',
                        title: 'Header image has been saved!'
                    });

                    setTimeout(() => {
                        $('#saveHeaderButton').click();
                    }, 2000);
                },
                error: (result) => {
                    manualToast.fire({
                        icon: 'error',
                        title: result.status + ' ' + result.statusText,
                    });
                }
            });
        });
    });

    // Main image
    $('#jockMainImage').on('change', function () {
        $('#customMain').attr('hidden', 'hidden');
        $('#cropMainButton').removeAttr('hidden');
        $('#cancelMainButton').removeAttr('hidden');
        $('#doneMainButton').attr('hidden', 'hidden');
        $imageToBeCropped = $('#croppingMainImage').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square'
            },
            boundary: {
                width: 500,
                height: 500
            }
        });

        readFile(this);
    });

    $('#cancelMainButton').on('click', function () {
        $('.cropper').removeClass('ready');
        $imageToBeCropped.croppie('destroy');
        $('#addArticleImage').modal('hide');
        $('#croppedArticleImage').val('');
        $('#update-profile').modal('hide');
        $('#update-artist-image').modal('hide');
        $('#dialog').modal('show');
        $('#jockMainImage').val('');
        $('#customMain').removeAttr('hidden');
        $('#cropMainButton').attr('hidden', 'hidden');
        $('#cancelMainButton').attr('hidden', 'hidden');
        $('#doneMainButton').removeAttr('hidden');

        $('#noButton').on('click', function () {
            $('#update-profile').modal('show');
            $('#update-artist-image').modal('show');
        });
    });

    $('#cropMainButton').on('click', function () {
        $imageToBeCropped.croppie('result', {
            type: 'canvas',
            size: {width: 500, height: 500},
            quality: 1
        }).then(function (response) {
            $.ajax({
                url: '{{ route('users.photo.upload') }}',
                method: 'POST',
                data: {
                    'image': response
                },
                beforeSend: () => {
                    $('#cropMainButton').attr('disabled', 'disabled');
                    Toast.fire({
                        icon: 'info',
                        title: 'Uploading main picture please wait',
                    });
                },
                success: (result) => {
                    $('#cropMainButton').attr('hidden', 'hidden');
                    $('input[id="mainImageName"]').val(result);

                    Toast.fire({
                        icon: 'success',
                        title: 'Main picture has been saved!',
                    });

                    setTimeout(() => {
                        $('#saveMainButton').click();
                    }, 2000);
                },
                error: (result) => {
                    manualToast.fire({
                        icon: 'error',
                        title: result.status + ' ' + result.statusText,
                    });
                }
            });
        });
    });
</script>
