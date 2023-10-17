<?php 
class CkEditorHelper extends AppHelper {
	
	var $helpers = array('Html', 'Form', 'Js');
	
	/**
	 * @Description : Tạo tool editor
	 *
	 * @plugin thêm vào: pbckcode (Sdung gõ mã lập trình)
	 * @Author 	: Hoang Tuan Anh - tuananh@url.vn
	 */
	function create($fieldName,$options=array()) {
		$toolbar = array();
		
		//['button1','button2','button3','Timestamp'];
		//,'MediaEmbed','pbckcode', 'PageBreak'
		
		switch ($options['toolbar']){
			case 'product':
				$toolbar = "[
								['Source'],['PasteText','PasteFromWord','-','Templates'],['Undo', 'Redo'],['Find','Replace','Scayt'],['ShowBlocks', 'Maximize','Preview'],'/',
								['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript','-','RemoveFormat'],['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],['Image','Table','-','Link','Unlink', 'Anchor','-', 'HorizontalRule', 'Flash','Iframe',  'Smiley', 'SpecialChar','-','oembed'],'/',
								['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent','-','Blockquote','CreateDiv']
							]";
				$height = 200;
				$maxHeight = 400;
				break;
			case 'full':
				$toolbar = "[
								['Source'],['PasteText','PasteFromWord','-','Templates'],['Undo', 'Redo'],['Find','Replace','Scayt'],['ShowBlocks', 'Maximize','Preview'],'/',
								['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript','-','RemoveFormat'],['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],['Image','Table','-','Link','Unlink', 'Anchor','-', 'HorizontalRule', 'Flash','Iframe',  'Smiley', 'SpecialChar','-','oembed'],'/',
								['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent','-','Blockquote','CreateDiv']
							]";
				$height = 200;
				$maxHeight = 400;
				break;
			case 'standard':
				$toolbar = "[	
								['Source'],['PasteText','PasteFromWord','-','Templates'],['Undo', 'Redo'],['Find','Replace','Scayt'],['Image','Table','Link', 'Anchor', 'HorizontalRule','MediaEmbed'],['ShowBlocks', 'Maximize','Preview'],'/',
								['Styles', 'Font', 'FontSize'],['Bold', 'Italic','Strike', '-','RemoveFormat'],['TextColor', 'BGColor'],['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent']
							 ]";
				$height = 200;
				$maxHeight = 400;
				break;
			case 'summary':
				$toolbar = "[['Source'],['Format','Font', 'FontSize'],['Bold', 'Italic'],['TextColor', 'BGColor'],['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],['Image','Table','Link']]";
				$height = 100;
				$maxHeight = 150;
				break;
			case 'user':
				$toolbar = "[['Source','Preview','ShowBlocks'],['Format','Font', 'FontSize'],['Bold', 'Italic'],['TextColor', 'BGColor'],['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],['Link', 'Anchor']]";
				$height = 200;
				$maxHeight = 400;
				break;
		}
		
		$attributes = $this->Form->_initInputField($fieldName, array());
		
        return $this->Html->scriptBlock("
	        var editor = CKEDITOR.replace( '{$attributes['id']}',{
				language: 'vi',
				height:$height,
				uiColor: '#f6f6f6',
				extraPlugins : 'pbckcode,scayt,wordcount,htmlbuttons,autogrow,oembed,mediaembed',
				autoGrow_maxHeight : $maxHeight,
//				contentsCss: '../css/ckeditor.css',
				removePlugins : 'resize',
				enterMode : CKEDITOR.ENTER_P,
				shiftEnterMode : CKEDITOR.ENTER_DIV,
				toolbar : $toolbar,
				extraAllowedContent: 'pre[*](*)',		//[attr]{css}(class)
				pbckcode:{
					modes : [ ['PHP', 'php'],['HTML','html'],['CSS','css'],['JS','javascript'],['SQL','sql'],['XML','xml'],['JSON','json']],
					theme : 'chrome',
					highlighter : 'SYNTAX_HIGHLIGHTER'
				},
				wordcount:{
					showWordCount: true,
					showCharCount: true
				}
			});
	        CKFinder.setupCKEditor( editor, '{$this->webroot}js/ckfinder/' ) ;	
        ");
    }
    
    /**
     * @Description : Upload
     *
     * @Author 	: Hoang Tuan Anh - tuananh@url.vn
     */
    function upload(){
    	
    }
}
?>