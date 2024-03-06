/*
* FileName: main.js
* Project: Assignment 6 (A Basic Text Editor using jQuery and JSON)
* Done By: Keval Patel (8864341) & Jaygiri Goswami (8866697)
* Date: 26/11/2023
* Description: The file contains functions to interact with the text editor, such as loading files, saving files, 
* 			   creating files, and updating the save button state based on user actions.
*/
$(document).ready(function () {
	// AJAX request to fetch file list from the server on page load
    $.ajax({
        url: 'editorPage.php',
        method: 'GET',
        data: { action: 'listFiles' },
        dataType: 'json',
        success: function (response) {
            var fileList = $('#fileList');
            fileList.empty();

			//Loop through the retrieved files and populate the dropdown
            $.each(response.files, function (index, filename) {
                fileList.append('<option value="' + filename + '">' + filename + '</option>');
            });

            fileList.val('');
            updateSaveButtonState(); 				// Update save button state based on current file selection
        }
    });

	// Event listener for file list selection change
    $('#fileList').change(function () {
        loadFile();					// Load the content of the selected file
    });	

	// Event listener for text area input changes
    $('#editorArea').on('input', function () {
        updateSaveButtonState();			// Update save button state based on text area content
    });
});


/*
* Function Name: updateSaveButtonState()
* Description: Update save button state based on current selections/content
* Parameters: None
* Return Value: None
*/
 function updateSaveButtonState() {
    var filename = $('#fileList').val();
    var content = $('#editorArea').val().trim();
    var saveButton = $('#saveButton');

    saveButton.prop('disabled', !filename);
}

/*
* Function Name: loadFile()
* Description: Used to load the content of a selected file into the text editor.
* Parameters: None
* Return Value: None
*/
function loadFile() {
    var filename = $('#fileList').val();

    $.ajax({
        url: 'editorPage.php',
        method: 'GET',
        data: { action: 'loadFile', filename: filename },
        dataType: 'json',
        success: function (response) {
			// Update the content of the textarea with the retrieved file content
            $('#editorArea').val(response.content);
            updateSaveButtonState();
        }
    });
}

/*
* Function Name: loadFile()
* Description: Save the content of the editor area to a file on the server.
* Parameters: None
* Return Value: None
*/
function saveFile() {
    var filename = $('#fileList').val();
    var content = $('#editorArea').val();
    $.ajax({
        url: 'editorPage.php',
        method: 'POST',
        data:  JSON.stringify({ action: 'saveFile', filename: filename, content: content }),
        contentType: 'application/json',
        dataType: 'json',
        success: function (response) {
            alert('File saved successfully!');
        }
    });
}

/*
* Function Name: createFile()
* Description: Create a new file in the text editor.
* Parameters: None
* Return Value: None
*/
function createFile() {
    var filename = prompt("Enter the new file name:");
    var content = $('#editorArea').val();

    if (filename !== null && filename !== "") {
		// Check if the filename already exists in the file list
        var fileList = $('#fileList');
		if (fileList.find('option[value="' + filename + '"]').length > 0) {
            alert('File name already exists. Please choose another file name.');
            return; // Exit the function if filename exists
        }
		
        $.ajax({
            url: 'editorPage.php',
            method: 'POST',
            data: JSON.stringify({ action: 'saveFile', filename: filename, content: content }),
            contentType: 'application/json',
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    loadFileList(filename);
                } else {
                    alert('Error creating file. File may already exist.');
                }
            }
        });
    }
}

/*
* Function Name: loadFileList()
* Description: Load and populate the file list in the text editor, and it also updates the state of the save button based on 
			   the user's actions.
* Parameters: selectedFile:  Determine if a specific file from the file list should be selected after loading the file list.
* Return Value: None
*/
function loadFileList(selectedFile) {
    $.ajax({
        url: 'editorPage.php',
        method: 'GET',
        data: { action: 'listFiles' },
        dataType: 'json',
        success: function (response) {
            var fileList = $('#fileList');
            fileList.empty();

            $.each(response.files, function (index, filename) {
                fileList.append('<option value="' + filename + '">' + filename + '</option>');
            });

            if (selectedFile) {
                fileList.val(selectedFile);
                // $('#editorArea').val('');
                updateSaveButtonState(); 
            } else {
                fileList.val('');
                updateSaveButtonState(); 
            }
        }
    });
}

// Add an event listener to track input changes and update character count
$('#editorArea').on('input', function () {
    updateCharCount(); // Call the function to update character count
});

/*
* Function Name: updateCharCount()
* Description: Function to update character count
* Parameters: None
* Return Value: None
*/
function updateCharCount() {
    const text = $('#editorArea').val();
    const count = text.length;
    $('#charCount').text(`Characters: ${count}`); // Update character count display
}

