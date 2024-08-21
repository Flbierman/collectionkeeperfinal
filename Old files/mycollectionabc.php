<?php
// Start the session to track user login status
session_start();
// Check if the user is logged in. If not, redirect to the sign-in page.
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Get the user_id of the logged-in user
$user_id = $_SESSION['user_id'];

// Database connection
$DBConnect = new mysqli("127.0.0.1", "flbierman", "Ilikepie001!", "collection");

// If there is no DB connection, let the admin know 
if ($DBConnect->connect_error) {
    die("Connection to DB failed:" . $DBConnect->connect_error);
}

// Fetch data from the database for display, sorted by title
$games_result = $DBConnect->query("SELECT title, 'Game' as type, game_system as 'system', location FROM games WHERE user_id = $user_id ORDER BY title ASC");
$movies_result = $DBConnect->query("SELECT title, 'Movie' as type, '' as 'system', location FROM movies WHERE user_id = $user_id ORDER BY title ASC");
$books_result = $DBConnect->query("SELECT title, 'Book' as type, '' as 'system', location FROM books WHERE user_id = $user_id ORDER BY title ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Collection - Alphabetical</title>
    <link rel="stylesheet" type="text/css" href="css/collectionstyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .media-section {
            margin-bottom: 20px;
            overflow-x: auto;
            white-space: nowrap;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .media-item {
            display: inline-block;
            vertical-align: top;
            width: 250px;
            height: 350px; /* Adjusted height */
            margin-right: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            text-align: center;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .media-item img {
            width: 100%;
            height: 88%; /* Adjusted height percentage */
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .media-item .details {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            box-sizing: border-box;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .media-item:hover {
            transform: scale(1.05);
        }

        .media-item:hover .details {
            opacity: 1;
        }

        .media-item h3 {
            margin: 5px 0;
            font-size: .9em; /* Adjusted font size */
            white-space: normal;
        }

        .media-item p {
            margin: 3px 0;
            font-size: 0.8em; /* Adjusted font size */
            line-height: 1.2em;
            white-space: normal;
            text-overflow: ellipsis;
        }

        .media-item a {
            color: #ffffff; /* Changed color to white for better contrast */
            text-decoration: none;
            font-size: .9em; /* Adjusted font size */
            margin-top: 5px;
            background-color: #06c; /* Added background color */
            padding: 5px 10px; /* Added padding for better click area */
            border-radius: 5px; /* Added border radius for better appearance */
            transition: background-color 0.3s ease; /* Added transition for hover effect */
            box-shadow: none; /* Removed shadow */
        }

        .media-item a:hover {
            background-color: #004080; /* Darker background color on hover */
        }
        .media-item button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
            font-size: .85em; /* Adjusted font size */
        }

        .media-item button:hover {
            background-color: #d32f2f;
        }

        .media-title-below {
            font-size: .94em; /* Adjusted font size */
            color: #333;
            margin-top: 5px;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0 10px;
            box-sizing: border-box;
            max-height: 40px;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Number of lines to show */
            -webkit-box-orient: vertical;
        }

        /* Media query for smaller screens */
        @media (max-width: 600px) {
            .media-item .details {
                opacity: 1;
                display: none;
            }

            .media-item.active .details {
                display: flex;
            }
        }
    </style>
</head>
<body>
<header>
    <picture class="title-image">
        <source srcset="Images/collectionkeepersmall.jpeg" media="(max-width: 480px)">
        <img src="Images/collectionkeepermedium.jpeg" alt="CollectionKeeper" media="(min-width: 481px)">
    </picture>
</header>
<div class="top-bar">
    <div id="cchandle">
        <a href="https://x.com/CollectionKeep" target="_blank">@CollectionKeeper</a>
    </div>
    <!--Navigation-->
    <div id="nav">
        <a href="index.html">Homepage</a>
        <a href="mycollection.php">MyCollection</a>
        <a href="signin.php">Sign in/up</a>
        <a href="about.html">About</a>
    </div>
</div>
<div id="link-container">
    <br>
    <a href="mycollection.php">My Full Collection</a>
    <a href="mygames.php">My Games</a>
    <a href="mymovies.php">My Movies</a>
    <a href="mybooks.php">My Books</a><br><br>
</div>
<div id="link-container2">
    <br>
    <a href="mycollectionabc.php">My Full Collection - Alphabetical</a>
</div>

<!-- Games Section -->
<div class="media-section" id="games-section">
    <h2>Games - Alphabetical</h2>
    <div id="games-container">
        <!-- Games data will be appended here -->
    </div>
</div>

<!-- Movies Section -->
<div class="media-section" id="movies-section">
    <h2>Movies - Alphabetical</h2>
    <div id="movies-container">
        <!-- Movies data will be appended here -->
    </div>
</div>

<!-- Books Section -->
<div class="media-section" id="books-section">
    <h2>Books - Alphabetical</h2>
    <div id="books-container">
        <!-- Books data will be appended here -->
    </div>
</div>

<hr>

<script>
const OMDB_API_KEY = "aeb6e632";
const GOOGLE_BOOKS_API_KEY = "AIzaSyDRP-bvrnqHuj_uhiHqvAwt_Qb1mtse0KQ";
const RAWG_API_KEY = "e5642732b1c44341bddda23362078348";

function showDetails(title, type, location, system) {
    const normalizedTitle = title.replace(/[^\w\s]/gi, '').trim();
    let url = "";
    
    switch (type) {
        case "Game":
            url = `https://api.rawg.io/api/games?key=${RAWG_API_KEY}&search=${normalizedTitle}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.results && data.results.length > 0) {
                        fetch(`https://api.rawg.io/api/games/${data.results[0].id}?key=${RAWG_API_KEY}`)
                            .then(response => response.json())
                            .then(gameDetails => displayDetails(title, type, gameDetails, location, system, true))
                            .catch(error => console.error('Error fetching game details:', error));
                    } else {
                        displayDetails(title, type, {}, location, system, false);
                    }
                })
                .catch(error => console.error('Error fetching game data:', error));
            break;
        case "Movie":
            url = `https://www.omdbapi.com/?t=${normalizedTitle}&apikey=${OMDB_API_KEY}`;
            fetch(url)
                .then(response => response.json())
                .then(data => displayDetails(title, type, data, location, system, data.Response !== "False"))
                .catch(error => console.error('Error fetching movie data:', error));
            break;
        case "Book":
            url = `https://www.googleapis.com/books/v1/volumes?q=${normalizedTitle}&key=${GOOGLE_BOOKS_API_KEY}`;
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.totalItems > 0) {
                        displayDetails(title, type, data.items[0].volumeInfo, location, system, true);
                    } else {
                        displayDetails(title, type, {}, location, system, false);
                    }
                })
                .catch(error => console.error('Error fetching book data:', error));
            break;
        default:
            break;
    }
}

function displayDetails(title, type, data, location, system, valid) {
    const normalizedTitle = title.replace(/[^\w\s]/gi, '').trim();
    let content = '';

    if (type === "Game") {
        content = `
            <div class="media-item" data-title="${normalizedTitle}" data-type="Game" onclick="toggleDetails(this)">
                <img src="${data.background_image || 'default_image.png'}" alt="${data.name || title}">
                <div class="details">
                    <h3 class="media-title">${data.name || title}</h3>
                    <p>System: ${system}</p>
                    <p>Location: ${location}</p>
                    <a href="${data.website || data.slug ? `https://rawg.io/games/${data.slug}` : '#'}" target="_blank">${data.website ? 'More Info' : data.slug ? 'More Info' : ''}</a>
                    <button onclick="confirmDelete('${title}', 'Game')">Delete</button>
                </div>
                <div class="media-title-below">${data.name || title}</div>
            </div>
        `;
        document.getElementById('games-container').innerHTML += content;
    } else if (type === "Movie") {
        content = valid ? `
            <div class="media-item" data-title="${normalizedTitle}" data-type="Movie" onclick="toggleDetails(this)">
                <img src="${data.Poster || 'default_image.png'}" alt="${data.Title || title}">
                <div class="details">
                    <h3 class="media-title">${data.Title || title}</h3>
                    <p>Location: ${location}</p>
                    <a href="https://www.imdb.com/title/${data.imdbID}" target="_blank">${data.imdbID ? 'More Info' : ''}</a>
                    <button onclick="confirmDelete('${title}', 'Movie')">Delete</button>
                </div>
                <div class="media-title-below">${data.Title || title}</div>
            </div>
        ` : `
            <div class="media-item" data-title="${normalizedTitle}" data-type="Movie" onclick="toggleDetails(this)">
                <div class="details">
                    <h3 class="media-title">${title}</h3>
                    <p>Location: ${location}</p>
                    <p>No Info Available</p>
                    <button onclick="confirmDelete('${title}', 'Movie')">Delete</button>
                </div>
            </div>
        `;
        document.getElementById('movies-container').innerHTML += content;
    } else if (type === "Book") {
        content = valid ? `
            <div class="media-item" data-title="${normalizedTitle}" data-type="Book" onclick="toggleDetails(this)">
                <img src="${data.imageLinks ? data.imageLinks.thumbnail : 'default_image.png'}" alt="${data.title || title}">
                <div class="details">
                    <h3 class="media-title">${data.title || title}</h3>
                    <p>Location: ${location}</p>
                    <a href="${data.infoLink}" target="_blank">${data.infoLink ? 'More Info' : ''}</a>
                    <button onclick="confirmDelete('${title}', 'Book')">Delete</button>
                </div>
                <div class="media-title-below">${data.title || title}</div>
            </div>
        ` : `
            <div class="media-item" data-title="${normalizedTitle}" data-type="Book" onclick="toggleDetails(this)">
                <div class="details">
                    <h3 class="media-title">${title}</h3>
                    <p>Location: ${location}</p>
                    <p>No Info Available</p>
                    <button onclick="confirmDelete('${title}', 'Book')">Delete</button>
                </div>
            </div>
        `;
        document.getElementById('books-container').innerHTML += content;
    }
}

function toggleDetails(element) {
    if (window.innerWidth <= 600) { // Only for small screens
        element.classList.toggle('active');
    }
}

function confirmDelete(title, type) {
    const confirmation = confirm(`Are you sure you want to delete the ${type} titled "${title}"?`);
    if (confirmation) {
        deleteEntry(title, type);
    }
}

function deleteEntry(title, type) {
    const normalizedTitle = title.replace(/[^\w\s]/gi, '').trim();
    fetch(`delete.php?title=${encodeURIComponent(normalizedTitle)}&type=${type}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`${type} titled "${title}" has been deleted successfully.`);
            document.querySelector(`[data-title='${normalizedTitle}'][data-type='${type}']`).remove();
        } else {
            alert(`Failed to delete ${type} titled "${title}".`);
        }
    })
    .catch(error => console.error('Error deleting entry:', error));
}

// Fetch and display all items from the database
document.addEventListener('DOMContentLoaded', () => {
    fetchAllItems();
});

function fetchAllItems() {
    // Fetch games
    <?php if ($games_result && $games_result->num_rows > 0) {
        while ($row = $games_result->fetch_assoc()) {
            $title = htmlspecialchars($row["title"]);
            $system = htmlspecialchars($row["system"]);
            $location = htmlspecialchars($row["location"]);
            echo "displayDatabaseDetails('{$title}', 'Game', '{$system}', '{$location}');";
        }
    } ?>

    // Fetch movies
    <?php if ($movies_result && $movies_result->num_rows > 0) {
        while ($row = $movies_result->fetch_assoc()) {
            $title = htmlspecialchars($row["title"]);
            $location = htmlspecialchars($row["location"]);
            echo "displayDatabaseDetails('{$title}', 'Movie', '', '{$location}');";
        }
    } ?>

    // Fetch books
    <?php if ($books_result && $books_result->num_rows > 0) {
        while ($row = $books_result->fetch_assoc()) {
            $title = htmlspecialchars($row["title"]);
            $location = htmlspecialchars($row["location"]);
            echo "displayDatabaseDetails('{$title}', 'Book', '', '{$location}');";
        }
    } ?>
}

function displayDatabaseDetails(title, type, system, location) {
    showDetails(title, type, location, system);
}
</script>

<div class="chatbox-container" id="chatbox-container">
    <button class="chatbox-toggle" onclick="toggleChatbox()">Chat with AI</button>
    <div id="chatbox">
        <div id="chat-output"></div>
        <div id="chat-input-container">
            <input type="text" id="chat-input" placeholder="Ask the AI about movies...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>

<script>
async function sendMessage() {
    const inputField = document.getElementById('chat-input');
    const userMessage = inputField.value;
    if (!userMessage.trim()) return;

    const chatOutput = document.getElementById('chat-output');
    const userMessageElement = document.createElement('div');
    userMessageElement.className = 'user-message';
    userMessageElement.innerText = `You: ${userMessage}`;
    chatOutput.appendChild(userMessageElement);
    inputField.value = '';

    const aiMessageElement = document.createElement('div');
    aiMessageElement.className = 'ai-message';
    aiMessageElement.innerText = 'AI: ...';
    chatOutput.appendChild(aiMessageElement);

    const response = await fetch('chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: userMessage })
    });

    const data = await response.json();
    aiMessageElement.innerText = `AI: ${data.reply}`;
}

function toggleChatbox() {
    const chatboxContainer = document.getElementById('chatbox-container');
    chatboxContainer.classList.toggle('expanded');
    const chatOutput = document.getElementById('chat-output');
    if (chatboxContainer.classList.contains('expanded')) {
        chatOutput.style.display = 'block';
    } else {
        chatOutput.style.display = 'none';
    }
}
</script>

<style>
 .chatbox-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            transition: height 0.3s ease;
            height: 35px; /* Minimized height */
        }

        .chatbox-container.expanded {
            height: 400px; /* Expanded height */
        }

        .chatbox-toggle {
            background: #06c;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        #chatbox {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #chat-output {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background: #f9f9f9;
            display: none; /* Hidden when minimized */
        }

        .chatbox-container.expanded #chat-output {
            display: block;
        }

        #chat-input-container {
            display: flex;
            border-top: 1px solid #ddd;
            padding-bottom: 20px;
                        background: #fff;
        }

        #chat-input {
            flex: 1;
            border: none;
            padding: 10px;
            font-size: 14px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        #chat-input:focus {
            outline: none;
        }

        #chat-input-container button {
            padding: 10px;
            background: #06c;
            color: #fff;
            border: none;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            cursor: pointer;
        }

        #chat-input-container button:hover {
            background: #005bb5;
        }

        .user-message, .ai-message {
            margin: 5px 0;
            padding: 10px;
            border-radius: 5px;
            font-size: .85em;
        }

        .user-message {
            background-color: #e1f5fe;
            align-self: flex-end;
        }

        .ai-message {
            background-color: #e0e0e0;
            align-self: flex-start;
        }
        @media (max-width: 600px) {
            .chatbox-container {
                width: 25%;
                bottom: 0;
                right: 0;
                border-radius: 0;
            }

            .chatbox-container.expanded {
                height: 300px; /* Smaller expanded height for smaller screens */
                width:100%;
            }

            #chat-input-container button {
                padding: 8px;
            }

            #chat-input {
                padding: 8px;
                font-size: 12px;
            }
        }
</style>

<footer>
    <div id="bottomnav">
        <a href="index.html">Homepage</a>
        <a href="mycollection.php">MyCollection</a>
        <a href="signin.php">Sign in/up</a>
        <a href="about.html">About</a>
    </div>
    <div id="location">
        <p>© 2024 CollectionKeeper All rights reserved.</p>
    </div>
</footer>
</body>
</html>
