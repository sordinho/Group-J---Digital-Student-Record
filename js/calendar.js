
!function() {
  var returnedData;
  /* Send the data using post and put the results in a div */
  $.ajax({
    url: "./homeworkAPI.php",
    type: "get",
    //data: val,
    datatype: 'json',
    async: false,
    success: function(data){
          //var jdata = JSON.parse(data);
          if(data.status == "ok"){
            returnedData = data.message;
          } //else{
    },
    error:function(){
      // just ignore for now, 
        /*$("#result").html('There was an error ');
        $("#result").addClass('error');
        $("#result").fadeIn(1500);*/
    }   
  }); 

  var data = Array.from(returnedData);

  //Working testing data
  /*
  var data = [
    { eventName: 'Pages 30-36', calendar: 'History', color: 'orange', full_date:"2019-11-27" },
    { eventName: 'Pages 50-56', calendar: 'Maths', color: 'green', full_date:"2019-11-28" },
  ];//*/

  var calendar = new Calendar('#calendar', data);

}();
