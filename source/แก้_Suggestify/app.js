$(document).ready(() => {
    $('#hamburger-menu').click(() => {
        $('#hamburger-menu').toggleClass('active')
        $('#nav-menu').toggleClass('active')
    })

    // setting owl carousel

    let navText = ["<i class='bx bx-chevron-left'></i>", "<i class='bx bx-chevron-right'></i>"]

    $('#hero-carousel').owlCarousel({
        items: 1,
        dots: false,
        loop: true,
        nav:true,
        navText: navText,
        autoplay: true,
        autoplayHoverPause: true
    })

    $('#top-movies-slide').owlCarousel({
        items: 2,
        dots: false,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        responsive: {
            500: {
                items: 3
            },
            1280: {
                items: 4
            },
            1600: {
                items: 6
            }
        }
    })

    $('.movies-slide').owlCarousel({
        items: 2,
        dots: false,
        nav:true,
        navText: navText,
        margin: 15,
        responsive: {
            500: {
                items: 2
            },
            1280: {
                items: 4
            },
            1600: {
                items: 6
            }
        }
    })
})

//crudนะครับ
// app.js

// Function to load suggestions from local storage
function loadSuggestions() {
    const suggestions = JSON.parse(localStorage.getItem('suggestions')) || [];
    const suggestionsList = document.getElementById('suggestions-list');
    suggestionsList.innerHTML = '';

    suggestions.forEach((suggestion, index) => {
        const suggestionDiv = document.createElement('div');
        suggestionDiv.className = 'suggestion';
        suggestionDiv.innerHTML = `
            <span>${suggestion}</span>
            <button onclick="editSuggestion(${index})">Edit</button>
            <button onclick="deleteSuggestion(${index})">Delete</button>
        `;
        suggestionsList.appendChild(suggestionDiv);
    });
}

// Function to add a new suggestion
document.getElementById('add-suggestion').addEventListener('click', () => {
    const suggestionInput = document.getElementById('suggestion-input');
    const suggestionText = suggestionInput.value.trim();

    if (suggestionText) {
        const suggestions = JSON.parse(localStorage.getItem('suggestions')) || [];
        suggestions.push(suggestionText);
        localStorage.setItem('suggestions', JSON.stringify(suggestions));
        suggestionInput.value = '';
        loadSuggestions();
    } else {
        alert('Please enter a suggestion.');
    }
});

// Function to edit a suggestion
function editSuggestion(index) {
    const suggestions = JSON.parse(localStorage.getItem('suggestions'));
    const newSuggestion = prompt('Edit your suggestion:', suggestions[index]);

    if (newSuggestion !== null) {
        suggestions[index] = newSuggestion;
        localStorage.setItem('suggestions', JSON.stringify(suggestions));
        loadSuggestions();
    }
}

// Function to delete a suggestion
function deleteSuggestion(index) {
    const suggestions = JSON.parse(localStorage.getItem('suggestions'));
    suggestions.splice(index, 1);
    localStorage.setItem('suggestions', JSON.stringify(suggestions));
    loadSuggestions();
}

// Load suggestions on page load
window.onload = loadSuggestions;

//sql
// app.js
const mysql = require('mysql');
const express = require('express');
const cors = require('cors');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

app.use(cors());  // To allow cross-origin requests
app.use(bodyParser.json());  // To parse JSON requests

// MySQL Database Connection
const db = mysql.createConnection({
    host: '10.1.3.40',
    user: '66102010584',
    password: '66102010584',
    database: 'series_anime' // Replace with your actual database name
});

// Connect to MySQL
db.connect((err) => {
    if (err) {
        console.log('Error connecting to MySQL: ' + err.stack);
        return;
    }
    console.log('Connected to MySQL as ID ' + db.threadId);
});

// POST request to add a suggestion to the database
app.post('/add-suggestion', (req, res) => {
    const { suggestionText } = req.body;
    const query = 'INSERT INTO Suggestions (suggestion_text) VALUES (?)';

    db.query(query, [suggestionText], (err, result) => {
        if (err) {
            console.log('Error inserting suggestion: ' + err.stack);
            res.status(500).send('Error inserting suggestion');
        } else {
            res.status(200).send('Suggestion added successfully');
        }
    });
});

// Get all suggestions from the database
app.get('/get-suggestions', (req, res) => {
    const query = 'SELECT * FROM Suggestions';
    db.query(query, (err, results) => {
        if (err) {
            console.log('Error fetching suggestions: ' + err.stack);
            res.status(500).send('Error fetching suggestions');
        } else {
            res.json(results);
        }
    });
});

// Start the server
app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
