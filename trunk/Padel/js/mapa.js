var map;

var latitud = 38.23983567865117;
var longitud = -0.7516475214957836;

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
        zoom: 18,
        center: new google.maps.LatLng(latitud, longitud),
        mapTypeId: google.maps.MapTypeId.HYBRID
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {  
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                visible: false
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
        
        var poligono = [
            new google.maps.LatLng(38.23947978397542, -0.7520111362457556),
            new google.maps.LatLng(38.23975365303111, -0.7512839067458117),
            new google.maps.LatLng(38.24015365328609, -0.7513697374342883),
            new google.maps.LatLng(38.24019157332692, -0.7517482797622961)
        ];

        var recinto = new google.maps.Polygon({
            paths: poligono,
            strokeColor: "#00E043",
            strokeOpacity: 0.7,
            strokeWeight: 2,
            fillColor: "#00E043",
            fillOpacity: 0.35,
            map: map
        });
        
        //recinto.setEditable(true);
        
        google.maps.event.addListener(recinto, "click", function(evt) {
            if (recinto.getEditable())
            {
                var contenido = "";
                
                var bounds = new google.maps.LatLngBounds();
                for (i = 0; i < poligono.length; i++)
                    bounds.extend(poligono[i]);
                map.setCenter(bounds.getCenter());
                
                contenido = contenido + map.getCenter().lat() + ", " + map.getCenter().lng() + "<br /><br />";
                
                var paths = recinto.getPath();
                for (var i = 0; i < paths.getLength(); i++)
                {
                    contenido = contenido + "(" + paths.getAt(i).lat() + ", " + paths.getAt(i).lng() + ") ";
                }
                infowindow.setContent(contenido);
            }
            infowindow.open(map, marker);
        });

    }
}
