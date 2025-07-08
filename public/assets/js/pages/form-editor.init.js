if (document.querySelector('.ckeditor-classic')) {
    document.querySelectorAll(".ckeditor-classic").forEach(element => {
        ClassicEditor.create(element).then(editor => { editor.ui.view.editable.element.style.height = "200px"; }).catch(function(e){console.error(e)});
    });
}
