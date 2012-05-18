var map;

var latitud = 38.278138;
var longitud = -0.719731;
function GoogleMaps3(fullscreen, divid)
{
    document.isLoaded=true;
		
    /*************************************************
    // Comprobamos si existe la capa.
    *************************************************/
    if (document.getElementById(divid))
    {
        /*************************************************
        // Creamos el mapa y lo incrustamos en el código HTML.
        *************************************************/
        if (fullscreen)
        {
            document.getElementById(divid).style.height = document.documentElement.clientHeight+"px";
            setInterval (function() { document.getElementById(divid).style.height = document.documentElement.clientHeight+"px"; }, 2000);
        }
        var locations = [
        ['Club de Padel en Matola', latitud, longitud, 1],
        ];

        var map = new google.maps.Map(document.getElementById(divid), {
        zoom: 14,
        center: new google.maps.LatLng(latitud, longitud),
        mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {  
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
                }
            })(marker, i));
            
            infowindow.setContent(locations[i][0]);
            infowindow.open(map, marker);
        }
    }
}
