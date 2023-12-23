
let allSeats = document.querySelectorAll(".seat");
let passengers = document.querySelector(".passengers");
let tktid = null;
if(document.getElementById("ticketid"))
{
    tktid = document.getElementById("ticketid").value;
}

if(allSeats.length > 0)
{
    for(let item of allSeats)
    {
        item.addEventListener("click", function(){ 
            if(tktid !== null)
            {  
               // item.setAttribute("data-selected", 'yes');
                //item.style.cssText = "background-color: green; color: #fff";
                let siblings = item.parentElement.children;
                for(let sib of siblings) {
                    if(sib.id == item.id)
                    {
                            sib.setAttribute("data-selected", 'yes');
                            sib.style.cssText = "background-color: green; color: #fff";
                            passengers.innerHTML += addPassenger(sib.getAttribute('data-seat'));
                    }
                    else
                    {
                        
                       if(sib.getAttribute("data-selected"))
                       {
                            sib.setAttribute("data-selected", 'no');
                            sib.style.cssText = "background-color: #bdc3c7; color: #000";
                            deletePassenger(sib.getAttribute('data-seat'))
                       }
                       
                    }
                    
                }
            }
            else
            {
                if(item.getAttribute("data-selected") == "no")
                {
                    item.setAttribute("data-selected", 'yes');
                    item.style.cssText = "background-color: green; color: #fff";
                    passengers.innerHTML += addPassenger(item.getAttribute('data-seat'));
                }
                else
                {
                    item.setAttribute("data-selected", 'no');
                    item.style.cssText = "background-color: #bdc3c7; color: #000";
                    deletePassenger(item.getAttribute('data-seat'))
                }
            }
 
            
        })
    }

   
}
else
{
    passengers.innerHTML = "<p>Seats Not Found</p>";
}


function addPassenger(id)
{
    let txt = "";
    if(document.getElementById("ticketid"))
    {
        
        var pname = document.getElementById("passenger_name").value;
        var gender = document.getElementById("gender").value;
        var age = document.getElementById("age").value;

        txt += "<div class='pform' id='pid_"+id+"'>";
        txt += "<div class='details-bar'>Seat No: "+id+" Passenger Details</div>"
        txt += "<div class='fields'>";
            txt += "<input value='"+pname+"' type='text' data-seat='"+id+"' id='pname_"+id+"' class='form-input pname' placeholder='Passenger Name' />";
            txt += "<select id='pgender_"+id+"' class='form-input pgender' name=''>";
                txt += "<option value=''>--gender--</option>";
                txt += (gender === "Male") ?  "<option selected value='Male'>Male</option>" : "<option value='Male'>Male</option>";
                txt += (gender === "Female") ?  "<option selected value='Female'>Female</option>" : "<option value='Female'>Female</option>";
            txt += "</select>";
            txt += "<input value='"+age+"' type='number' min='5' max='100' id='page_"+id+"' class='form-input page' placeholder='age' />";
            
        txt += "</div>";
        txt += "<div>";

    }
    else
    {
       
        txt += "<div class='pform' id='pid_"+id+"'>";
                txt += "<div class='details-bar'>Seat No: "+id+" Passenger Details</div>"
                txt += "<div class='fields'>";
                    txt += "<input type='text' data-seat='"+id+"' id='pname_"+id+"' class='form-input pname' placeholder='Passenger Name' />";
                    txt += "<select id='pgender_"+id+"' class='form-input pgender' name=''>";
                        txt += "<option value=''>--gender--</option>";
                        txt += "<option value='Male'>Male</option>";
                        txt += "<option value='Female'>Female</option>";
                    txt += "</select>";
                    txt += "<input type='number' min='5' max='100' id='page_"+id+"' class='form-input page' placeholder='age' />";
                    txt += "<button class='remove-btn' onclick='deletePassenger("+id+")'>Remove</button>";
                txt += "</div>";
        txt += "<div>";
   }
    

    return txt;
}

function deletePassenger(id)
{
    if(document.getElementById("pid_"+id))
    {
        document.getElementById("pid_"+id).remove();
    }
    document.getElementById("seat_"+id).style.cssText = "background-color: #bdc3c7; color: #000";
    document.getElementById("seat_"+id).setAttribute("data-selected", 'no');
}

document.querySelector(".booknow").addEventListener("click", function(){
    let allFields = document.querySelectorAll(".fields .pname");
    let ageFields = document.querySelectorAll(".fields .page");
    let genderFields = document.querySelectorAll(".fields .pgender");
    

    if(allFields.length)
    {
        

       let pname = page = pgender =  true;
       for(let item of allFields)
       {
            if(item.value === ""){
                pname = false;
            }
       }
       for(let item of ageFields)
       {
            if(item.value === ""){
                page = false;
            }
       }
       for(let item of genderFields)
       {
            if(item.value === ""){
                pgender = false;
            }
       }

       if(pname == true && page == true && pgender == true)
       {
            const pdata = [];
            console.log(allFields)
            for(let item of allFields)
            {
                let user = {};
                if(document.getElementById("ticketid"))
                {
                    user.action = "edit";
                    user.ticketid = document.getElementById("ticketid").value;
                }
                else
                {
                    user.action = "add";
                    user.ticketid = (Date.now()+(Math.random()*172)).toFixed(0);
                }
                
                let seat = item.getAttribute('data-seat');
                user.userid = document.getElementById("loggedin_user").value;
                user.busnum = getParameterByName('serviceno');
                user.source = getParameterByName('source');
                user.destination = getParameterByName('destination');
                user.travel_date = getParameterByName('date');
                user.seatno = seat;
                user.passenger_name = item.value;
                user.gender = document.getElementById("pgender_"+seat).value;
                user.age = document.getElementById("page_"+seat).value;
                user.charge = document.getElementById("unit_fare").value;
                pdata.push(user);
            }
            localStorage.setItem("booking_details", JSON.stringify(pdata));
            const obj = new XMLHttpRequest();
            obj.open("POST", "http://localhost/bms/booking.php", true);
            obj.setRequestHeader("Content-Type", "Application/josn");
            obj.send(JSON.stringify(pdata))
            obj.onreadystatechange = function(){
                if(obj.readyState === 4 && obj.status === 200)
                {
                    const info = JSON.parse(obj.responseText);
                    if(info.status === "success")
                    {
                        if(info.action === "edit")
                        {
                            alert("Ticket reschedule updated successfully");
                            window.location = "user_manage_booking.php";
                        }
                        else
                        {
                            window.location = "payment.php?tid="+info.tid;
                        }
                        
                    }
                    else
                    {
                        alert("Sorry! Unbale to book ticket, try again")
                        window.location = window.location.href;
                    }
                    // window.location = "payment.php";
                }
            }
           

       }
       else
       {
            alert("Please select all the details of passengers")
       }
    }
    else
    {
        alert("Please select atleast one seat")
    }
})

function getParameterByName(name) {
    var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
    return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
}

function getUserInfo(id, cb)
{
    const obj = new XMLHttpRequest();
        obj.open("GET", "http://localhost/bms/getuserinfo.php?tid="+tid, false);
        obj.send()
        obj.onload = function(){
            if(obj.readyState === 4 && obj.status === 200)
            {
                userInfo = JSON.parse(obj.responseText);  
                cb(userInfo)
            }
    }

}




