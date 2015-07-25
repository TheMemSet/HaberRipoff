"use strict";
	$(document).ready(function() {
		var latestMessageId = 0;
		fetchNewMessages();
		setInterval(fetchNewMessages, 1000);
		var msgs = [];
		var username = "", password = "", token = "";
		$("#logoutbtn").hide();

		var Message = function(text, id) {
			this.text = text;
			this.id = id;
			return this;
		}

		$("#textbox").keypress(function(e) {
			if (e.which === 13) {
				if ($("#textbox").val() != "") {
					var message = $("#textbox").val();
					$("#textbox").val("");
					$.post("php/sendmessage.php", {message: message, token: token}, function(response) {
						if (response == "INVALID_TOKEN") {
							alert("Please log in.");
							token = "";
							$("#textbox").val(message);
							if (!$("#logoutbtn").is(":hidden"))
								$("#loginarea").children().toggle(1000);
						}
					});
				}
				return false;
			}
		});

		function fetchNewMessages() {
			$.post("php/fetchmessages.php", {latest: latestMessageId}, function(response) {
				try {
					response = $.parseJSON(response);
				} catch(e) {
					return;
				}
				for (var i = 0; i < response.length; i++) {
					response[i].id = parseInt(response[i].id);
					if (response[i].id > latestMessageId) 
						latestMessageId = response[i].id;
					
					$("#messagearea").append("<p>" + response[i].text + "</p>");
					//msgs.push(new Message(response[i].text, response[i].id));
				}
			});
		}

		$("#loginbtn").click(function() {
			if ($("#usernamebox").val() == "" || $("#passwordbox").val() == "") {
				alert("Please enter a valid username and password");
				return false;
			} else
				$.post("php/login.php", {username: $("#usernamebox").val(), password: $("#passwordbox").val()}, function(response) {
					try {
						response = $.parseJSON(response);
					} catch(e) {
						alert("Server issues. Please try again.");
						return;
					}
					if (response.text == "INVALID_LOGIN") {
						alert("Please enter a valid username and password.");
						$("#usernamebox, #passwordbox").val("");
						return;
					}
					else if (response.text == "LOGIN_SUCCESSFUL") {
						token = response.token;
						$("#usernamebox, #passwordbox").val("");
						$("#loginarea").children().toggle(1000);
					} 
				});
		});

		$("#logoutbtn").click(function() {
			$.post("php/logout.php", {token: token}, function(response) {
				if (response == "LOGOUT_UNSUCCESSFUL") 
					alert("Could not log out.");
				else {
					$("#loginarea").children().toggle(1000);
					$("#usernamebox, #passwordbox").val("");
				}
			});
		});

		$("#registerbtn").click(function() {
			$.post("php/register.php", {username: $("#usernamebox").val(), password: $("#passwordbox").val()}, function(response) {
				$("#usernamebox, #passwordbox").val("");
				if (response == "REGISTRATION_SUCCESSFUL") 
					alert("Registration successful, you may now log in");
				else if (response == "USERNAME_TAKEN")
					alert("That username is taken, please try another one");
				else if (response == "INVALID_CREDENTIALS")
					alert("There's something wrong with your username or password, please try again");
			});
		});
	});