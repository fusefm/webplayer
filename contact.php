	<script type="text/javascript" src="js/functionAddEvent.js"></script>
	<script type="text/javascript" src="js/contact.js"></script>
	<script type="text/javascript" src="js/xmlHttp.js"></script>

	<p id="loadBar" style="display:none;">
		<strong>I'm just sending your message, won't be a sec...</strong>
		<img src="img/loading.gif" alt="Loading..." title="Sending Email" />
	</p>
	<p id="emailSuccess" style="display:none;">
		<strong>Success! Your message has been sent to the studio...</strong>
	</p>
	<div id="contactFormArea">
		<form action="scripts/contact.php" method="post" id="cForm">
			
				<table cellpadding="0" cellspacing="2" align="center"><tr><td align="right"><label for="posName">Your name: </label></td><td>
				<input class="text" type="text" size="25" name="posName" id="posName" /></td></tr><tr><td align="right">
				<label for="posEmail">Your e-mail address: </label></td><td>
				<input class="text" type="text" size="25" name="posEmail" id="posEmail" /></td></tr><tr><td align="right">
				<label for="posRegard">Subject: </label></td><td>
				<input class="text" type="text" size="25" name="posRegard" id="posRegard" /></td></tr></table><br />
				<label for="posText">Message:</label>
				<textarea cols="40" rows="5" name="posText" id="posText"></textarea><br /><br />
				<label for="selfCC">
					<input type="hidden" name="selfCC" id="selfCC" value="nosend" />
				</label>
				<label>
					<input class="submit" type="submit" name="sendContactEmail" id="sendContactEmail" value="Send Message" />
				</label>
			
		</form>
	</div>