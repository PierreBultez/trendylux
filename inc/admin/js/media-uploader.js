jQuery(document).ready(function($){
    // Generic Media Uploader
    var mediaUploader;

    $('.trendylux-upload-btn').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var targetId = button.data('target'); // The hidden input ID
        var previewId = button.data('preview'); // The preview container ID
        var multiple = button.data('multiple') === true; // Boolean

        // If the uploader object has already been created, reopen the dialog
        // (We create a new one each time to handle different targets properly or store it in a map)
        // For simplicity, we create one on the fly.
        
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choisir une image',
            button: {
                text: 'Utiliser cette image'
            },
            multiple: multiple
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').toJSON();
            var ids = [];
            var previewHtml = '';

            // Handle multiple or single
            if (multiple) {
                $.each(attachment, function(i, val){
                    ids.push(val.id);
                    previewHtml += '<div class="img-preview-item" style="display:inline-block; margin:5px; position:relative;">';
                    previewHtml += '<img src="' + (val.sizes.thumbnail ? val.sizes.thumbnail.url : val.url) + '" style="max-width:100px; height:auto; border:1px solid #ccc;">';
                    previewHtml += '</div>';
                });
                $('#' + targetId).val(ids.join(','));
                $('#' + previewId).html(previewHtml);
            } else {
                // Single
                var val = attachment[0];
                $('#' + targetId).val(val.id);
                previewHtml = '<img src="' + (val.sizes.medium ? val.sizes.medium.url : val.url) + '" style="max-width:200px; height:auto;">';
                $('#' + previewId).html(previewHtml);
            }
        });

        mediaUploader.open();
    });

    // Clear button
    $('.trendylux-clear-btn').on('click', function(e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        var previewId = $(this).data('preview');
        $('#' + targetId).val('');
        $('#' + previewId).html('');
    });
});
