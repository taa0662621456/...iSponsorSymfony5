// Creates links to the TinyMCE
// https://www.youtube.com/watch?v=fOCw15bpbSw
//

let tinymce = require('tinymce');
require('tinymce/skins/ui/oxide/skin.css');
require('tinymce/themes/silver/index');
require('tinymce/plugins/image');
let form = document.querySelector('#object');
//let form = $('#object').filter('form');
//console.dir(document.documentElement); console.log(form.dataset.objectId);
if (form !== undefined) {
    tinymce.init({
        selector: '.reader',
        plugins: 'image',
        toolbar: 'undo redo | link image',
        automatic_uploads: true,
        images_upload_url: '/attachment/' + form.dataset.name + form.dataset.objectId,
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            let input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                let file = this.files[0];

                let reader = new FileReader();
                reader.onload = function () {
                    let id = 'blobid' + (new Date()).getTime();
                    let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    let base64 = reader.result.split(',')[1];
                    let blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), {title: file.name});
                };
                reader.readAsDataURL(file);
            };

            input.click();
        }
    });
}
