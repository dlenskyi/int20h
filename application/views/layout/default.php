<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f1f1f1;
            padding: 20px;
            font-family: Arial;
        }

        /* Center website */
        .main {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            font-size: 50px;
            word-break: break-all;
        }

        .row {
            margin: 10px -16px;
        }

        /* Add padding BETWEEN each column */
        .row,
        .row > .column {
            padding: 8px;
        }

        /* Create three equal columns that floats next to each other */
        .column {
            float: left;
            width: 33.33%;
            display: none; /* Hide all elements by default */
        }

        /* Clear floats after rows */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Content */
        .content {
            background-color: white;
            padding: 10px;
        }

        /* The "show" class is added to the filtered elements */
        .show {
            display: block;
        }

        /* Style the buttons */
        .btn {
            border: none;
            outline: none;
            padding: 12px 16px;
            background-color: white;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #ddd;
        }

        .btn.active {
            background-color: #666;
            color: white;
        }
    </style>
</head>
<body>
    <?php echo $content;?>
</div>
<div class="footer">
    <hr>
    <p>&copy; 2019 <a href="https://www.facebook.com/profile.php?id=100004512672168&ref=bookmarks">vbudnik</a><p>
</div>
</body>
</html>

