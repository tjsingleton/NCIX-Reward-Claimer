<?php

class Claimer {
  
  public $claim_number = "";
  private $claim_url = CLAIM_URL;
  private $active_users = array();
  private $current_email = "";

  
  public function __construct($claim_number) {
    $this->claim_number = $claim_number;
    $this->get_active_users();
    $this->claim_points();
    $this->clean_users();
  }
  
  private function get_active_users() {
    $query = "SELECT email FROM users WHERE active = 1";
    $result = mysql_query($query);
    
    while($row = mysql_fetch_assoc($result)) {
      $this->active_users[] = $row;
    }
  }
  
  private function claim_points() {    
    $ch = curl_init();

    foreach ($this->active_users as $user_email) {    
      $this->current_email = $user_email['email'];
      $user_email = urlencode($user_email['email']);
      $fields = "email=$user_email&claimno=" . urlencode($this->claim_number);
      
      $options = array (
          CURLOPT_URL => $this->claim_url
        , CURLOPT_POST => 2
        , CURLOPT_POSTFIELDS => $fields
        , CURLOPT_RETURNTRANSFER => TRUE
      );

      curl_setopt_array($ch, $options);

      $result = curl_exec($ch);

      $this->parse_results($result);
    }
    
    curl_close($ch);
  }
  
  private function parse_results($result) {
    
    $claim_reponses_regex = array (
        "Sorry, you have already claimed the Bonus"
      , "The email address hasn't been subscribed in the NCIX Newsletter\. Do you want to subscribe ncix newsletter\?"
      , "If you want to claim NCIX Newsletter Bonus, you have to Register NCIX\.com first"
    );
    
    $regex_string = "/(" . implode("|", $claim_reponses_regex) . ")/";
    
    preg_match($regex_string, $result, $match);
  
    $this->match_action($match);
  }
  
  private function match_action($match) {
    if(is_array($match)) {
      if(!empty($match[0])) {
        $this->log_failed_claim($match[0]);
      }
    }
  }
  
  private function log_failed_claim($error_message) {
    $error_message = mysql_real_escape_string($error_message);
    $query = "INSERT INTO failed_claims (email, error_message, error_date) VALUES ('{$this->current_email}', '$error_message', CURDATE())";
    mysql_query($query);
  }

  private function clean_users() {
    $query = "SELECT email, COUNT(email) FROM failed_claims GROUP BY email";
    $res = mysql_query($query);
    while($row = mysql_fetch_assoc($res)) {
        if($row['COUNT(email)'] >= 4) {
          $this->deactivate_email($row['email']);
        }
    }
  }
  
  private function deactivate_email($email) {
    $query = "UPDATE users SET active = 0 WHERE email = '$email'";
    mysql_query($query);
  }
    
}



?>