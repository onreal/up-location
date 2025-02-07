function sendLocationToServer(position) {
    if (!position || !position.coords) {
        console.error('No position data available');
        return Promise.reject('No position data available');
    }

    const data = {
        action: 'store_user_location',
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
        nonce: up_location_ajax_object.nonce
    };

    console.log('Sending location to server:', data);

    // Convert data to a URL-encoded format for `fetch`
    const formData = new URLSearchParams(data);

    return fetch(up_location_ajax_object.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: formData.toString()
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse JSON response if applicable
        })
        .then(data => {
            console.log('Location saved on server:', data);
            return data;  // Resolve with data to indicate completion
        })
        .catch(error => {
            console.error('Error sending location to server:', error);
            throw error;  // Reject on error to handle downstream
        });
}


jQuery(document).ready(function($) {
    function handleGeolocationError(error) {
        if (error.code === error.PERMISSION_DENIED) {
            const data = {
                'action': 'store_user_location',
                'denied': true,
                'nonce': up_location_ajax_object.nonce
            };

            //console.log('Location access denied.');

            $.post(up_location_ajax_object.ajax_url, data, function(response) {
                // Optional: Notify the user about denied location access and how to enable it
            });
        } else {
            console.error('Geolocation error:', error);
        }
    }

    let button = document.getElementById('up-location-button');

    if (button) {
        button.addEventListener('click', function() {
            navigator.geolocation.getCurrentPosition(function(position) {
                sendLocationToServer(position)
                    .then(() => {
                        window.location.reload();  // Reload after location is sent
                    })
                    .catch(error => {
                        console.error('Failed to send location:', error);
                    });
            }, handleGeolocationError);
        });
    }

    let isSendingLocation = false;
    let upLocationData = null;

    $(document).on('heartbeat-send', function(e, data) {
        console.log('Sending location via Heartbeat');
        if (isSendingLocation) return;  // Prevents multiple requests
        console.log('Sending location via Heartbeat1');
        if (!navigator.permissions) return;
        isSendingLocation = true;
        console.log('Sending location via Heartbeat2');
        navigator.permissions.query({name: 'geolocation'}).then(permissionStatus => {
            console.log('Sending location via Heartbeat3');
            if (permissionStatus.state !== 'granted') {
                isSendingLocation = false;
                return;
            }

            console.log('Sending location via Heartbeat4');
            navigator.geolocation.getCurrentPosition(position => {
                console.log('Sending location via Heartbeat5');
                upLocationData = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };

                data.up_location = upLocationData;
                isSendingLocation = false;
            }, handleGeolocationError);
        });
    });

    $(document).on('heartbeat-tick', function(e, data) {
        console.log('Sending location via Heartbeat6', data);
        if (data.up_location || upLocationData) {
            const location = data.up_location || upLocationData;
            console.log('User location received via Heartbeat:', location);

            // Send location to server and reset temporary storage
            sendLocationToServer({ coords: location });
            upLocationData = null; // Clear after sending to avoid duplicate sends
        }
    });
});
