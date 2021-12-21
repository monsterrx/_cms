@if(Session::has('success'))
    <script type="text/javascript">
        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
    </script>
@endif