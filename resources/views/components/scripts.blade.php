<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/datatables.min.js') }}"></script>
<script src="{{ asset('js/feather.min.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
<script src="{{ asset('js/all.min.js') }}"></script>
{{-- Old Tinymce --}}
{{--<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>--}}
<script src="https://cdn.tiny.cloud/1/u3b0v3xpk1gxxfrea1jh5z3bewrv4z22l10o1ue35aiqglfx/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
<script src="{{ asset('js/jquery.inputmask.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/croppie.min.js') }}"></script>
<script src="{{ asset('js/customScripts.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json; utf-8;',
        },
    });

    setTimeout(function () {
        $('.alert-success').fadeOut();
        $('.alert-danger').fadeOut();
    }, 2500);

    setTimeout(() => {
        Toast.close();
    }, 3500)


    $('#active').addClass('active');
    $('table').css('width', '100%');
</script>
@include('components.scripts.functions')
@include('components.scripts.datatables')
@include('components.scripts.routes')
@include('components.scripts.events')
@include('components.scripts.modal')
@include('components.scripts.cropper')
@include('components.scripts.tinymce')
