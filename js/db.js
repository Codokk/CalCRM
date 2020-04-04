//Request Functions
function requestInfo (url, data = null, method = "POST", async = true) {
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
      if(data) {
          datastring = Object.keys(data).map(function(key) {
              return encodeURIComponent(key) + "=" + encodeURIComponent(data[key]);
          }).join("&");
          console.log(datastring);
      }
      xhr.send(datastring);
    });
  }
function refreshData() {
    createBadge("Refreshing Your Data");
    Promise.all([
        requestInfo("/api.php", {ssid: ssid, fn: 'getAppts', startdate: new Date(start_date).toISOString()}),
        requestInfo("/api.php", {ssid: ssid, fn: 'getCusts'}),
        requestInfo("/api.php", {ssid: ssid, fn: 'getServs'})
    ]).then(function(ret) {
        console.log(ret);
        try
        {
            appts = JSON.parse(ret[0]);
            customers = JSON.parse(ret[1]);
            services = JSON.parse(ret[2]);
        } catch {
            console.error("Refreshing Data Failed");
        }
        Promise.all([customer_object_creator(customers),service_object_creator(services)]).then(function(res) {
            console.log("Objects Created");
            console.log(appts);
            createBadge("Data loaded, refreshing Calendar")
            calendarRefresh();
        })
    }).catch(function(err) {
        console.log(err);
        alert("There was an error refreshing your information");
    });
}
function customer_object_creator(cust) {
    return new Promise(function (resolve, reject) {
        let cust_obj = {};
        for(let i = 0; i < cust.length; i++)
        {
            cust_obj["cid"+cust[i].id] = cust[i];
        }
        customer_obj = cust_obj;
        resolve();
    })
}
function service_object_creator(serv) {
    return new Promise(function (resolve, reject) {
        let serv_obj = {};
        for(let i = 0; i < serv.length; i++)
        {
            serv_obj["servid"+serv[i].id] = serv[i];
        }
        service_obj = serv_obj;
        resolve();
    })
}
function updateAppt(obj)
{
    let app;
    if(!obj.id && !obj.index)
    {
        createBadge("The Appt Was not created, Please try again.", "red");
        return;
    } else {
        if (obj.index) {
            app = appts[obj.index];
        } else if (obj.id) {
            for(let i = 0; i < appts.length; i++)
            {
                if(appts.id == obj.id)
                {
                    app = appts[i];
                    break;
                }
            }
        }
        if(obj.date) {
            app.date = obj.date;
        }
        //Actually Update the Database
    }
}

function createCustomer_alpha() {
    let fname = prompt("Customer First Name: ");
    if(!fname){return;}
    let lname = prompt("Customer Last Name: ");
    if(!lname){return;}
    let email = prompt("Customer Email: ");
    let phone = prompt("Customer Phone: ");
    let data = {
        fn: 'createCustomer',
        fname: fname,
        lname: lname,
        email: email,
        phone: phone
    };
    requestInfo("./api.php", data).then(function(res) {
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
    requestInfo("./api.php", data).then(function(res) {
        console.log(res);
        alert("Created");
    });
}