//Request Functions
function requestInfo(url, data = null, method = "POST", async = true) {
    return new Promise(function (resolve, reject) {
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, async);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };
        let datastring;
        if (data) {
            datastring = Object.keys(data).map(function (key) {
                return encodeURIComponent(key) + "=" + encodeURIComponent(data[key]);
            }).join("&");
            console.log(datastring);
        }
        xhr.send(datastring);
    });
}
function apiCall(data, method = "POST", async = true) {
    return new Promise(function (resolve, reject) {
        let xhr = new XMLHttpRequest();
        xhr.open(method, "./api.php", async);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };
        let datastring;
        if (data) {
            datastring = Object.keys(data).map(function (key) {
                return encodeURIComponent(key) + "=" + encodeURIComponent(data[key]);
            }).join("&");
            console.log(datastring);
        }
        xhr.send(datastring);
    });
}
function refreshData() {
    //Get all data via Promise
    createBadge("Refreshing Your Data");
    Promise.all([
        apiCall({ ssid: ssid, fn: 'getAppts', startdate: zeroTime(new Date(start_date).toISOString()), enddate: zeroTime(new Date(start_date).addDays(days_to_show).toISOString()) }),
        apiCall({ ssid: ssid, fn: 'getCusts' }),
        apiCall({ ssid: ssid, fn: 'getServs' })
    ]).then(function (ret) {
        try {
            G_appts = JSON.parse(ret[0]);
            G_custs = JSON.parse(ret[1]);
            G_servs = JSON.parse(ret[2]);
        } catch {
            console.error("Refreshing Data Failed");
        }
        Promise.all([mapById(G_appts, true),mapById(G_custs), mapById(G_servs)]).then(function (res) {
            O_appts = res[0];
            O_custs = res[1];
            O_servs = res[2];
            console.log("Objects Created");
            console.log(O_appts);
            createBadge("Data loaded, refreshing Calendar")
            calendarRefresh();
        })
    }).catch(function (err) {
        console.log(err);
        alert("There was an error refreshing your information");
    });
}
function updateAppt(obj) {
    let app;
    if (!obj.id && !obj.index) {
        createBadge("The Appt Was not created, Please try again.", "red");
        return;
    } else {
        if (obj.index) {
            app = G_appts[obj.index];
        } else if (obj.id) {
            for (let i = 0; i < G_appts.length; i++) {
                if (G_appts.id == obj.id) {
                    app = G_appts[i];
                    break;
                }
            }
        }
        if (obj.date) {
            app.date = obj.date;
        }
        //Actually Update the Database
        apiCall({fn: 'updateAppt', key: "date", val: app.date, appt_id: app.id})
            .then(function(res) {
                console.error(res);
            });
    }
}

function createCustomer_alpha() {
    let fname = prompt("Customer First Name: ");
    if (!fname) { return; }
    let lname = prompt("Customer Last Name: ");
    if (!lname) { return; }
    let email = prompt("Customer Email: ");
    let phone = prompt("Customer Phone: ");
    let data = {
        fn: 'createCustomer',
        fname: fname,
        lname: lname,
        email: email,
        phone: phone
    };
    apiCall(data).then(function (res) {
        console.log(res);
        alert("Created");
    });
}
function createService_alpha() {
    let data = {
        fn: 'createService',
        name: prompt("Service Name: "),
        price: prompt("Service Price: "),
        duration: prompt("Service Duration: ")
    };
    apiCall(data).then(function (res) {
        console.log(res);
        alert("Created");
    });
}
function mapById(arr, appt=false) {
    let o = {};
    for(let i=0; i<arr.length; i++)
    {
        if(appt)
        {
            arr[i].items = JSON.parse(arr[i].items);
        }
        o[arr[i].id] = arr[i];
    }
    return o;
}