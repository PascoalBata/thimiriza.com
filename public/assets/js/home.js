var product_conent = "product_content";
var service_conent = "service_content";

var url = window.location.href;

function product() {
    document.getElementById(product_content).style.display = "block";
    document.getElementById(service_content).style.display = "none";
    window.history.replaceState(null, "Thimiriza", url + "/products");
}

function service() {
    document.getElementById(product_content).style.display = "none";
    document.getElementById(service_content).style.display = "block";
    window.history.replaceState(null, "Thimiriza", url + "/services");
}

function show_services() {
    if(document.getElementById('all_services').style.display === 'block'){
        document.getElementById('all_services').style.display = 'none';
    }else{
        document.getElementById('all_services').style.display = 'block'
    }
}