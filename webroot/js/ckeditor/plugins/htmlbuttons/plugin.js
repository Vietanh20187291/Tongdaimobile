/*
 * @file HTML Buttons plugin for CKEditor
 * Copyright (C) 2012 Alfonso Mart�nez de Lizarrondo
 * A simple plugin to help create custom buttons to insert HTML blocks
 */

CKEDITOR.plugins.add( 'htmlbuttons',
{
	init : function( editor )
	{
		var buttonsConfig = editor.config.htmlbuttons;
		if (!buttonsConfig)
			return;

		function createCommand( definition )
		{
			return {
				exec: function( editor ) {
					editor.insertHtml( definition.html );
				}
			};
		}

		// Create the command for each button
		for(var i=0; i<buttonsConfig.length; i++)
		{
			var button = buttonsConfig[ i ];
			var commandName = button.name;
			editor.addCommand( commandName, createCommand(button, editor) );

			editor.ui.addButton( commandName,
			{
				label : button.title,
				command : commandName,
				icon : this.path + button.icon
			});
		}
	} //Init

} );

/**
 * An array of buttons to add to the toolbar.
 * Each button is an object with these properties:
 *	name: The name of the command and the button (the one to use in the toolbar configuration)
 *	icon: The icon to use. Place them in the plugin folder
 *	html: The HTML to insert when the user clicks the button
 *	title: Title that appears while hovering the button
 *
 * Default configuration with some sample buttons:
 */
CKEDITOR.config.htmlbuttons =  [
	{
		name:'button1',
		icon:'icon1.png',
		html:'<p class="button_run"><a href="http://w3c.com.vn/thuc-hanh?id=" target="_blank" title="Thực hành" rel="nofollow">Thực hành</a></p>',
		title:'Thực hành'
	},
	{
		name:'button2',
		icon:'icon2.png',
		html:'<table border="1" cellpadding="1" cellspacing="1" style="width:675px"><tbody><tr><td style="background-color: rgb(102, 102, 102); width: 150px;"><strong><span style="color:#FFFFFF">Giá trị</span></strong></td><td style="background-color: rgb(102, 102, 102);"><strong><span style="color:#FFFFFF">Mô tả</span></strong></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table>',
		title:'Bảng thuộc tính'
	},
	{
		name:'button3',
		icon:'icon3.png',
		html:'<span style="font-size:14px"><span style="color:rgb(178, 34, 34)"><strong>Tiêu đề</strong></span></span>',
		title:'Tiêu đề'
	}
];