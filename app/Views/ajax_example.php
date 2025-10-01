<!DOCTYPE html>
<html>
<head>
    <title>AJAX Example with jQuery</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <h1>AJAX with CodeIgniter 4</h1>
    <button id="load-data-btn">Load Data</button>
    <div id="result"></div>

    <script>
        $(document).ready(function() {
            // Use jQuery's click event handler
            $('#load-data-btn').click(function() {
                // Use jQuery's .ajax() method, which simplifies the process
                $.ajax({
                    url: '/ajax/getData', // The URL to send the request to
                    type: 'GET',         // The HTTP method (GET, POST, etc.)
                    dataType: 'json',    // The type of data we expect back from the server
                    
                    success: function(data) {
                        // This function runs if the request is successful
                        $('#result').html('Data received: ' + data.message);
                    },
                    
                    error: function(xhr, status, error) {
                        // This function runs if there's an error
                        console.error('AJAX Error: ' + status + ' ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>