const addbookform = document.getElementById("addbookform");
const title = document.getElementById("title");
const author = document.getElementById("author");
const rating = document.getElementById("rating");
const genre = document.getElementById("genre");
const isbn = document.getElementById("isbn");
const isbnlength = document.getElementById("isbnlength");
const pdate = document.getElementById("pdate");
const coverimg = document.getElementById("coverimg");
const ebook = document.getElementById("ebook");


// function that removes the hidden class from the error message
showErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.remove("hidden");
}

// function that adds the hidden class to the error message
hideErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.add("hidden");
}

// add an event listener to the form
addbookform.addEventListener("submit", (e) => {

    let errors = 0; // variable that keeps track of errors

    // check if the fields are empty
    if (title.value === "" || title.value == null) {
        errors++;
        showErrorMessage(title)
    } else hideErrorMessage(title)

    if (author.value === "" || author.value == null) {
        errors++;
        showErrorMessage(author)
    } else hideErrorMessage(author)

    if (rating.value === "" || rating.value == null) {
        errors++;
        showErrorMessage(rating)
    } else hideErrorMessage(rating)
    
    if (genre.value === "" || genre.value == null) {
        errors++;
        showErrorMessage(genre)
    } else hideErrorMessage(genre)

    if (pdate.value === "" || pdate.value == null) {
        errors++;
        showErrorMessage(pdate)
    } else hideErrorMessage(pdate)

    if (isbn.value === "" || isbn.value == null) {
        errors++;
        showErrorMessage(isbn)
    } else if (isbn.value.length != 13) { // check if the isbn is 13 characters long
        errors++;
        isbnlength.classList.remove("hidden");
    } else {
        hideErrorMessage(isbn)
        isbnlength.classList.add("hidden");
    }

    if (coverimg.value === "" || coverimg.value == null) {
        errors++;
        showErrorMessage(coverimg)
    } else hideErrorMessage(coverimg)

    if (ebook.value === "" || ebook.value == null) {
        errors++;
        showErrorMessage(ebook)
    } else hideErrorMessage(ebook)

    // if there are errors, prevent the form from submitting
    if (errors > 0) {
        e.preventDefault();
    }
})