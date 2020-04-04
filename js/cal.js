//Custom Date Function For Better Syntax
Date.prototype.addDays = function (days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}
Date.prototype.subtractDays = function (days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() - days);
    return date;
}
//Dragging Functions
function dragStart(ev) {
    ev.dataTransfer.effectAllowed = "move";
    ev.dataTransfer.setData("Text", ev.target.getAttribute("id"));
    ev.dataTransfer.setDragImage(ev.target, 0, 0);
    return true;
}
function dragEnter(ev) {
    ev.preventDefault();
    return true;
}
function dragOver(ev) {
    return false;
}
function dragDrop(ev) {
    let id = ev.dataTransfer.getData("Text");
    let confirmation = confirm("Move Appt to " + ev.target.getAttribute("data-time") + "?");
    if (confirmation) {
        let d = new Date(ev.target.getAttribute("data-time")).toISOString();
        let data = {
            index: id.split("-")[1], 
            date: d,
        };
        console.log(data);
        updateAppt(data);
        ev.target.appendChild(document.getElementById(id));
        console.log(ev.target.getAttribute("data-rem"));
        document.getElementById(id).style.top = ev.target.getAttribute("data-rem") + "rem";
        ev.stopPropagation();
    } else {
        console.warn("Changes Cancelled");
    }

    return false;
}
//Conversion Functions
function minToTime(mins, isMilitary = false) {
    let hr = Math.floor(mins / 60);
    let min = mins % 60;
    min == 0 ? min = "00" : min = "" + min;
    switch (isMilitary) {
        case true:
            hr = "T" + hr + ":" + min + ":" + "00.000Z";
            break;
        case false:
            if (hr > 12) {
                hr -= 12;
                hr = hr + ":" + min;
                hr += "&nbsp;PM";
            } else {
                hr = hr + ":" + min;
                hr += "&nbsp;AM";
            }
            break;
    }
    return hr;
}
//Calendar Functionality Functions
function applyOptions() {
    changeDaysToShow(document.getElementById('dts_input').value);
    start_date = new Date(document.getElementById('opt_start_date').value);
    changeInterval(document.getElementById('interval_input'));
    refreshData();
    document.getElementById('overlay').click();
}
function openConfiguration() {
    for (let i = 0; i < document.getElementsByClassName('overlay_container').length; i++) {
        document.getElementsByClassName('overlay_container')[i].classList.add('hidden');
    }
    //Apply Current Settings
    document.getElementById("opt_start_date").value = start_date;
    document.getElementById("dts_input").value = days_to_show;
    document.getElementById("config_popup").classList.remove("hidden");
    document.getElementById("overlay").classList.remove("hidden");
}
function openApptCreator(ticket_id = null) {
    for (let i = 0; i < document.getElementsByClassName('overlay_container').length; i++) {
        document.getElementsByClassName('overlay_container')[i].classList.add('hidden');
    }
    //Apply Current Settings
    if(ticket_id) {

    } else {
        cart = {
            id: null,
            date: new Date().toISOString(),
            duration: 0,
            cid: null,
            eid: 1,
            items: [
    
            ],
            valid: {
                client: false,
                items: false
            },
        };
    }
    let formatted_date = cart.date.split("T");
    let time = formatted_date[1].substr(0,5);
    formatted_date = formatted_date[0].split("-");
    formatted_date = formatted_date[1]+"/"+formatted_date[2]+"/"+formatted_date[0];
    time = time.split(":");
    if(time[0] > 12){time[0]-=12;time[2]="PM"}else{time[2]="AM"};
    document.getElementById("editor_ticket_id").innerHTML = ticket_id;
    document.getElementById("editor_appt_date").value = formatted_date;
    document.getElementById("editor_appt_hr").value = time[0];
    document.getElementById("editor_appt_min").value = time[1];
    document.getElementById("editor_appt_tod").value = time[2];
    buildItems();
    refreshCart();
    document.getElementById("create_appt_popup").classList.remove("hidden");
    document.getElementById("overlay").classList.remove("hidden");
}
function create_appt(time, day_index) {
    //NEED TO REPLACE THIS WITH ACTUAL DATA AT SOME POINT
    let appt_date = start_date.addDays(day_index);
    appt_date = appt_date.toISOString();
    appt_date = appt_date.split("T")[0];
    appt_date += minToTime(time, true);
    let appt = {};
    appt.cid = 0;
    appt.date = appt_date;
    appt.duration = 30;
    appt.services = [
        0
    ];
    appts.push(appt);
    calendarRefresh();
}
function changeDaysToShow(days) {
    days_to_show = days;
}
function changeInterval(el) {
    let opt;
    for (let i = 0; i < el.options.length; i++) {
        opt = el.options[i];
        if (opt.selected === true) {
            break;
        }
    }
    alert(opt.value);
    increment = opt.value;
}
function moveBackDays() {
    start_date = new Date(start_date).subtractDays(days_to_show);
    refreshData();
}
function moveForwardDays() {
    start_date = new Date(start_date).addDays(days_to_show);
    refreshData();
}
function calendarRefresh() {
    //Reset the calendar
    document.getElementsByClassName('weekly-calendar')[0].innerHTML = `
  <div id="Side-Guide" class="row" style='width: 7rem;border:none;'>
        <div class="row-top">
          <div>&nbsp;</div>
          <div>&nbsp;</div>
        </div>
        <div class="timeslot-header">
          
        </div>
      </div>`;
    //Set the date header
    document.getElementById("cal_date_range").innerHTML = new Date(start_date).toDateString() + " - " + new Date(start_date).addDays(days_to_show - 1).toDateString();
    //Generate Timeslots & Timeslot Header
    let active_time = open_mins;
    let head_html = "";
    let day_html = [""];
    let itteration = 0;
    while (active_time <= close_mins) {
        let str_time = minToTime(active_time);
        head_html += "<div class='slot_header' data-time='" + str_time + "' style='pointer-events:auto' title='" + str_time + "'>" + str_time + "</div>";
        for (let i = 0; i < days_to_show; i++) {
            let template = "<div class='slot' id='i" + i + "t" + active_time + "' data-time='" + new Date(start_date).addDays(i).toDateString() + "' data-rem='" + (slot_height * itteration) + "' title='" + str_time + "' onClick='slotselect(" + active_time + ", " + i + ")' ondragenter='return dragEnter(event)' ondrop='return dragDrop(event)' ondragover='return dragOver(event)'>&nbsp;</div>";
            day_html[i] == undefined ? day_html[i] = template : day_html[i] += template;

        }
        itteration += 1;
        active_time += Number(increment);
    }
    //Place the header, build the days
    document.getElementsByClassName("timeslot-header")[0].innerHTML = head_html;
    for (let i = 0; i < days_to_show; i++) {
        let d = new Date(start_date);
        d = d.addDays(i);
        let day_string = d.toDateString();
        let iso_date = d.toISOString();
        iso_date = iso_date.split("T")[0];
        document.getElementsByClassName("weekly-calendar")[0].innerHTML += `
  <div class="row">
    <div class="row-top">
      <div class="employee-name">Cody</div>
      <div class="day-of-week">`+ day_string + `</div>
    </div>
    <div class="row-body timeslots date`+ iso_date + `">
  
    </div>
  </div>
  `;
        document.getElementsByClassName("timeslots")[i].innerHTML = day_html[i];
    }
    console.warn(appts);
    for (let i = 0; i < appts.length; i++) {
        app = appts[i];
        console.log(app);
        //let t = time of appt
        let d = app.date.split("T")[0];
        let t = app.date.split("T")[1];
        console.log("Time:" + t);
        t = t.split(".")[0];
        let t_array = t.split(":");
        let min = t_array[0] * 60;
        min += Number(t_array[1]);
        min -= open_mins;
        min = min / increment;
        console.log("Slots:" + min);
        let top = min * slot_height + "rem";
        let h = (app.duration / increment) * slot_height + "rem";
        document.getElementsByClassName("date" + d)[0].innerHTML += "<div class='appointment' data-apptid='"+app.id+"' id='apptindex-" + i + "' draggable='true' ondragstart='return dragStart(event)' style='top: " + top + ";height:" + h + "'><div class='content'><strong>" + customer_obj["cid" + app.cid].fname + "&nbsp;" + customer_obj["cid" + app.cid].lname + "</strong><br/><p>" + app.items[0].name + "</p></div></div>"
    }
}
function slotselect(time, day_index) {
    console.log("i" + day_index + "t" + time);
    let el = document.getElementById("i" + day_index + "t" + time);
    if (el.classList.contains('active')) {
        create_appt(time, day_index);
    } else {
        try {
            document.querySelector(".slot.active").classList.remove('active');
        } catch {
            console.warn("No active slots found");
        }
        el.classList.add('active');
    }
}
function populateServiceDataList() {
    let html = "";
    for(let i = 0; i < services.length; i++)
    {
        html += "<option value='"+services[i].name+"'></option>"
    }
    return html;
}