$(function() {
   var selectedUserId;

   function initializeAutocomplete() {
       $("#userSearch").autocomplete({
           source: function(request, response) {
               $.ajax({
                   url: "/user/search",
                   method: "GET",
                   data: { term: request.term },
                   success: function (data) {
                    // Map the array of objects to an array of usernames
                    var usernames = data.map(function (user) {
                        return user.username;
                    });
                    response(usernames);
                }
               });
           },
           minLength: 1,
           select: function(event, ui) {
               selectedUserId = ui.item.id; 
               console.log("Selected user ID:", selectedUserId);
           }
       });
   }

   initializeAutocomplete();

   $("#addUserBtn").click(function() {
       console.log("Adding user ID:", selectedUserId);
       // Add logic to send the selectedUserId to the server or perform other actions
   });
});