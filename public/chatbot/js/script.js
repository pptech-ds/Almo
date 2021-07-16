// on input/text enter--------------------------------------------------------------------------------------
$('.usrInput').on('keyup keypress', function (e) {
	var keyCode = e.keyCode || e.which;
	var text = $(".usrInput").val();
	if (keyCode === 13) {
		if (text == "" || $.trim(text) == '') {
			e.preventDefault();
			return false;
		} else {
			$(".usrInput").blur();
			setUserResponse(text);
			send(text);
			e.preventDefault();
			return false;
		}
	}
});


function getFocusChatField() {
	document.getElementById("keypad").focus();
}


function showHideChatBot() {
	var x = document.getElementById("chatbotShowHide");
	if (x.style.display === "none") {
	  x.style.display = "block";
	} else {
	  x.style.display = "none";
	}
}



//------------------------------------- Set user response------------------------------------
function setUserResponse(val) {
	var UserResponse = '<img class="userAvatar" src=' + "chatbot/img/userAvatar.jpg" + '><p class="userMsg">' + val + ' </p><div class="clearfix"></div>';
	$(UserResponse).appendTo('.chats').show('slow');
	$(".usrInput").val('');
	scrollToBottomOfResults();
	$('.suggestions').remove();
	getFocusChatField();
}

//---------------------------------- Scroll to the bottom of the chats-------------------------------
function scrollToBottomOfResults() {
	var terminalResultsDiv = document.getElementById('chats');
	terminalResultsDiv.scrollTop = terminalResultsDiv.scrollHeight;
}

function send(message) {
	console.log("User Message:", message)
	$.ajax({
		url: 'http://localhost:5005/webhooks/rest/webhook',
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify({
			"message": message,
			"sender": "Me"
		}),
		success: function (data, textStatus) {
			if(data != null){
					setBotResponse(data);
			}
			console.log("Rasa Response: ", data, "\n Status:", textStatus)
		},
		error: function (errorMessage) {
			setBotResponse("");
			console.log('Error' + errorMessage);

		}
	});
}

function isValidUrl(string) {
	try {
		new URL(string);
	} catch (_) {
		return false;  
	}

	return true;
}

function formatHyperlinkText(string) {
	if(string.includes('\"')) {
		var FinalMsg = [];
		var StringArray = string.split('\"');
		// console.log("formatHyperlinkText::StringArray: ",StringArray)
		var BotMsg = StringArray[1]
		// console.log("formatHyperlinkText::BotMsg: ",BotMsg);
		var CleanHyperlinks = []
		var LinkName = []
		var LinkUrl = []
		var i;

		for (i = 3; i < StringArray.length; i++) {
			if(i%2==1) {
			CleanHyperlinks.push(StringArray[i]);
			}
		}
		// console.log("formatHyperlinkText::CleanHyperlinks: ",CleanHyperlinks);

		
		for (i = 0; i < CleanHyperlinks.length; i++) {
			if(i%2==0) {
				LinkName.push(CleanHyperlinks[i]);
			}
			else {
				LinkUrl.push(CleanHyperlinks[i]);
			}
		}
		// console.log("formatHyperlinkText::LinkName: ",LinkName);
		// console.log("formatHyperlinkText::LinkUrl: ",LinkUrl);

		FinalMsg.push(BotMsg);
		// <a href="url">link text</a>
		for (i = 0; i < LinkName.length; i++) {
			// HyperLinks.push('<a href=\"' + LinkUrl[i] + '\">' + LinkName[i] + '</a>')
			var CurrentHyperLinks = '<a href=\"' + LinkUrl[i] + '\">' + LinkName[i] + '</a>';
			FinalMsg.push(CurrentHyperLinks)
		}

		// console.log("formatHyperlinkText::FinalMsg: ",FinalMsg);
		return FinalMsg;
	}
	else {
		return string;
	}

	
}

//------------------------------------ Set bot response -------------------------------------
function setBotResponse(val) {
	setTimeout(function () {
		if (val.length < 1) {
			//if there is no response from Rasa
			msg = 'Désolé, je ne comprends pas ta demande, merci de bien vouloir reformuler.';

			var BotResponse = '<img class="botAvatar" src="chatbot/img/botAvatar.png"><p class="botMsg">' + msg + '</p><div class="clearfix"></div>';
			$(BotResponse).appendTo('.chats').hide().fadeIn(1000);
			getFocusChatField();

		} else {
			//if we get response from Rasa
			for (i = 0; i < val.length; i++) {
				//check if there is text message
				if (val[i].hasOwnProperty("text")) {
					var CurrentRasaResponse = formatHyperlinkText(val[i].text);

					if(i>0){
						var j;
						for (j = 0; j < CurrentRasaResponse.length; j++) {
							// console.log("setBotResponse::formatHyperlinkText(val[i].text).length: ",formatHyperlinkText(val[i].text).length);
							if(j==0){
								var BotResponse = '<img class="botAvatar" src="chatbot/img/botAvatar.png">' + CurrentRasaResponse[j] + '</p><div class="clearfix"></div>';
								$(BotResponse).appendTo('.chats').hide().fadeIn(1000);
								getFocusChatField();
							}
							else {
								var BotResponse = '<p> &nbsp; &nbsp; &nbsp; *' + CurrentRasaResponse[j] + '</p><div class="clearfix"></div>';
								$(BotResponse).appendTo('.chats').hide().fadeIn(1000);
								getFocusChatField();
							}
						}
					}
					else {
						var BotResponse = '<img class="botAvatar" src="chatbot/img/botAvatar.png">' + CurrentRasaResponse + '</p><div class="clearfix"></div>';
						$(BotResponse).appendTo('.chats').hide().fadeIn(1000);
					}
					
				}

				//check if there is image
				if (val[i].hasOwnProperty("image")) {
					var BotResponse = '<div class="singleCard">' +
						'<img class="imgcard" src="' + val[i].image + '">' +
						'</div><div class="clearfix">'
					$(BotResponse).appendTo('.chats').hide().fadeIn(1000);
					getFocusChatField();
				}

				//check if there is  button message
				if (val[i].hasOwnProperty("buttons")) {
					addSuggestion(val[i].buttons);
				}


			}
			scrollToBottomOfResults();
		}

	}, 500);
}


// ------------------------------------------ Toggle chatbot -----------------------------------------------
$('#profile_div').click(function () {
	$('.profile_div').toggle();
	$('.widget').toggle();
	scrollToBottomOfResults();
});

$('#close').click(function () {
	$('.profile_div').toggle();
	$('.widget').toggle();
});

