/* 
 * 
 * 
 */


    /*
     * This functions uses jEditable to allow the titles of blocks/strips to
     * be edited. It sends the information to the api, and sets the title to
     * whatever was inputted. When it is successful, it changes the title on the
     * page to that.
     */
    function editableTitles(){
        $("td.tableHeader span.stuff").editable('helpers/api.php?action=9',{
            indicator: "Saving",
            event: 'click',
            tooltip: "Click to edit",
            onblur: 'submit'
        });
    }
    
    
    /*
     * Draggable usernames for editing the timetable. This allows the usernames
     * to be dragged to table cell items. It then uses the drophandler function
     * to save that information into the database, and updates the cell on the
     * page.
     */
    function dropping(){
        $(".username").draggable({
            cursor: 'move',
            containment: 'window',
            helper: 'clone'
        });

        $(".tableCell").droppable({
            hoverClass: 'hover',
            drop: dropHandler
        });
    }
    
    
    /*
     * This is the drophandler. It takes the element that was dropped onto it,
     * grabs the strip and block ids, and throws that information at the api.
     * It then returns the user's name, and dropHandler updates the cell
     * content to that.
     */
    function dropHandler( event, ui ){
        var username = ui.draggable.attr('id');
        var box = $(this);
        var cell = box.attr('id');
        var stuff = cell.split(",")
        var strip = stuff[0];
        var block = stuff[1];
        
        if(block < 1 || strip < 1){
            return false;
        }
        
        // Add username to a blockEntry for that strip and block
        // currently will change whatever is there
        var string = "helpers/api.php?action=3&user=" + username 
                + "&block=" + block + "&strip=" + strip;
        $.get(string,function(data,status){
            box.html(data);
        });
        return true;
    }
    
    
    
    /*
     * Add the click function to these delete links. It grabs the correct id
     * and then requests that the api delete these. Then I do some weird
     * jquery nonesense and delete the right (hopefully) row or column. I don't
     * think it works perfectly. Need to do more testing.
     */
    
    function deleteClick(){
        $(".deleteB").click(function(e){
            e.preventDefault();
            var id = $(this).attr('id');
            ids = id.split(",");
            var block = ids[1];


            var string = "helpers/api.php?action=7&block=" + block;
            $.get(string, function(data,status){

            });
            var myIndex = $(this).closest("td").index();
            $("td#0\\,"+block).remove();
            for(strip in strips){
                tdid = "" + strips[strip] + "\\," + block;
                console.log(tdid);
                $("td#"+tdid+"").remove();
            }
            var arrayIndex = blocks.indexOf(block);
            blocks.splice(arrayIndex, 1);
        });
        
    
        $(".deleteS").click(function(e){
            e.preventDefault();
            var ids = $(this).attr('id');
            ids = ids.split(",");
            var strip = ids[1];

            var string = "helpers/api.php?action=8&strip=" + strip;
            $.get(string, function(data,status){

            });
            var myIndex = $(this).closest("tr").remove();
            var arrayIndex = strips.indexOf(strip);
            strips.splice(arrayIndex, 1);
        });
    }
    
    
    /**
     * 
     */
    
    function touchHandler(event){
     var touches = event.changedTouches,
        first = touches[0],
        type = "";

        switch(event.type){
            case "touchstart": type = "mousedown"; break;
            case "touchmove":  type="mousemove"; break;        
            case "touchend":   type="mouseup"; break;
            default: return;
        }
        var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent(type, true, true, window, 1,
                              first.screenX, first.screenY,
                              first.clientX, first.clientY, false,
                              false, false, false, 0/*left*/, null);

        first.target.dispatchEvent(simulatedEvent);
        event.preventDefault();
    }

    function init(){
       var test = document.getElementById("userlist");
       test.addEventListener("touchstart", touchHandler, true);
       test.addEventListener("touchmove", touchHandler, true);
       test.addEventListener("touchend", touchHandler, true);
       test.addEventListener("touchcancel", touchHandler, true);    
    }