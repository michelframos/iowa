CKEDITOR.editorConfig = function( config ) {

    config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
        { name: 'forms', groups: [ 'forms' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
        { name: 'links', groups: [ 'links' ] },
        { name: 'insert', groups: [ 'insert' ] },
        '/',
        { name: 'styles', groups: [ 'styles' ] },
        { name: 'colors', groups: [ 'colors' ] },
        { name: 'tools', groups: [ 'tools' ] },
        { name: 'others', groups: [ 'others' ] },
        { name: 'about', groups: [ 'about' ] }
    ];

    config.removeButtons = 'Source,Save,NewPage,Templates,Cut,Undo,Redo,Find,Replace,SelectAll,Form,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,About,Maximize,BGColor,ShowBlocks,TextColor,Styles,Format,Font,FontSize,Iframe,PageBreak,SpecialChar,Smiley,HorizontalRule,Table,Flash,Image,Link,Unlink,Anchor,Language,BidiRtl,BidiLtr,JustifyCenter,JustifyLeft,JustifyRight,JustifyBlock,Outdent,Indent,Blockquote,CreateDiv,BulletedList,NumberedList,CopyFormatting,RemoveFormat,Superscript,Subscript,Strike,Underline,Italic,Bold,PasteFromWord,PasteText,Paste,Copy';
};