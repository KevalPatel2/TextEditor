<!--
FileName: startPage.php
Project: Assignment 6 - A Basic Text Editor using jQuery and JSON
Done By: Keval Patel (8864341) & Jaygiri Goswami (8866697)
Date: 26/11/2023
Description: This file sets up the structure of an Online Text Editor interface with options to select, edit, save, and 
			 create files. It links necessary stylesheets and JavaScript files for functionality and styling. The interface 
			includes a text area for editing content and buttons to perform file-related actions using JavaScript functions.
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Text Editor</title>
	
	<!-- Link to external stylesheet for styling -->
    <link rel="stylesheet" href="editor.css">
</head>
<body>
    <div class="main-container">

        <h1>Online Text Editor</h1>
        <span>
            <label for="fileList">Select a file:</label>				<!-- Label for file selection dropdown -->
            <select id="fileList"></select>								<!-- Dropdown to display available files -->
        </span>
    
        <div class="container">
            <textarea  id="editorArea"></textarea>						<!-- Text area for editing content -->
        </div>
		
        <span>
			<!-- Button to save the file (initially disabled) -->
            <button class="button2" id="saveButton" onclick="saveFile()" title="Click this button to save your file"disabled>Save File</button>
			
			<!-- Button to create a new file -->
            <button class="button2" onclick="createFile()" title="Click this button to create a new file">Save As</button>
        </span>
		
			<div id="charCount">Characters: 0</div> 					<!-- Display character count -->
    </div>

	<!-- Link to jQuery library-->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	
	<!-- Link to external JavaScript functionality-->
    <script src="main.js"></script>
</body>
</html>
