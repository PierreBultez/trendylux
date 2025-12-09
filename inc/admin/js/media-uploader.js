jQuery(document).ready(function($){
    // Generic Media Uploader Logic
    
    $('.trendylux-upload-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetId = button.data('target'); // The hidden input ID
        var previewId = button.data('preview'); // The preview container ID
        var isMultiple = button.data('multiple') === true; // Boolean check
        
        // Create a custom frame for this specific button click to ensure settings are fresh
        var frame = wp.media({
            title: isMultiple ? 'Choisir des images' : 'Choisir une image',
            button: {
                text: isMultiple ? 'Utiliser ces images' : 'Utiliser cette image'
            },
            multiple: isMultiple ? 'add' : false
        });

        // When a file is selected, grab the URL and set it as the text field's value
        frame.on('select', function() {
            var selection = frame.state().get('selection');
            var ids = [];
            var previewHtml = '';

            if (isMultiple) {
                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    ids.push(attachment.id);
                    
                    var thumbUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    
                    previewHtml += '<div class="img-preview-item" style="display:inline-block; margin:5px; position:relative;">';
                    previewHtml += '<img src="' + thumbUrl + '" style="max-width:100px; height:auto; border:1px solid #ccc;">';
                    previewHtml += '</div>';
                });
                
                $('#' + targetId).val(ids.join(','));
                $('#' + previewId).html(previewHtml);
                
            } else {
                // Single image
                var attachment = selection.first().toJSON();
                var thumbUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                
                $('#' + targetId).val(attachment.id);
                previewHtml = '<img src="' + thumbUrl + '" style="max-width:150px; height:auto; margin-top:10px; display:block;">';
                $('#' + previewId).html(previewHtml);
            }
        });

        frame.open();
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