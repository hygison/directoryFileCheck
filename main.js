$(document).ready(function(){

    

    $(document).on("click","#scan",function(){
        var action = 'scanDir';
        $.ajax({
            url : "includes/fileCheck.inc.php",
            method: "POST",
            data: {
                action : action
            },
            success:function(data){
                $("#show-content").html(data);
            }
        });
    });

    $(document).on("click","#dbFiles",function(){
        var action = 'dbFiles';
        $.ajax({
            url : "includes/fileCheck.inc.php",
            method: "POST",
            data: {
                action : action
            },
            success:function(data){
                $("#show-content").html(data);
            }
        });
    });

    $(document).on("click",".viewDangerWords",function(){
        let dangerString = $(this).attr("data-json");

        let dangerJSON = JSON.parse(dangerString);

        let dangerHTML = 'There are no danger words in the file';
        for(var i =0; i < dangerJSON.length; i++){
            if(i ==0){
                dangerHTML = '';
            }
            dangerHTML += dangerJSON[i]+'<br>';
        }
       
        $(".modal-body").html(dangerHTML);
        $("#modal").modal('show'); 
    });


    





});