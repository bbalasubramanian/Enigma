
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Enigma Personal Assistant</title>
  
  <link rel="stylesheet" href="<?=base_url();?>assets/css/reset.min.css">
  <link rel="stylesheet" href="<?=base_url();?>assets/css/style.css">
  
</head>

<body>
  <div class="wrapper">
    <div class="container">        
        <div class="right">
            <div class="head">
            <span class="network">Airtel</span><span class="time"><?php date_default_timezone_set('Asia/Kolkata'); echo date("h:i a"); ?></span></div>
            <div class="top"><img src="<?=base_url();?>assets/img/chat-head.png" alt="" /><span><span class="name">Enigma Assistant</span></span></div>
            <div class="chat" id='chat_disp' data-chat="person2">
                <div class="conversation-start">
                  <span><?php echo "Today, " . date("h:i a"); ?></span>
                </div>
                
            
        </div>
        <div class="write">
                <form method="get">
                    <input type="text" id="human_txt" autocomplete="off"/>
                    <button type="submit" class="write-link send" id="human_send" style="height: 40px; background-color: #eceff1; border: none;"></button>
                </form>
            </div>
    </div>
</div>

  <script src='<?=base_url();?>assets/js/jquery.min.js'></script>

    <script src="<?=base_url();?>assets/js/index.js"></script>

</body>
</html>
