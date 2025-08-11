async function init_map_location() {

	//map settings
	const map_containers = document.querySelectorAll('.map-container');
	
	//if map containers are set
	if(map_containers) {
		
		//define interactivity from first map loaded - defaults to true, controls zoom and other options
		const map_interactivity = map_containers[0].dataset.mapInteractive  === 'false' ? false : true ?? true;
		
		const map_options = {
				    center: { lat: 0, lng: 0 },
				    zoom: 6,
				    mapId: "1e864cb42e1a7895",
				    disableDefaultUI: true,
				    backgroundColor: '#f7f7f7',
				    mapTypeControl: map_interactivity,
				    zoomControl: map_interactivity,
				    streetViewControl: map_interactivity,
			    };

		//request libraries for map
		const { Map } = await google.maps.importLibrary("maps");
		const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
		const { PlacesService } = await google.maps.importLibrary("places");

		//setup map with settings - if a single item, such as getting a container with an ID
		// const map = new Map(map_container, map_options);

		//setup map with settings - for multiple map containers on a single page
		map_containers.forEach(map_item => {

			const markers = map_item.querySelectorAll('.map-marker'),
				  map = new Map(map_item, map_options),
				  bounds = new google.maps.LatLngBounds();

			//create an info window to share between markers.
			const infoWindow = new google.maps.InfoWindow();
			
			//initialise Place details request
			const service = new PlacesService(map);

			markers.forEach(marker => {

				//DOM marker properties
				const lat = parseFloat(marker.getAttribute('data-lat')),
					  lng = parseFloat(marker.getAttribute('data-lng')),
					  marker_place = marker.getAttribute('data-place-id'),
					  marker_title = marker.getAttribute('aria-label'),
					  marker_content = marker.innerHTML;
				
				//create new marker pin
				const pin = new PinElement({
					background: '#407060',
					borderColor: '#407060',
					glyphColor: '#FFFFFF',
					scale: 1
				});

				//create marker
				marker = new AdvancedMarkerElement({
					map,
					position: { 
						lat: lat,
						lng: lng
					},
					content: pin.element,
					title: marker_title,
				});	  

				bounds.extend(marker.position);

				//add a click listener for each marker, and set up the info window.
				marker.addListener("click", ({ domEvent, latLng }) => {

					infoWindow.close(); //close all infowindows

					//zoom into location
					if( markers.length == 1 ) {
						map.setZoom(14);
					} else {
						map.setZoom(8);
					}		

					//pan to map location
					map.panTo(marker.position);
					
					// Fetch place details using the Place ID
                    const request = {
                        placeId: marker_place,  // Use the marker's place ID
                        fields: ["name", "formatted_address", "website", "formatted_phone_number"]
                    };
					
					//set infowindow details
					service.getDetails(request, (place, status) => {
						
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
														
                            // Customize info window content with place details
                            const placeDetails = `
                                <div class="map-marker-details">
                                    <h3>${place.name}</h3>
                                    <p><strong>Address:</strong> ${place.formatted_address}</p>
                                    <p><strong>Website:</strong> <a href="${place.website}" target="_blank">${place.website}</a></p>
									<p><strong>Telephone:</strong> <a href="tel:${place.formatted_phone_number}" target="_blank">${place.formatted_phone_number}</a></p>
                                </div>
                            `;
							
                            infoWindow.setContent(placeDetails);
                            infoWindow.open(map, marker);
							
                        } else {
							
                            //fallback to original content if no place details are found
                            infoWindow.setContent(marker_content);
                            infoWindow.open(marker.map, marker);
							
                        }
						
                    });
					
					

				});

			});

			//if singular map marker
			if( markers.length == 1 ) {

				map.setZoom(14);

			} else {

				//fit map around markers
				map.fitBounds(bounds);

			}

			//center map around marker
			map.setCenter(bounds.getCenter());

		});
		
	}

}