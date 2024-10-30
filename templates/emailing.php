<?php
/**
 * @package  Miraget Generator
 */

class  MiragetEmailingTemplate{

}
?>
<?php
    
    if( isset($_POST['user_mail']) && isset($_POST['subject']) && isset($_POST['message'])){
        // ini_set("SMTP", "localhost");
        add_action( 'phpmailer_init', 'mailer_config', 10, 1);
        function mailer_config(PHPMailer $mailer){
          $mailer->IsSMTP();
          $mailer->Host = "smtp.gmail.com"; // your SMTP server
          $mailer->Port = 587;
          $mailer->SMTPSecure = "tls";
          $mailer->SMTPAuth = true;                               // Enable SMTP authentication
          $mailer->Username = 'sskarim85@gmail.com';                 // SMTP username
          $mailer->Password = 'account password';  
          $mailer->SMTPDebug = 0; // write 0 if you don't want to see client/server communication in page
          $mailer->CharSet  = "utf-8";
        }
        // ini_set('smtp_port',25);
        $user_email = sanitize_text_field($_POST['user_mail']);
        $user_message = sanitize_text_field($_POST['message']);
        $subject = sanitize_text_field($_POST['subject']);
        $to      = 'sskarim@live.fr';
        
        $headers = array(
          'From' => $user_email,
          'Reply-To' => $user_email,
          'X-Mailer' => 'PHP/' . phpversion()
      );
        $sent=wp_mail($to, $subject, $user_message, $headers);
        if($sent) echo '<h2 style="color:green;">Thank you for your FeedBack <h2>';
        else echo 'failed to send';
       
       
    }
?>
<h3> For custom integrations or to report a bug please contact us below : </h3>

 
<div class="email-container">
  <form method="post" action="">
    <div class="row">
      <div class="col-25">
        <label for="fname"> Email</label>
      </div>
      <div class="col-75">
        <input type="text" id="fname" name="user_mail" placeholder="Your Email..">
      </div>
    </div>
    <div class="row">
      <div class="col-25">
        <label for="lname">Subject</label>
      </div>
      <div class="col-75">
        <input type="text" id="lname" name="subject" placeholder="Subject..">
      </div>
    </div>
    
    <div class="row">
      <div class="col-25">
        <label for="subject">Message</label>
      </div>
      <div class="col-75">
        <textarea id="subject" name="message" placeholder="Your Message here..." style="height:200px"></textarea>
      </div>
    </div>
    <div class="row">
      <input type="submit" value="Send Email">
    </div>
  </form>
</div>
