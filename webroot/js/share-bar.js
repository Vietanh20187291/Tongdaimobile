	var act = new gigya.socialize.UserAction();
	//act.setUserMessage("This is the user message");
	//act.setTitle("Seasons");
	//act.setLinkBack("https://platform.gigya.com");
	//act.setDescription("Seasons is a surreal motion graphics animation based on the changing seasons.");
	//act.addActionLink("Watch this movie", "http://vimeo.com/24496773");
	//act.addMediaItem({ type: 'image', src: 'http://b.vimeocdn.com/ts/160/564/160564685_100.jpg', href: 'http://vimeo.com/24496773' });
	var showShareBarUI_params=
	{ 
    containerID: 'intrepid-sharebar-affixed',
	layout:'vertical',
	showCounts:'top',
	  shareButtons:
        [
			{ // Twitter Tweet button
                provider:'twitter',
                tooltip:'Share on Twitter',
                defaultText: 'Twitter message',
                url: 'https://twitter.com/impresstravel'
                 
            },
			{ // facebook button
                provider:'facebook-like', //facebook or facebook-like
                tooltip:'facebook this',
				action:'like',  		//recommend or like
                font:'arial',
                url: 'https://www.facebook.com/Impress-Travel-557952310918259/'
            },
            { // Google +1 button
                provider:'google-plusone',
                tooltip:'Recommend this on Google',
                userMessage:'default user message',
                url: 'https://plus.google.com/117154961644045739868/about'
            },
			//{ // General Share Button
              //  provider:'share',
               // tooltip:'General Share Button',
               // userMessage:'default user message'
            //},
			{ // Pinterest button
            	provider: 'pinterest-pinit',
                tooltip:'Share on Pinterest',
                defaultText: 'default user message',
                url:'https://www.pinterest.com/impresstravel/'
            },
            { // Email button
                provider:'email',
                tooltip:'Email this'
            }
            
        ],
    //shareButtons:'twitter,facebook,stumbleupon,google,share,email', // list of providers
    userAction: act
	}
	gigya.socialize.showShareBarUI(showShareBarUI_params);
