<?php
echo<<<eot
<div id="contact" class="contact">
		<div class="container">
			<h3>$lang_contact</h3>
			<div class="contact-bottom">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2564.958900464012!2d36.23097800000001!3d49.993379999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4127a0f009ab9f07%3A0xa21e10f67fa29ce!2sGeorgia+Education+Center!5e0!3m2!1sen!2sin!4v1436943860334" frameborder="0" style="border:0" allowfullscreen></iframe>
			</div>
			<div class="col-md-4 contact-left">
				<h4>$lang_address</h4>
				<p>$config_address</p>
				<ul>
					<li>$lang_phone: $config_hotline</li>
					<li>$lang_fax: $config_fax</li>
					<li>$lang_email: <a href="mailto:$config_email">$config_email </a></li>
				</ul>
			</div>
			<div class="col-md-8 contact-left">
				<h4>$lang_contactform</h4>
				<form id="contactform" action="includes/contact.php">
					<input type="text" name='name' placeholder="$lang_name" required="">
					<input type="email" name='email' placeholder="$lang_email" required="">
					<input type="text" name='phone' placeholder="$lang_phone" required="">
					<textarea name='message' placeholder="$lang_message" required=""></textarea>
					<input name='submit' type="submit" value="$lang_submit" >
					<input name='Clear' type="reset" value="$lang_clear" >
				</form>
				<div id='submit'></div>
			</div>
			
			<div class="clearfix"> </div>
			
		</div>
	</div>
	<script language="javascript">
		$('#contactform').ajaxForm({
			target: '#submit', 
			beforeSubmit: function(){
				$('#submit').show().html('<span class="badge badge-warning">Làm ơn chờ...</span>'); 
			},
			success: function() { 
				;
			}
		}); 
	</script>
eot;
?>