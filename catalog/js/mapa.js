var map;

var latitud = 38.278106;
var longitud = -0.719725;

//https://maps.google.es/maps?q=Calzados+JAM&sll=38.281431,-0.719326&sspn=0.01297,0.033023&t=h&hq=Calzados+JAM&z=16&iwloc=A

function GoogleMaps3(divid)
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
        var enlace = '<a class="btnazul" href="https://maps.google.es/maps?q=Calzados+JAM&sll=38.281431,-0.719326&sspn=0.01297,0.033023&t=h&hq=Calzados+JAM&z=16&iwloc=A">Ver en Google Maps</a>';
        var locations = [
        ['<div><img src="css/calzadosjam_logo.png" title="Calzados JAM" alt="Calzados JAM" width="190px" /><br /><br />Calle Almansa, 65<br />Elche (Alicante)<br />03206<br /><br />' + enlace + '<br /><br /></div>', latitud, longitud, 1],
        ];

        var map = new google.maps.Map(document.getElementById(divid), {
            zoom: 17,
            center: new google.maps.LatLng(38.279, -0.7199),
            mapTypeId: google.maps.MapTypeId.HYBRID
        });

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {  
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                visible: true
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
