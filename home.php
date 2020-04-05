<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./scss/cal.css">
    <script src="https://kit.fontawesome.com/038373e252.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Homemade+Apple|Old+Standard+TT:700|Share+Tech+Mono" rel="stylesheet">
    <!--Tail.DateTime-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tail.datetime@0.4.13/css/tail.datetime-harx-light.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tail.datetime@0.4.13/css/tail.datetime-harx-light.map">
    <title>Calendar Project</title>
</head>

<body onscroll="fixSidebar()">
    <header>
        <div id="title_box" class='flex-col'>
            <h1>Calendar V1.2</h1>
        </div>
        <div class="box-container">
            <div class="box_button" title="Customers" onClick="createCustomer_alpha()"><i class="fas fa-users"></i></div>
            <div class="box_button" title="Products" onClick="#"><i class="fas fa-shopping-bag"></i></div>
            <div class="box_button" title="Services" onClick="createService_alpha()"><i class="fas fa-exclamation-circle"></i></div>
            <div class="box_button" title="Logout" onClick="requestInfo('/api.php',{fn:'logout'}).then(function(){createBadge('Goodbye!');setTimeout(function(){window.location.reload()},1000)})"><i class="fas fa-power-off"></i></div>
        </div>
    </header>
    <aside id="sidebar" class="sidebar flex-col">
        <div class="box_button">
            <i class="fa fa-calendar"></i>
        </div>
        <div class="box_button">
            <i class="fa fa-list"></i>
        </div>
        <div class="box_button">
            <i class="fa fa-plus"></i>
        </div>
    </aside>
    <div id="calendar_headbar">
        <div id="cal_appt_date">
            <div><h3>Appointment Date</h3></div>
            <div>
                <i class="fa fa-arrow-left" onClick="moveBackDays();"></i>
                <span id="cal_date_range">tody</span>
                <i class="fa fa-arrow-right" onClick="moveForwardDays();"></i>
            </div>
        </div>
        <div id="cal_days_to_show">
            <h3>Days To Show</h3>
            <div><select id="cal_days_to_show_select" onchange="changeDaysToShow(value)">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
            </select></div>
        </div>
        <div id="employees_to_show">
            <h3>Employees To Show</h3>
            <div>
                <select name="" id="">
                    <option value="Cody">Cody</option>
                </select>
            </div>
        </div>
    </div>
    <div class="weekly-calendar">
        <div id="Side-Guide" class="row" style='width: 7rem;border:none;'>
            <div class="row-top">
                <div>&nbsp;</div>
                <div>&nbsp;</div>
            </div>
            <div class="timeslot-header">

            </div>
        </div>
    </div>
    <div id='btn-container' class="lower-right flex-col">
        <div class="btn-round" id="configuration-button" onClick="openConfiguration()">
            <i class="fas fa-cog"></i>
        </div>
        <div class="btn-round" id='add-appt' onClick="openApptCreator()">
            <i class="fas fa-plus"></i>
        </div>
    </div>
    <div id="badge-container" class="lower-right" style="right:6rem;">
        <div class="badge">
            <i class="fas fa-exclamation-circle"></i>
            This would be a sample badge
        </div>
    </div>

    <?php //Overlay ?>
    <div class="overlay hidden" id="overlay" onClick="if(event.target.classList.contains('overlay'))event.target.classList.add('hidden')">
        <div class="overlay_container hidden" id="config_popup" onClick="event.preventDefault();">
            <h2><u>Options</u></h2>
            <br/>
            <div class="input-box">
                <label for="opt_start_date">Start Date</label>
                <input type="text" class="input-calendar" id="opt_start_date" class="datetime-field" style="width:5rem;text-align: center;">
            </div>
            <div class="input-box">
                <label for="dts_input"><span id='dts'></span> Days showing</label>
                <input id="dts_input" type="range" min=1 max=14 oninput="document.getElementById('dts').innerHTML = value" />
            </div>
            <div class="input-box">
                <label for="interval_input">Time Interval</label>
                <select id="interval_input">
                    <option value=15>15 Mins</option>
                    <option value=30>30 Mins</option>
                    <option value=45>45 Mins</option>
                    <option value=60>60 Mins</option>
                </select>
            </div>
            <button class='btn-success float-right' onClick="applyOptions()">Apply</button>
        </div>
        <div class="overlay_container hidden" id="create_appt_popup" onClick="event.preventDefault();">
        <div class="ticket">
            <h1 id="editor_customer_name" onClick='pickACustomer()'>Choose A Customer</h1>
            <div class="meta">
                <div class="meta-item">
                <h2 class="meta-item-label">
                    Date
                </h2>
                <div class="flex_container nowrap">
                    <input type="text" class="input-calendar" id="editor_appt_date" class="datetime-field" style="width:5rem;text-align: center;">
                    <div class="flex_container nowrap">
                        <input type="text" list="datalist_hr" id="editor_appt_hr" style="width:3rem;text-align: right;" onClick="this.value = null; this.click();">
                        <input type="text" list="datalist_min" id="editor_appt_min" style="width:3rem" onClick="this.value = null;this.click();">
                        <input type="text" list="datalist_tod" id="editor_appt_tod" style="width:3rem" onClick="this.value = null;this.click();">
                        <datalist id="datalist_hr">
                            <option value="1"></option>
                            <option value="2"></option>
                            <option value="3"></option>
                            <option value="4"></option>
                            <option value="5"></option>
                            <option value="6"></option>
                            <option value="7"></option>
                            <option value="8"></option>
                            <option value="9"></option>
                            <option value="10"></option>
                            <option value="11"></option>
                            <option value="12"></option>
                        </datalist>
                        <datalist id="datalist_min">
                            <option>00</option>
                            <option>15</option>
                            <option>30</option>
                            <option>45</option>
                        </datalist>
                        <datalist id="datalist_tod">
                            <option>AM</option>
                            <option>PM</option>
                        </datalist>
                    </div>
                </div>
                </div>
                <div class="meta-item">
                <h2 class="meta-item-label">
                    Employee
                </h2>
                <select name="Employee" id="editor_appt_employee" style="width: 80%;margin-left: 10%;">
                    <option value="Cody">Cody</option>
                </select>
                </div>
                <div class="meta-item">
                <h2 class="meta-item-label">
                    Ticket ID
                </h2>
                <h2 class="ticket-id" id="editor_ticket_id">46126</h2>
                </div>
            </div>
            <h3 class="item-header">
                Products | Services | Packages
            </h3>
            <div class="order-list-container">
                <div class="order-list">
                    <div class="item_header">Item ID</div>
                    <div class="item_header">Item Name</div>
                    <div class="item_header">Price</div>
                    <div class="item_header">Duration</div>
                    <div class="item_header">Qty</div>
                    
                </div>
                <div id="additem" class="btn-round lower-right" style="position: absolute;bottom:5rem;" onClick="searchForItem()">
                    <i class="fas fa-plus"></i>
                </div>
                <div id="additem" class="btn-round lower-right" style="position: absolute;" onClick="saveAppointment()">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            
            </div>
            <!-- <h2><u>Create Appointment</u></h2>
            <br/>
            <div class="input-box">
                <label for="customer_name">Customer: </label>
                <input id="customer_name" list="customer_datalist" name="customer_name">
                <datalist id="customer_datalist">
                </datalist>
            </div>
            <div class="input-box">
                <label for="service_list">Products & Services: </label>
                <input id="service_list" list="service_datalist" name="service_list">
                <datalist id="service_datalist">
                </datalist>
            </div>
            <div class="itemList" id="itemList">
            </div>
            <button class='btn-success float-right' onClick="create_appointment()">Create Appointment</button> -->
        </div>
    </div>
    <?php //End Overlay ?>
    <div class="longBar" id="longbar">
        <div id="hideLongBar" onclick="hideLongBar()"><i class="fas fa-sort-up"></i></div>
        <h3 id='longbar_header'>Longbar Header</h3>
        <input type='text' id='longbar_input' />
    </div>
    <footer>
        <script src="./js/db.js"></script>
        <script src="./js/util.js"></script>
        <script src="./js/cal.js"></script>
        <script src="./js/ticket.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tail.datetime@latest/js/tail.datetime.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/tail.datetime@latest/langs/tail.datetime-de.js"></script>
        <script>
            //Global Vars
            let open_mins = 540 // 9:00 AM or 9*60
            let close_mins = 1140 // 7:00 PM or 19*60
            let increment = 15;
            let days_to_show = 3;
            let slot_height = 1.75;
            let start_date = new Date();
            let ssid = "<?php echo $ssid; ?>";
            let item_storage_container = ""; //Used to detect if user clicks on datalist option for products & services
            let items = []; //Used for storing products services and packages on ticket creations.
            let products = [];
            let cart = {}; //Used for ticket storage;
            document.addEventListener("DOMContentLoaded", function(){
                refreshData();
                //Set event listeners
                // let service_input_list = document.getElementById("service_list");
                // service_input_list.addEventListener("keyup", function(event) {
                //     if(event.keyCode == 13)
                //     {
                //         addToTicket(service_input_list.value);
                //     } else {
                //         item_storage_container = service_input_list.value;
                //     }
                // })
                // service_input_list.addEventListener("change", function(event) {
                //     if(item_storage_container.length < service_input_list.value.length - 2)
                //     {
                //         addToTicket(service_input_list.value);
                //     }
                // });
                //Set the DatePickers
                tail.DateTime(".input-calendar", {
                    position: "bottom",
                    dateFormat: "mm-dd-YYYY",
                    timeFormat: false,
                    zeroSeconds: true
                });
                document.getElementById("cal_days_to_show_select").value = days_to_show;
            })
        </script>
    </footer>
</body>

</html>