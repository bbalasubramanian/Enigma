$('.chat[data-chat=person2]').addClass('active-chat');
$('.person[data-chat=person2]').addClass('active');
$('#human_txt').focus();

$('form').submit(function(e){ 
    e.preventDefault(e); 
    $humanRep = $('#human_txt').val();
    $('#chat_disp').append('<div class="bubble me">' + $humanRep + ' </div>');
    $('#chat_disp').append('<div id="loader"><img src="assets/img/ajax-loader.gif" style="float:left"/></div>');

    $('#human_txt').val('');
    $('#human_txt').focus();
    console.log($humanRep);

    if($humanRep == 'clear'){
        $(".bubble").remove();
        $("#loader").remove();
        $(".card").remove();        
    }
    else
    {
        $.get("http://localhost/enigma/chatroom/talktobot?q=" + $humanRep, function(data) {        
            console.log(data);
            $("#loader").remove();
            if(data.indexOf('<div')!=-1){
                $('#chat_disp').append(data);    
            } else {
                $('#chat_disp').append('<div class="bubble you">' + data + ' </div>');    
            }
        });
    }
});