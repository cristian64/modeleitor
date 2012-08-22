var map;

var latitud = 38.22808209515297;
var longitud = -0.7486528351306561;

//https://maps.google.es/maps?q=Calzados+JAM&sll=38.281431,-0.719326&sspn=0.01297,0.033023&t=h&hq=Calzados+JAM&z=16&iwloc=A

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
        ['<img src="css/logo.png" title="Club Padel Matola" alt="Club Padel Matola" width="300px" /><br /><strong>Club Padel Matola</strong><br />Vereda Santa Teresa, 23<br />Elche (Alicante)<br />03296<br /><br /><a href="mapagrande.php" class="freshbutton-lightblue">Pantalla completa</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />', latitud, longitud, 1],
        ];
        
        if (fullscreen)
        {
        	locations = [
        ['<img src="css/logo.png" title="Club Padel Matola" alt="Club Padel Matola" width="300px" /><br /><strong>Club Padel Matola</strong><br />Vereda Santa Teresa, 23<br />Elche (Alicante)<br />03296<br /><br /><a href="mapa.php" class="freshbutton-lightblue">Tamaño normal</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />', latitud, longitud, 1],
        ];
        }

        var map = new google.maps.Map(document.getElementById(divid), {
        zoom: 17,
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
            new google.maps.LatLng(38.22785625175806, -0.7487683455467504),
            new google.maps.LatLng(38.22780963379814, -0.7487206486225659),
            new google.maps.LatLng(38.227784217779146, -0.7486686369657036),
            new google.maps.LatLng(38.227771443921675, -0.7486085786819103),
            new google.maps.LatLng(38.227758274994066, -0.7482771504401171),
            new google.maps.LatLng(38.228405915311875, -0.7484193075179064),
            new google.maps.LatLng(38.22828792266518, -0.749028519821195),
            new google.maps.LatLng(38.227985699680644, -0.7489091615200323),
            new google.maps.LatLng(38.22791044062793, -0.748873622250585),
            new google.maps.LatLng(38.22791653183387, -0.7488048906445783)
        ];

        var recinto = new google.maps.Polygon({
            paths: poligono,
            strokeColor: "#00E043",
            strokeOpacity: 0.7,
            strokeWeight: 2,
            fillColor: "#00E043",
            fillOpacity: 0.60,
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
                    contenido = contenido + "new google.maps.LatLng(" + paths.getAt(i).lat() + ", " + paths.getAt(i).lng() + "),<br />";
                }
                infowindow.setContent(contenido);
            }
            infowindow.open(map, marker);
        });

    }
}
