<?php
  
  require 'config.php';
  require 'claimer.class.php';
  require 'user.class.php';
  
  if(isset($_POST['claimno'])) {
    $results = new Claimer($_POST['claimno']);
  }
  
?>
<!DOCTYPE html>
<html>
  <head>
    <title>NCIX Point Claimer</title>
  </head>
  <body>
    <?php if(isset($results)): ?>
      <div style="text-align: center; font-weight: bold; padding: 25px; font-size: 1.5em; color: #FFF; background-color: green;">
        <h1>Complete!</h1>
        <ul>
          <li>Successful Claims: <?php echo $results->claims_success; ?></li>
          <li>Failed Claims: <?php echo $results->claims_failed; ?></li>
          <li>Deactivated Users: <?php echo $results->deactivated_users; ?></li>
        </ul>
        
      </div>
    <?php endif; ?>
    
    <form action="" method="POST">
      <input type="text" name="claimno" /><br />
      <input type="submit" value="Submit" />
    </form>
  </body>
</html>