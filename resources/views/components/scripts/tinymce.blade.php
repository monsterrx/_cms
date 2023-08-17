<script type="text/javascript">
    tinymce.init({
        selector: "#content, #update-content, #bug-content",
        height: 300,
        width: '100%',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount image media link tinydrive code',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat | insertfile image link | code ',
        tinydrive_token_provider: '{{ url('/') }}/jwt.php',
        tinydrive_google_drive_key: 'AIzaSyC_IryDOhUCYAM1WBKzNUbLQ5tYNuywYyY',
        tinydrive_google_drive_client_id: '199937399789-s34bf73o7apfapo7ejobpg2pjm7ade7j.apps.googleusercontent.com',
    });
</script>
