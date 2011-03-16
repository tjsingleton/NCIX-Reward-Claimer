<?php

class Claimer {
  
  public $claim_number = "";
  private $claim_url = CLAIM_URL;
  private $active_users = array();

  
  public function __construct($claim_number) {
    $this->claim_number = $claim_number;
    $this->get_active_users();
    $this->claim_points();
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
      $user_email = urlencode($user_email['email']);
      $fields = "email=$user_email&claimno=" . urlencode($this->claim_number);

      curl_setopt($ch, CURLOPT_URL, $this->claim_url);
      curl_setopt($ch, CURLOPT_POST, 2);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

      $result = curl_exec($ch);

      $this->parse_results($result);
    }
    
    curl_close($ch);
  }
  
  private function parse_results($result) {
    var_dump($result);
  }

    
}



?>