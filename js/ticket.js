function removeItem(i) {
    if (confirm("Delete This item?")) {
        cart.items = cart.items.slice(0, i).concat(cart.items.slice(i + 1, cart.items.length));
        refreshCart();
    }
}
function buildItems() {
    for (let i = 0; i < services.length; i++) {
        items.push(services[i]);
    }
    for (let i = 0; i < products.length; i++)
    {
        items.push(products[i]);
    }
    //Add Packages here when added    
    items = sortByObjName(items);
}
function sanitizeLongbar() {
    document.getElementById("longbar_input").remove();
    document.getElementById("longbar").innerHTML += "<input type='text' id='longbar_input' />";
}
function searchForItem() {
    let inpt = document.getElementById("longbar_input");
    document.getElementById("longbar_header").innerHTML = "Search For Item: ";
    inpt.setAttribute("list", "item_search");
    inpt.setAttribute("placeholder", "Item Name Here");
    inpt.addEventListener("keyup", function (e) {
        if (e.keyCode == 13) {
            e.target.setAttribute("disabled", "true");
            createBadge("Finding item...");
            let item = false;
            for (let i = 0; i < items.length; i++) {
                console.log(items);
                if (items[i].name.toLowerCase() == e.target.value.toLowerCase() || items[i].id.toLowerCase() == e.target.value.toLowerCase()) {
                    item = items[i];
                    createBadge("Item Found");
                    break;
                }
            }
            console.log("Done Searching");
            e.target.removeAttribute("disabled");
            if (item) {
                item.qty = prompt("Amount to purchase:", 1);
                cart.items.push(item);
                refreshCart();
                hideLongBar();
            } else {
                console.log("Missing Item");
                setTimeout(() => { createBadge("Item was not found...") }, 1000);
                console.log(items);
            }
        } else {

        }
    })
    document.getElementById("longbar").style.top = '0rem';
    inpt.focus();
}
function pickACustomer() {
    let inpt = document.getElementById("longbar_input");
    document.getElementById("longbar_header").innerHTML = "Please Select a Customer: ";
    inpt.setAttribute("list", "customer_search_list");
    inpt.setAttribute("placeholder", "Customer Name");
    inpt.addEventListener("keyup", function (e) {
        if (e.keyCode == 13) {
            e.target.setAttribute("disabled", "true");
            createBadge("Finding Customer...");
            let cust = false;
            for (let i = 0; i < customers.length; i++) {
                let spl = e.target.value.split(" ");
                if (spl.length > 1) {
                    if (customers[i].fname.toLowerCase() == e.target.value.split(" ")[0].toLowerCase() && customers[i].lname.toLowerCase() == e.target.value.split(" ")[1].toLowerCase()) {
                        cust = customers[i];
                        createBadge("Customer Found");
                        break;
                    }
                }

            }
            console.log("Done Searching");
            e.target.removeAttribute("disabled");
            if (cust) {
                cart.cust = cust;
                cart.cid = cust.id;
                cart.valid.client = true;
                hideLongBar();
                document.getElementById("editor_customer_name").innerHTML = cust.fname + " " + cust.lname;
            } else {
                console.log("Missing Customer");
                setTimeout(() => { createBadge("Customer was not found...") }, 1000);
            }
        } else {

        }
    })
    document.getElementById("longbar").style.top = '0rem';
    inpt.focus();
}
function refreshCart() {
    let del = document.getElementsByClassName("cart_item");
    let duration = 0;
    while (del[0]) {del[0].parentNode.removeChild(del[0]);}
    let html = "";
    for (let i = 0; i < cart.items.length; i++) {
        let item = cart.items[i];
        duration += item.duration;
        console.log(item);
        html += "<div data-index='" + i + "' class='cart_item item_id' onClick='removeItem(" + i + ")'>" + item.id + "</div>";
        html += "<div data-index='" + i + "' class='cart_item item_name'>" + item.name + "</div>";
        html += "<div data-index='" + i + "' class='cart_item item_price'>" + item.price + "</div>";
        html += "<div data-index='" + i + "' class='cart_item item_duration'>" + item.duration + "</div>";
        html += "<div data-index='" + i + "' class='cart_item item_qty'>" + item.qty + "</div>";
    }
    if(html.length > 10)
    {
        cart.valid.items = true;
    }
    cart.duration = duration;
    document.getElementsByClassName("order-list")[0].innerHTML += html;
}
function hideLongBar() {
    document.getElementById('longbar').style.top = "-8rem";
    sanitizeLongbar();
}
function saveAppointment() {
    console.log(cart);
    if(!cart.valid.client)
    {
        createBadge("This ticket needs a valid customer");
        return;
    } else if (!cart.valid.items) {
        createBadge("This ticket needs at least 1 item");
        return;
    } else {
        cart.valid = null;
        cart.fn = "createAppt";
        cart.items = JSON.stringify(cart.items);
        cart.cust = JSON.stringify(cart.cust);
        cart.status = 1;
        requestInfo("./api.php", cart).then(function(res) {
            console.log(res);
        })
    }
}