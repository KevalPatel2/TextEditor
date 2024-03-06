
<?php
/*
FileName: startPage.php
Project: Assignment 6 - A Basic Text Editor using jQuery and JSON
Done By: Keval Patel (8864341) & Jaygiri Goswami (8866697)
Date: 26/11/2023
Description: This PHP file serves as the backend for an Online Text Editor. It handles various HTTP requests, including GET 
			 and POST, utilizing JSON data interchange format. The file manages actions such as listing available files, 
			 loading file contents, saving files, and creating new files within the 'MyFiles' directory. Additionally, 
			 it provides error handling and sends JSON-formatted responses to the client based on the received requests.

*/
header('Content-Type: application/json');
/*
* Function Name: sendJsonResponse()
* Description: This function takes in a piece of data and encodes it into JSON format using json_encode(). It then echoes (outputs) the JSON-encoded data as the response. After sending the response, the function terminates script execution using exit;.
* Parameters: $data (mixed): The data to be encoded into JSON format and sent as a response.
* Return Value: None
*/
function sendJsonResponse($data) {
    echo json_encode($data);
    exit;
}

/*
* Function Name: getJsonInput()
* Description: This function retrieves raw input from the request body using 'php://input'. It then decodes the JSON-formatted
			   input data using json_decode() with the second parameter set to true, which converts the JSON string into an 
			   associative array.
* Parameters: None
* Return Value: Associative array (mixed): The decoded JSON data obtained from the request body.
*/
function getJsonInput() {
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}

// Handling GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];

	 // Handling different actions for GET requests
    if ($action === 'listFiles') {
		// Retrieve and list files from MyFiles directory
        $files = scandir('MyFiles');
        $files = array_diff($files, ['.', '..']);
        sendJsonResponse(['files' => $files]);
    } elseif ($action === 'loadFile') {
		// Load the content of a specific file
        $filename = isset($_GET['filename']) ? $_GET['filename'] : '';
        $fileContents = file_get_contents("MyFiles/{$filename}");
        sendJsonResponse(['content' => $fileContents]);
    } else {
		// Send an error response for an invalid action
        sendJsonResponse(['error' => 'Invalid action']);
    }
	// Handling different actions for POST requests
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'saveFile') {
		// Save the content to a file
        $filename = isset($_POST['filename']) ? $_POST['filename'] : '';
        $content = isset($_POST['content']) ? $_POST['content'] : '';
        file_put_contents("MyFiles/{$filename}", $content);
        sendJsonResponse(['success' => true]);
    } elseif ($action === 'createFile') {
		 // Create a new file or send an error if the file already exists
        $filename = isset($_POST['filename']) ? $_POST['filename'] : '';
        $newFilePath = "MyFiles/{$filename}";

        if (!file_exists($newFilePath)) {
            file_put_contents($newFilePath, '');
            sendJsonResponse(['success' => true]);
        } else {
            sendJsonResponse(['error' => 'File already exists']);
        }
    } else {
        sendJsonResponse(['error' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    $jsonData = getJsonInput();

    if (isset($jsonData['action'])) {
        $action = $jsonData['action'];
		
		// Handling different actions for POST requests with JSON content type
        if ($action === 'saveFile') {
			// Save the content to a file from JSON input
            $filename = isset($jsonData['filename']) ? $jsonData['filename'] : '';
            $content = isset($jsonData['content']) ? $jsonData['content'] : '';

            file_put_contents("MyFiles/{$filename}", $content);
            sendJsonResponse(['success' => true]);
        } elseif ($action === 'createFile') {
			// Create a new file or send an error if the file already exists from JSON input
            $filename = isset($jsonData['filename']) ? $jsonData['filename'] : '';
            $newFilePath = "MyFiles/{$filename}";

            if (!file_exists($newFilePath)) {
                file_put_contents($newFilePath, '');
                sendJsonResponse(['success' => true]);
            } else {
                sendJsonResponse(['error' => 'File already exists']);
            }
        } else {
            sendJsonResponse(['error' => 'Invalid action']);
        }
    } else {
        sendJsonResponse(['error' => 'Invalid request']);
    }
} else {
    sendJsonResponse(['error' => 'Invalid request']);
}
