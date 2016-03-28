jQuery(document).ready(function($){

    //file to which ajax requests will be sent
    var processFile = "assets/inc/ajax.inc.php";

    //funciton to manipulate the modal window
    var fx = {
        "initModal" : function(){
            if($(".modal-window").length==0){
                return $("<div>").addClass("modal-window").
                    appendTo("body");
            }
            else{
                return $("modal-window");
            }
        }

    };

    //select all links child of li
    $("li>a").click(function(event){
        //prevent default action
        event.preventDefault();

        //add an active class
        $(this).addClass("active");

        //get the href attribute
        var data = $(this).attr("href").replace(/.+?\?(.*)$/, "$1");

        //check if modal window exist, if not create one
        modal = fx.initModal();

        //loads the event data from DB
        $.ajax({
            type: "post",
            url: processFile,
            data: "actions=event_view&" +data,
            success: function(data){
                modal.append(data);
            },
            error: function(msg){
                modal.append(msg);
            }

        });

    });
});