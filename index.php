<?php
if($_GET['id']) { $id = $_GET['id']; } else { $id = 0; }
$url = "data/" . $id . ".xml";
if (file_exists($url)) {

$xml = simplexml_load_file($url);

$locale = $xml->settings[0]->locale;
$currency = $locale;

if (isSet($_GET["locale"])) $locale = $_GET["locale"];
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
setlocale(LC_MONETARY, $currency);
bindtextdomain("messages", "./locale");
textdomain("messages");

$tax = $xml->settings[0]->tax;
$discount = $xml->settings[0]->discount;
$subtotal = 0;
$total = 0;

require('assets/functions.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="<?php echo $_GET['id']; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $xml->client[0]->name . " - AXIS - " . _('Estimate') . " #" . $xml->settings[0]->id; ?></title>
<link href='http://fonts.googleapis.com/css?family=Istok+Web:400,700,400italic' rel='stylesheet' type='text/css'>
<link href="assets/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<link href="assets/css/globals.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="assets/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="assets/js/functions.js?<?php echo time(); ?>"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>

</head>

<body>

<div class="container">

<?php if (($xml->settings[0]->auth == "") || ($xml->settings[0]->auth == $_GET["auth"])) { ?>
    
    <div class="buttons">
        <div class="contain">
            <div class="minilogo"></div>
            <?php if($xml->attributes()->accepted == "true") { ?>
            <div class="btn btn-success btn-large disabled" id="accepted" rel="tooltipRight" title="<?php echo _("We're all set!"); ?>"><i class="icon-ok icon-white"></i> <?php echo _("Estimate Accepted"); ?></div>
			<?php } else { ?>
            <div class="btn btn-success btn-large" id="accept" rel="tooltipRight" title="<?php echo _("Looking good, right?"); ?>"><i class="icon-ok icon-white"></i> <?php echo _("Accept Estimate"); ?></div>
			<?php } ?>
            <div class="btn btn-info btn-right" id="questions" rel="tooltipBottom" title="<?php echo _("Get in touch!"); ?>"><i class="icon-comment icon-white"></i> <?php echo _('Questions?'); ?></div>
            <?php if($xml->attributes()->pdf != "false") { ?>
            <a href="pdf/<?php echo $id; if($xml->settings[0]->auth != "") echo "-" . $xml->settings[0]->auth; ?>.pdf" class="btn btn-right" rel="tooltipBottom" title="<?php echo _("Download Estimate"); ?>" target="_blank"><i class="icon-file"></i> PDF</a>
            <?php } ?>
        </div>
    </div>
    
    <div class="page">
        
        <section class="header">
            <div class="logo">
                <img src="assets/img/logo.png" />
            </div>
            
            <div class="eduardonunes">
                <h1>Eduardo Nunes</h1>
                <p><a href="http://www.eduardonunes.me">www.eduardonunes.me</a></p>
                <p><a href="mailto:hi@eduardonunes.me">hi@eduardonunes.me</a></p>
                <p>(+351) 93 480 81 91</p>
                <p>NIF 247377031</p>
            </div>
            
            <div class="push"></div>
        </section>
        
        <section class="client">
            <div class="details">
                <?php plainEcho('<h2>', $xml->settings[0]->title, '</h2>'); ?>
                <?php plainEcho('<p>' . _('Estimate') .' #', $xml->settings[0]->id, '</p>'); ?>
                <?php plainEcho('<p>' . _('Created on') .' ', $xml->settings[0]->emissiondate, '</p>'); ?>
            </div>
            
            <div class="clientdetails">
                <?php plainEcho('<h1>', $xml->client[0]->name, '</h1>'); ?>
                <?php plainEcho('<p>', $xml->client[0]->address, '</p>'); ?>
                <?php plainEcho('<p>', $xml->client[0]->subaddress, '</p>'); ?>
                <?php plainEcho('<p>', $xml->client[0]->phone, '</p>'); ?>
                <?php plainEcho('<p>', $xml->client[0]->email, '</p>'); ?>
            </div>
            
            <div class="push"></div>
        </section>
        
        <section class="money">
        
            <table width="100%" class="estimatetotals" cellpadding="0" cellspacing="0">
              <thead>
              <tr class="row">
                <th scope="col"><?php echo _('Type'); ?></th>
                <th scope="col"><?php echo _('Description'); ?></th>
                <th scope="col" class="alignRight"><?php echo _('Rate'); ?></th>
                <th scope="col" class="alignRight"><?php echo _('Hours'); ?></th>
                <th scope="col" class="alignRight"><?php echo _('Value'); ?></th>
              </tr>
              </thead>
              <?php
				$count = count($xml->items->item);
				for ($i = 0; $i < $count; $i++) {
					$value = $xml->items->item[$i]->rate * $xml->items->item[$i]->hours;
					$subtotal += $value;
			  ?>
              <tr class="row">
                <td><?php plainEcho('', $xml->items->item[$i]->type, ''); ?></td>
              <?php if($xml->items->item[$i]->explanation != "") { ?>
                <td><a href="#" rel="popover" data-content="<?php plainEcho('', $xml->items->item[$i]->explanation, ''); ?>" data-original-title="<?php echo _("What's included?"); ?>"><?php plainEcho('', $xml->items->item[$i]->description, ''); ?></a></td>
			  <?php } else { ?>
              <td><?php plainEcho('', $xml->items->item[$i]->description, ''); ?></td>
			  <?php } ?>
                <td class="alignRight"><?php plainEcho('', $xml->items->item[$i]->rate, ''); ?></td>
                <td class="alignRight"><?php plainEcho('', $xml->items->item[$i]->hours, ''); ?></td>
                <td class="alignRight"><?php moneyEcho($value, $locale); ?></td>
              </tr>
              <?php } ?>
            </table>
            
            <table width="40%" class="estimatetotals" cellpadding="0" cellspacing="0">
              <tr class="row">
                <td><?php
                	echo _('Subtotal');
				if($discount != "") { 
					echo  "<br />" . _('Discount') . " (" . $discount . "%)";
					echo  "<br /><br/>" . _('Untaxed Total');
				}
				if($tax != "") { 
					echo "<br />" . _('Tax') . " (" . $xml->settings[0]->taxname . ")";
				} ?></td>
                <td class="alignRight">
				<?php
				moneyEcho($subtotal, $locale);
				if($discount) {
					echo "<br/>";
					$discount = $subtotal/100*$discount;
					echo "-"; moneyEcho($discount, $locale);
					$subtotal -= $discount;
					echo "<br/><br/>";
					moneyEcho($subtotal, $locale);
				}
				if($tax) {
					echo "<br/>";
					$tax = $subtotal/100*$tax;
					moneyEcho($tax, $locale);
					$total = $subtotal + $tax;
				} else {
					$total = $subtotal;
				} ?></td>
              </tr>
              <tr class="row total">
                <td><?php echo _('Total'); ?></td>
                <td class="alignRight"><?php moneyEcho($total, $locale);?></td>
              </tr>
            </table>
            
            <div class="push"></div>
        </section>
        
        <div class="push"></div>
        
        <section class="footer">
        	<div class="well">
            	Eduardo Nunes, Cross-Media Designer
                <a class="pull-right" href="#" id="termsAndStuff"><?php echo _('Terms and Conditions'); ?></a>
            </div>
            <div class="push"></div>
        </section>
	
    </div>
        
</div>


<!-- ESTIMATE ACCEPT CONFIRMATION -->
<div class="modal hide fade" id="acceptModal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal" >&times;</a>
    <h3><?php echo _('Awesome!'); ?></h3>
  </div>
  <div class="modal-body" id="acceptBox">
    <h4><?php echo _("Looks like you're happy with the estimate - great!"); ?></h4>
    <p><?php echo _("Just one last tick in the box below, and I'll be ready to get working immediately."); ?></p>
    <hr>
	<div class="alert hide" id="noTick">
		<?php echo _("You should really tick this box first..."); ?>
	</div>
	<label class="checkbox"><input type="checkbox" id="checkIt"> <?php printf(gettext("I confirm that %s + taxes sounds fair"), utf8_encode(money_format('%.2n', $subtotal))); ?></label>
  </div>
  <div class="modal-body hide" id="acceptedBox">
		<div class="alert alert-success" id="welldone">
			<h4><?php echo _("You did the right thing!"); ?></h4>
			<p><?php echo _("I'll get back to you really soon, so we can take care of the final details, before I get to work. Thank you for your business!"); ?></p>
		</div>
    </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal" id="noCanDo"><?php echo _("Nevermind"); ?></a>
    <a href="#" class="btn btn-success" id="goodToGo"><?php echo _("Yes, we're good to go!"); ?></a>
      <a href="#" class="btn hide" data-dismiss="modal" id="closeAccept"><?php echo _("Close"); ?></a>
  </div>
</div>



<!-- SOMETHING'S WRONG! -->
<div id="getInTouch" class="modal fade hide">

    <div class="modal-header">
      <a class="close" data-dismiss="modal" >&times;</a>
      <h3><?php echo _("What's troubling you?"); ?></h3>
    </div>
    <div class="modal-body stepone">
    	<ul class="nav nav-pills nav-stacked">
			<li><a href="#" data-target="#tooMuch"><?php echo _("I was expecting to pay less for your services"); ?></a></li>
	        <li><a href="#" data-target="#tooLate"><?php echo _("I was hoping this could be ready sooner than that"); ?></a></li>
	        <li><a href="#" data-target="#notAgreed"><?php echo _("The items described are incomplete, or are not the ones we discussed earlier"); ?></a></li>
	        <li><a href="#" data-target="#otherStuff"><?php echo _("I want to discuss something else with you..."); ?></a></li>
        </ul>
        
        <!-- Too Much -->
        <div class="hide contextual" id="tooMuch">
	        <p><?php echo _("I'm sorry to know that, but I'm sure we can work something out. Let's start right here..."); ?></p>
			<hr>
            <form class="form-horizontal">
              <fieldset>
                <!-- HIDDEN -->
              	<input type="hidden" name="estimateId" value="<?php plainEcho('', $xml->settings[0]->id, ''); ?>">
                
                <div class="control-group">
                  <label class="control-label" for="input01"><?php echo _("Deadline"); ?></label>
                  <div class="controls">
	                  <p><?php echo _("Would you be able to move the deadline further away, if the price went down a notch?"); ?></p>
					  <label class="radio">
                        <input type="radio" name="deadline" id="deadlineRadios1" value="yes" checked class="inputData">
                        <?php echo _("Deffinitely, this is not urgent"); ?>
                      </label>
                      <label class="radio">
                        <input type="radio" name="deadline" id="deadlineRadios2" value="maybe" class="inputData">
                        <?php echo _("Yes, but no more than a couple of weeks"); ?>
                      </label>
                      <label class="radio">
                        <input type="radio" name="deadline" id="deadlineRadios3" value="no" class="inputData">
                        <?php echo _("Not really, it's kind of urgent"); ?>
                      </label>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><?php echo _("Contacts"); ?></label>
                  <div class="controls">
                    <p><?php echo _("How should I get back to you?"); ?></p>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="email" value="emailCheck" class="checkbox inline"> <?php echo _("By e-mail"); ?></label> <input type="text" class="span2" name="emailAddress" value="<?php plainEcho('', $xml->client[0]->email, ''); ?>">
                    </div>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="phone" value="phoneCheck" class="checkbox inline"> <?php echo _("By phone"); ?></label> <input type="text" class="span2" name="phoneNo" value="<?php plainEcho('', $xml->client[0]->phone, ''); ?>">
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
        
        <!-- Too Late -->
        <div class="hide contextual" id="tooLate">
	        <p><?php echo _("I see you're in a hurry, so I won't take much of your time..."); ?></p>
			<hr>
            <form class="form-horizontal">
              <fieldset>
                <!-- HIDDEN -->
              	<input type="hidden" name="estimateId" value="<?php plainEcho('', $xml->settings[0]->id, ''); ?>">
                
                <div class="control-group">
                  <label class="control-label" for="thisSoon"><?php echo _("When?"); ?></label>
                  <div class="controls">
                    <input type="text" class="input-xlarge" name="thisSoon" placeholder="<?php echo _("When exactly do you need this?"); ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><?php echo _("Contacts"); ?></label>
                  <div class="controls">
                    <p><?php echo _("How should I get back to you?"); ?></p>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="email" value="emailCheck" class="checkbox inline"> <?php echo _("By e-mail"); ?></label> <input type="text" class="span2" name="emailAddress" value="<?php plainEcho('', $xml->client[0]->email, ''); ?>">
                    </div>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="phone" value="phoneCheck" class="checkbox inline"> <?php echo _("By phone"); ?></label> <input type="text" class="span2" name="phoneNo" value="<?php plainEcho('', $xml->client[0]->phone, ''); ?>">
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
        
        <!-- Not Agreed -->
        <div class="hide contextual" id="notAgreed">
	        <p><?php echo _("My bad! Would you care to explain what's wrong, so I can fix it ASAP?"); ?></p>
			<hr>
            <form class="form-horizontal">
              <fieldset>
                <!-- HIDDEN -->
              	<input type="hidden" name="estimateId" value="<?php plainEcho('', $xml->settings[0]->id, ''); ?>">

                <div class="control-group">
                  <label class="control-label" for="thisSoon"><?php echo _("What's wrong?"); ?></label>
                  <div class="controls">
                    <textarea class="input-xlarge" name="thisIsWrong" rows="4"></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><?php echo _("Contacts"); ?></label>
                  <div class="controls">
                    <p><?php echo _("How should I get back to you?"); ?></p>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="email" value="emailCheck" class="checkbox inline"> <?php echo _("By e-mail"); ?></label> <input type="text" class="span2" name="emailAddress" value="<?php plainEcho('', $xml->client[0]->email, ''); ?>">
                    </div>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="phone" value="phoneCheck" class="checkbox inline"> <?php echo _("By phone"); ?></label> <input type="text" class="span2" name="phoneNo" value="<?php plainEcho('', $xml->client[0]->phone, ''); ?>">
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
        
        <!-- Other Stuff -->
        <div class="hide contextual" id="otherStuff">
	        <p><?php echo _("I see there's something you want to discuss... I'm all ears!"); ?></p>
			<hr>
            <form class="form-horizontal">
              <fieldset>
                <!-- HIDDEN -->
              	<input type="hidden" name="estimateId" value="<?php plainEcho('', $xml->settings[0]->id, ''); ?>">

                <div class="control-group">
                  <label class="control-label" for="thisSoon"><?php echo _("What's wrong?"); ?></label>
                  <div class="controls">
                    <textarea class="input-xlarge" name="thisIsWrong" rows="4"></textarea>
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label"><?php echo _("Contacts"); ?></label>
                  <div class="controls">
                    <p><?php echo _("Do you want me to get back to you?"); ?></p>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="email" value="emailCheck" class="checkbox inline"> <?php echo _("By e-mail"); ?></label> <input type="text" class="span2" name="emailAddress" value="<?php plainEcho('', $xml->client[0]->email, ''); ?>">
                    </div>
                    <div class="checkbox-item">
                    	<label class="checkbox inline"><input type="checkbox" name="phone" value="phoneCheck" class="checkbox inline"> <?php echo _("By phone"); ?></label> <input type="text" class="span2" name="phoneNo" value="<?php plainEcho('', $xml->client[0]->phone, ''); ?>">
                    </div>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
        
        <div class="alert alert-success hide" id="welldone">
          <h4 class="alert-heading"><?php echo _("All right!"); ?></h4>
		  <?php echo _("Your message got here! I'll get in touch as soon as possible."); ?>
        </div>
        
        <div class="alert alert-error hide" id="notcool">
          <h4 class="alert-heading"><?php echo _("Uh-oh!"); ?></h4>
		  <?php echo _("Something went wrong, and your message did not get here. Fortunately, everything (including this error) was logged, and is safely stored in my server. There's a slight chance I miss it, so please try again if I do not get back to you in the next 48 hours. Thank you!"); ?>
        </div>
    </div>
    <div class="modal-footer">
	  <a href="#" class="btn pull-left hide" id="backToOne"><?php echo _("Start Over"); ?></a>
      <a href="#" class="btn" data-dismiss="modal" id="closeContacts"><?php echo _("Close"); ?></a>
	  <a href="#" class="btn btn-success hide" id="sendItIn"><?php echo _("Send"); ?></a>
    </div>
</div>
    
<!-- TERMS AND CONDITIONS -->
<div class="modal hide fade" id="termsAndConditions">
  <div class="modal-header">
    <a class="close" data-dismiss="modal" >&times;</a>
    <h3><?php echo _('Terms and Conditions'); ?></h3>
  </div>
  <div class="modal-body" id="someTos">
    <p><?php echo _("Terms and conditions are neccessary, but no fun. That's why I've made them easier for you. Here's the scoop:"); ?></p>
    <h4><?php echo _("Quote and Estimates"); ?></h4>
    <p><?php echo _("All values are based on projected hours, and final hours will be tracked using a neat little web service. You will never be charged more than what's listed right here, unless you eventually go berzerk and want a lot more stuff done, in which case I'll be happy to roll out a new estimate :)"); ?></p>
    <h4><?php echo _("Money Stuff"); ?></h4>
    <p><?php echo _("I need you to pay 25&#37; of the total amount before I get to work. It's only fair - most people will charge you 50&#37; upfront. You can pay by credit card through PayPal, or by bank transfer."); ?></p>
    <h4><?php echo _("Other Stuff"); ?></h4>
    <p><?php echo _("We can make a written agreement, with additional terms, before we start working, so we're both at peace. Be aware that by accepting the estimate, you're agreeing with this legal stuff."); ?></p>
  </div>
  <div class="modal-body hide" id="allTos">
  	
    <!-- REPLACE THE FOLLOWING TEXT
    WITH YOUR OWN TERMS & CONDITIONS -->
    
    <h4>GENERAL</h4>
    <ol>
	    <li>The present terms and conditions define all clauses referring to a service provided by Eduardo Nunes, upon a client's request, including, but not limited to, the project's scope of work, as well as values estimated based on a projection of hours indispensable for the service completion, multiplied by their fixed hourly rates.</li>
		<li>All estimates are presented obligation-free, and these terms and conditions are activated only by the customer's acceptance of the final estimate.</li>
	</ol>
    <h4>QUOTING</h4>
    <ol>
	    <li>Values presented for all services are defined based on fixed hourly rates. Estimates for each service are based on a projection of all billable work hours indispensable to the project's completion, excluding project management, accounting and communications, unimputable to the final client.</li>
		<li>Whenever products or services are provided by business partners, and not by Eduardo Nunes, the estimate will clearly state the name of the original provider. The customer is entitled to suggest a different provider, whenever the values presented are non-satisfactory. Eduardo Nunes reserves the right to reject providers if for some reason their service compromises the quality of the final work.</li>
		<li>All estimates include a period of no more than 10% of the total billable hours for client revisions and project iterations, according to the client's instructions.</li>
Final billed value will be calculated according to the total work hours tracked through the web-based time tracking tool Toggl (or one of similar nature). The provider may, at any time, proccess a new estimate, whenever hours tracked are expected to exceed the original estimate.</li>
	</ol>
    <h4>PAYMENT</h4>
    <ol>
	    <li>Final payment method should be agreed on between Eduardo Nunes and the final client. Credit card (via PayPal), bank transfer and money order are accepted.</li>
		<li>The client agrees to pay a 25% deposit of the quoted amount upon approval of the estimate, with the balance payable prior to delivery.</li>
		<li>Once research, resources allocated or design work has commenced on a project, this deposit is non-refundable. In the event of a withdrawal of the project by the client, this deposit is non-refundable.</li>
	</ol>
    <h4>TERMS OF SERVICE</h4>
    <ol>
	    <li>Additional terms of service may be presented to the client upon acceptance of the estimate. Whenever the client considers the additional TOS to be unnaceptable, the client is exempt of paying the initial deposit, until new satisfactory terms have been agreed upon.</li>
		<li>Both the customer and the provider may request a written agreement before any work is started.</li>
		<li>The acceptance of the current estimate implies the acceptance of these terms and conditions.</li>
	</ol>
    </div>
  <div class="modal-footer">
      <a href="#" class="btn" data-dismiss="modal" id="closeAccept"><?php echo _("Close"); ?></a>
	  <a href="#" class="btn btn-info pull-left" id="iWantToSeeItAll"><?php echo _("Show me the Full Terms"); ?></a>
    <a href="#" class="btn btn-info hide pull-left" id="notSoMuchPlease"><?php echo _("That's Way Too Much Text"); ?></a>
  </div>
</div>
    

<?php } else { ?>

<div class="page">
        
        <section class="header">
            <div class="logo">
                <img src="assets/img/logo.png" />
            </div>
            
            <div class="eduardonunes">
                <h1>Eduardo Nunes</h1>
                <p><a href="http://www.eduardonunes.me">www.eduardonunes.me</a></p>
                <p><a href="mailto:ed@eduardonunes.me">ed@eduardonunes.me</a></p>
                <p>(+351) 93 480 81 91</p>
            </div>
            
            <div class="push"></div>
        </section>
        
        <section class="client">
            <div class="details">
                <h2><?php echo _("This estimate is protected"); ?></h2>
                <p><?php echo _("You are trying to reach an address that was probably meant for the client only. If that's you, please follow the link provided to you by e-mail, or get in touch, using one of the contacts above. Thanks!"); ?></p>
            </div>
        	
        </section>
        
</div>

<?php } ?>

</div>

</body>
</html>

<?php

} else {

	// Actually, file does not exist
	echo "Unauthorized Access";

} ?>
