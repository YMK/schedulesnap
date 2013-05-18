/* 
 * This is my library for the constraint satisfaction of the timetables. It
 * is how I get it to automatically fill in the timetable. Currently, it will
 * just fill in the timetable with everyone able to go in every box, but
 * over time I'll add in the constraints. It will be nice and flexible to allow
 * different types of constraints. 
 */

var cspjs;
var csphash;
var cspset;

require(
    /*
     * Just require the 3 files needed for this constraint satisfaction. 
     * This is the CSP library. 
     */
    ['csp/csp', 'csp/hash', 'csp/Set', 'domReady!'],
    
    
    
    
    function(csp, Hash, Set){
        
        cspjs = csp;
        csphash = Hash;
        cspset = Set;
        
        
        /*
         * When everything is available, create the functions. We will then
         * add them to the click action for each button.
         * 
         * We have 2 functions. The first takes the table array, and fills
         * the empty cells with users.
         */
                
        function fill(full){
            var p = cspjs.DiscreteProblem();
            
            var nonabilities = new Array();
                      
            for (listeduser in userlist){  
                var string = "helpers/api.php?action=12&rota=" 
                    + rotaid + "&user=" + userlist[listeduser][1];
                $.ajax({
                    url : string,
                    async: false,
                    success: function(data, status){
                        var datas = data.content.split(",");
                        if(datas[0] == "ability"){
                            if(datas[3] == "false"){
                                nonabilities.push("" + datas[2] +"," + userlist[listeduser][1]);
                            }
                        }
                    }
                });
            }

            for (row in tablearray){
                for (cell in tablearray[row]){
                    if(row > 0 && cell > 0 && (full || tablearray[row][cell] == " ")){
                        
                        var possibles = new Array();
                        for (listeduser in userlist){
                            var able = true;
                            for(nonab in nonabilities){
                                var test = nonabilities[nonab].split(",");
                                if(test[1] == userlist[listeduser][1]){
                                    if(test[0] == cell){
                                        able = false;
                                        console.log(able);
                                    }
                                }
                            }
                            if(able){
                                possibles.push(userlist[listeduser][1]);
                            }
                        }
                        possibles.sort(function() {return 0.5 - Math.random()});
                        possibles.sort(function() {return 0.5 - Math.random()});
                        p.addVariable(""+row+"\\,"+cell, possibles);
                    }
                }
            }
            var one_solution = p.getSolution();
            for(sol in one_solution){
                changeUser(one_solution[sol], sol, "\\,");
            }
        }
        
        
        function changeUser(username, id, splitter){
            var ids = id.split(splitter);
            var block = ids[1];
            var strip = ids[0];
            var box = $("td#" + id);
            var string = "helpers/api.php?action=3&user=" + username 
                    + "&block=" + block + "&strip=" + strip;
            $.get(string,function(data,status){
                box.html(data);
            });
        }
        
        
        /*
         * Now we add the click actions to the function, and make sure the links
         * don't actually do anything.
         */
        $("#fillRota").click(function(e){
            e.preventDefault();
            fill(true);
        });

        $("#fillEmpty").click(function(e){
            e.preventDefault();
            fill(false);
        });
        
        
        
    });