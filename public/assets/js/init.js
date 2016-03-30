jQuery(document).ready(function($){

    //file to which ajax requests will be sent
    var processFile = "assets/inc/ajax.inc.php";

    //funciton to manipulate the modal window
    var fx = {
        "initModal" : function(){
            if($(".modal-window").length==0){
                return $("<div>").
                hide().
                addClass("modal-window").
                appendTo("body");
            }
            else{
                return $(".modal-window");
            }
        },

        "boxin" : function(data, modal){
            $("<div>").hide().
            addClass("modal-overlay").
            click(function(event){
                fx.boxout(event);
            })
            .appendTo("body");

            modal
            .hide()
            .append(data)
            .appendTo("body");

            //fades in the modal body and overlay
            $(".modal-window,.modal-overlay")
            .fadeIn();
        },

        //removes and fades out the active modal window
        "boxout" : function(event) {
            if (event!=undefined){
                event.preventDefault();
            }
            $("a").removeClass("active");
            $(".modal-window,.modal-overlay").fadeOut("slow", function(){
                $(this).remove();
            });
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

        // add close button
        $("<a>").attr("href", "#").addClass("modal-close-btn")
        .html("&times;").click(function(event){
            fx.boxout(event);          
        }).appendTo(modal);


        //loads the event data from DB
        $.ajax({
            type: "post",
            url: processFile,
            data: "action=event_view&" +data,
            success: function(data){
                //modal.append(data);
                fx.boxin(data, modal);
            },
            error: function(msg){
                modal.append(msg);
            }

        });

    });
});