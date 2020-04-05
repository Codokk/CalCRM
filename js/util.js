document.getScroll = function() {
    if (window.pageYOffset != undefined) {
        return [pageXOffset, pageYOffset];
    } else {
        var sx, sy, d = document,
            r = d.documentElement,
            b = d.body;
        sx = r.scrollLeft || b.scrollLeft || 0;
        sy = r.scrollTop || b.scrollTop || 0;
        return [sx, sy];
    }
}
function createBadge(text, color = 'green', icon = 'warn', duration = '5000')
{
    let bid = "bid"+ new Date().getTime();
    let iconObj = {
        warn: '<i class="fas fa-exclamation-circle"></i>'
    }
    let html = "<div id='"+bid+"' class='badge'>";
    html += iconObj[icon];
    html += text;
    html += "</div>";
    document.getElementById("badge-container").innerHTML += html;
    setTimeout(function() {
        document.getElementById(bid).style.opacity = "100%";
    },500)
    setTimeout(function() {
        document.getElementById(bid).style.opacity = '0%';
        setTimeout(function() {
            document.getElementById(bid).remove();
        }, 1000);
    }, Number(duration) + 500)
}
function objectNameComparison(a,b) {
      // Use toUpperCase() to ignore character casing
    const itema = a.name.toLowerCase();
    const itemb = b.name.toLowerCase();

    let comparison = 0;
    if (itema > itemb) {
        comparison = 1;
    } else if (itema < itemb) {
        comparison = -1;
    }
    return comparison;
}
function sortByObjName(obj) {
    return obj.sort(objectNameComparison);
}
function zeroTime(iso) {
    return iso.split("T")[0] + "T00:00:00.00Z";
}
function fixSidebar() {
    let top = (document.getScroll()[1]> 90 ? 90 : document.getScroll()[1]);
    document.getElementById("sidebar").style.top = 90-top+"px";
    document.getElementById("sidebar").style.padding = "0px 0px "+top+"px 0px";
}