
require(

['js/functions.js', 'js/csp/constraints.js', 'domReady!'],

function(doc) {
    
    /*
     * This is the slideshow for the index page when not logged in.
     *
     */
    var indeximg = $(".indexImg");
    if(indeximg.length != 0){
        var index = 0;
        window.setInterval(function(){
            $("#index"+index).slideUp("slow");
            index = (index+1)%4
            $("#index"+index).slideDown("slow");
        }, 5000);
    }
    
    
    
    /*
     * These just set the drag/drop and the deleting up.
     */
    dropping();
    deleteClick();
    
    /*
     * Add on the "add block" and "add strip" items to the table
     * This will then need to be taken into consideration when
     * you actually do add one in...
     */

    $("#blockRota tr:first").append(
            "<td class='tableHeader' id='addBlock'>\
                    <a href='' id='addBlock'>Add</a></td>");
    $("#blockRota").append(
            "<tr id='tradd'><td class='tableHeader' id='addStrip'>\
                    <a href='' id='addStrip'>Add</a></td><tr>");
    
    
    /*
     *
     */
    
    $('span.rotaName').editable('helpers/api.php?action=11',{
        indicator: "Saving",
        event: 'click',
        tooltop: "Click to edit"
    });
    
    /*
     * This runs teh correct rota deleting function when the link is clicked.
     */
    $("#deleteRota").click(function(e){
        e.preventDefault();
        //TODO -Check that the user actually wants to delete
        
        var string = "helpers/api.php?action=10&rota=" + rotaid;
        console.log(string);
        $.get(string, function(data, status){
            window.location.href = "index.php";
        })
    });
    
    
    
    
    
    /*
     * This stuff is for the automatic user adding. 
     */
    
    $('#userSearch').click(function(){
        if($(this).val().toLowerCase() == "add user"){
            $(this).val("");
        }
    });
    
    $('#userSearch').blur(function(){
        if($(this).val().trim() == ""){
            $(this).val("Add user");
        }
    });
    
    $('#userSearch').autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "helpers/api.php?action=2",
          dataType: "json",
          type: "POST",
          data: {
            query: request.term
          },
          success: function( data ) {
            response( $.map( data.suggestions, function( item ) {
              return {
                label: item.value,
                value: item.value,
                data: item.data
              }
            }));
          }
        });
      },
      select: function( event, ui ) {
        if(ui.item){
            var name = ui.item.label;
            var username = ui.item.data;
            
            $('#userSearch').val("");
            // Add user to rota
            var string = "helpers/api.php?action=1&user=" + username + 
                            "&rota=" + rotaid;
            $.get(string, function(data,status){
                // Add to the page
                $("#userlist p.username:last").before
                        ("<p class='username' id='"+username+"'>"+name+"</p>");
                        dropping();
                userlist.push(new Array(name, username));
            });
        }
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
    
    
    
    
    
    
    
    /*
     * Adding a block when the add link is clicked.
     */
    
    $("#addBlock").click(function(e){
        e.preventDefault();
        // Create a new block, and the right number of blocksEntries as there
        // are strips.
        
        
        // Add a block using rotaid
        var string = "helpers/api.php?action=4&rota=" + rotaid;
        $.get(string,function(data,status){
            var block = data;
            $("#blockRota tr:first").find('td:last').before(
                    "<td class='tableHeader' id='0,"+block+"'>\n\
                        <span id='0,"+block+"' class='stuff'>New block</span>\n\
                        <span class='delete'>\
                            <a href='' class='none deleteB' \
                               id='del,"+block+"'>Del</a></span>\
                    </td>");
            $(".delete").show();
            deleteClick();
            blocks.push(block);
            editableTitles();
            // Add block entry with all of the strip numbers, and the block number
            for (var i = 0; i < strips.length; i++) {
                var string = "helpers/api.php?action=6&block=" + block + 
                    "&strip=" + strips[i];
                $.get(string,function(data,status){
                        
                });
                var j = i+1;
                $("#blockRota tr:eq(" + j + ")").append("<td id='" + strips[i] 
                                + "," + block + "' class='tableCell'> </td>");
                $("#blockRota tr:eq(" + j + ") td:last").droppable({
                    hoverClass: 'hover',
                    drop: dropHandler
                });
                tablearray[strips[i]][block] = " ";
            }
        });
    });
    
    /*
     * Adding a strip when the add link is clicked.
     */
    $("#addStrip").click(function(e){
        e.preventDefault();
        // Create a new strip, and the right number of blocksEntries as there
        // are blocks.
        
        // Add a block using rotaid
        var string = "helpers/api.php?action=5&rota=" + rotaid;
        $.get(string,function(data,status){
            editableTitles();
            var strip = data;
            $("#blockRota tr#tradd").before(
                    "<tr id='strip"+strip+"'>\
                        <td class='tableHeader' id='"+strip+",0' >\n\
                        <span id='"+strip+",0' class='stuff'>New strip</span>\n\
                                <span class='delete'>\
                                <a href='' class='none deleteS' \
                               id='del," + strip + "'>Del</a></span></td></tr>");
            
            $(".delete").show();
            strips.push(strip);
            deleteClick();
            editableTitles();
            list = new Array();
            // Add block entry with all of the strip numbers, and the block number
            for (var i = 0; i < blocks.length; i++) {
                var string = "helpers/api.php?action=6&strip=" + strip + 
                    "&block=" + blocks[i];
                $.get(string,function(data,status){
                
                });
                list[blocks[i]] = " ";
                $("#blockRota tr#strip" + strip).append("<td id='" + strip 
                    + "," + blocks[i] + "' class='tableCell'> </td>");
                $("#blockRota tr#strip" + strip).find("td:last").droppable({
                    hoverClass: 'hover',
                    drop: dropHandler
                });
                
            }
            tablearray[strip] = list;
        });
    });
    
    
    
    /*
     * The login box.
     */
    
    $("#myaccountp").click(function(e) {
        e.preventDefault();
        // This changes the view when you want to edit
        var login = $("#login");
        var account = $("#useraccount");
        var link = $("#myaccountp");
        if(login.is(":visible")){
            login.fadeOut("fast");
            link.animate({'backgroundColor': 'rgba(0,0,0,0)'}, 'fast')
            return false;
        }
        if(account.is(":visible")){
            account.fadeOut("fast");
            link.animate({'backgroundColor': 'rgba(0,0,0,0)'}, 'fast')
            return false;
        }
        link.animate({'backgroundColor': '#736F6E'}, 'fast')
        login.fadeIn("fast");
        account.fadeIn("fast");
        return false;
        
        
    });
    
    
    
    
    /*
     * Change view to edit when the link is clicked. Change the size of the
     * rota, and then fade a bunch of stuff in.
     */
    $("#edit").click(function(e){
        e.preventDefault();
        init();
        var users = $("#userlist");
        var edit = $("#edit");
        var addB = $("#addBlock");
        var addS = $("#addStrip");
        var table = $("#table");
        var fill = $("#fillRota");
        var complete = $("#fillEmpty");
        if(users.is(":visible")){
            addS.fadeOut("fast");
            addB.fadeOut("fast",function(){
                fill.fadeOut("fast");
                complete.fadeOut("fast");
                users.fadeOut("fast");
                table.animate({
                    width: '100%'
                })
            });
            $(".delete").fadeOut("fast");
            edit.text("Edit Rota");
            $("td.tableHeader span.stuff").unbind('click');
        }else{
            table.animate({
                    width: '65%'
                }, function(){
                        edit.text("Finish Editing");
                        fill.fadeIn("fast");
                        complete.fadeIn("fast");
                        addB.fadeIn("fast");
                        addS.fadeIn("fast");
                        users.fadeIn("fast");
                        $(".delete").fadeIn("fast");
                }
            );
            editableTitles();
        }
    })
});