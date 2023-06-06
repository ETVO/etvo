$('.rich-editor').each(function (i, obj) {
    var toolbarOptions = [
        [{ 'header': [false, 1, 2, 3, 4, 5, 6] }],
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        
        ['link'],        // toggled buttons
        
        ['clean']                                         // remove formatting button
    ];
    var quill = new Quill(obj, {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions
        },
    });
});