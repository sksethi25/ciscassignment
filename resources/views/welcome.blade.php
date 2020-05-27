<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cisco Assignment</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

       <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css"> -->
  <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script> -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="/welcome.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.js"></script>


<script type="text/javascript">
$(document).ready( function () {
    var form = document.getElementsByClassName('needs-validation')[0];
    form.addEventListener('submit', onsubmit);
    loaddataable();
    setvalues("", "", "", "", "", "");
    setErrorText("", "", "", "");
    $('#deleterouter').on('click', ondelete);
    $('#exampleModalCenter').on('hidden.bs.modal', onmodalHide);
    
} );

function onmodalHide(){
  setvalues("", "", "", "", "");
  setErrorText("", "", "", "");
  $('#deleterouter').prop('disabled', true);
}
function isValidIp(loopback) {
  return (/^(?:(?:^|\.)(?:2(?:5[0-5]|[0-4]\d)|1?\d?\d)){4}$/.test(loopback) ? true : false);
}
function ondelete(){
        var routerid = $("#routerid").val();
        if(routerid=='undefined'  || routerid==""){
            closemodel();
            loaddataable();
        }else{
          deleteRouter(routerid);
        }
        
      }

function saveRouter(sapid, hostname, loopback, macaddress){
   $.ajax({
      url: "/router/",
          type:"post",
          dataType:"json",
           data: {
              sapid:sapid,
              hostname:hostname,
              loopback:loopback,
              macaddress:macaddress
           }
      }).done(function(data) {
          var res =data.status;
          if(res==true){
              alert("Data saved sucessfully");
              closemodel();
              loaddataable();
          }
    });
}

function updateRouter(sapid, hostname, loopback, macaddress, routerid){
   $.ajax({
      url: "/router/"+routerid,
          type:"put",
          dataType:"json",
           data: {
              sapid:sapid,
              hostname:hostname,
              loopback:loopback,
              macaddress:macaddress
           }
      }).done(function(data) {
          var res =data.status;
          if(res==true){
              alert("Data updated sucessfully");
              closemodel();
              loaddataable();
          }
    });
}

function deleteRouter(routerid){
  $.ajax({
    url: "/router/"+routerid,
        type:"delete"
        
    }).done(function(data) {
        var res =data.status;
        if(res==true){
            alert("Data deleted sucessfully");
            closemodel();
            loaddataable();
        }
  });
}

function closemodel(){
    $("#closeb").click();
    setvalues("", "", "", "", "", "");
    setErrorText("", "", "", "");
    $('#deleterouter').prop('disabled', true);
}

function setvalues(sapid, hostname, loopback, macaddress, routerid){
  var sapid = $("#sapid").val(sapid);
  var hostname = $("#hostname").val(hostname);
  var loopback = $("#loopback").val(loopback);
  var macaddress = $("#macaddress").val(macaddress);
  var routerid = $("#routerid").val(routerid);
}

function setErrorText(sapid, hostname, loopback, macaddress){
    $("#sapidhelp").text(sapid);
    $("#hostnamehelp").text(hostname);
    $("#macaddresshelp").text(macaddress);
    $("#loopbackhelp").text(loopback);
}

function loaddataable(){
    $('#table_id').DataTable().destroy();
    var table="";
    $.ajax({
       url: "/router/all",
      type:"post"
    }).done(function(data) {
        table = $('#table_id').DataTable({
        serverSide: false,
        data:data.data,
        columns: [
        { data: 'id' },
        { data: 'sapid' },
        { data: 'hostname' },
        { data: 'loopback' },
        { data: 'macaddress' },
        { data: 'created_at' },
        { data: 'updated_at' },
        { data: 'deleted_at' }
        ]
        });

        $('#table_id tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        setvalues(data.sapid, data.hostname, data.loopback, data.macaddress, data.id);
         $('#deleterouter').prop('disabled', false);
         $("#model_open").click();
    } );
  

});
}

function onsubmit(event){
          event.preventDefault();
          event.stopPropagation();

          var valid=true;
          var sapidtext=hostnametext=loopbacktext=macaddresstext="";
           
          var routerid = $("#routerid").val();
          var sapid = $("#sapid").val();
          var hostname = $("#hostname").val();
          var loopback = $("#loopback").val();
          var macaddress = $("#macaddress").val();

           if(sapid=="" || sapid.length<3 || sapid.length>18){
                $("#sapidhelp").text("It should be String between 3 to 18 chars");
                valid=false;
                sapidtext = "It should be String between 3 to 18 chars";
           }

           if(hostname=="" || hostname.length<3 || hostname.length>14){
                $("#hostnamehelp").text("It should be String between 3 to 14 chars");
                hostnametext = "It should be String between 3 to 14 chars";
                 valid=false;
           }

           if(macaddress=="" || macaddress.length<3 || macaddress.length>17){
                $("#macaddresshelp").text("It should be String between 3 to 17 chars");
                macaddresstext = "It should be String between 3 to 17 chars";
                 valid=false;
           }

           if(!isValidIp(loopback)){
                 valid=false;
                 loopbacktext = "It should be a valid ip";
           }

           setErrorText(sapidtext, hostnametext, loopbacktext, macaddresstext)

           if(valid==true){
            if(routerid=='undefined'  || routerid==""){
                saveRouter(sapid, hostname, loopback, macaddress);
            } else{
                updateRouter(sapid, hostname, loopback, macaddress, routerid);
            }
          }
}
</script>
    </head>
    <body>
        <div class="flex-center position-ref">
            <div class="content">
                <div class="title m-b-md">
                    Cisco Assingnment
                </div>
                <div>

              <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" id="model_open" data-toggle="modal" data-target="#exampleModalCenter">
  Create new Router
</button>
</div>
                <table id="table_id" class="display table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Sapid</th>
             <th>Hostname</th>
            <th>Loopback </th>
             <th>Macaddress</th>
            <th>Created at</th>
             <th>Updated at</th>
            <th>Deleted at</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <form class="needs-validation" id="dd"> 
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Router</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">


    <div class="form-group">
    <label for="routerid">Router id</label>
    <input type="text" class="form-control" id="routerid" aria-describedby="routeridhelp" readonly placeholder="It is auto assinged">
    <small id="routeridhelp" class="form-text text-danger"></small>
  </div>

    <div class="form-group">
    <label for="sapid">Sap id</label>
    <input type="text" class="form-control" id="sapid" aria-describedby="sapidhelp" placeholder="Enter Sapid">
    <small id="sapidhelp" class="form-text text-danger"></small>
  </div>
  <div class="form-group">
   <label for="hostname">Host Name</label>
    <input type="text" class="form-control" id="hostname" aria-describedby="hostnamehelp" placeholder="Enter Hostname">
    <small id="hostnamehelp" class="form-text text-danger"></small>
  </div>
  <div class="form-group">
   <label for="loopback">Loopback</label>
    <input type="text" class="form-control" id="loopback" aria-describedby="loopbackhelp" placeholder="Enter Loopback(ip)">
    <small id="loopbackhelp" class="form-text text-danger"></small>
  </div>
  <div class="form-group">
   <label for="macaddress">Mac Address</label>
    <input type="text" class="form-control" id="macaddress" aria-describedby="macaddresshelp" placeholder="Enter Macaddress">
    <small id="macaddresshelp" class="form-text text-danger"></small>
  </div>
  
      </div>
      <div class="modal-footer">
        <button type="button" id="closeb" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="deleterouter" disabled class="btn btn-primary">Delete router</button>
        <button type="submit" class="btn btn-primary">Save changes</button>

      </div>
      <form>
    </div>
  </div>
</div>

            </div>
        </div>
    </body>
</html>
