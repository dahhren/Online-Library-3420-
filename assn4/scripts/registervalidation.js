document.addEventListener("DOMContentLoaded", () => {
const registerform = document.getElementById("registerform");
const Name = document.getElementById("name");
const username = document.getElementById("username");
const email = document.getElementById("email");
const password = document.getElementById("password");
const confirmpassword = document.getElementById("cpassword");

// create a request object
let request = new XMLHttpRequest();
// set up a callback function
request.addEventListener('load', (ev) => {
    if (request.status != 200) {
        console.log(request);
    }
    else if (request.response == "true") {
        // if the username already exists, call an error and display message
        if (document.getElementById("usernamexist") == null) { 
            username.parentElement.nextElementSibling.insertAdjacentHTML("afterend", "<span id='usernamexist' class='error'>*That user already exists</p>");
        }
    } else {
        // if the username does not exist, do not display error message
        if (document.getElementById("usernamexist") != null) { 
            document.getElementById("usernamexist").remove();
        }
    }
});


// function that removes the hidden class from the error message
showErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.remove("hidden");
}

// function that adds the hidden class to the error message
hideErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.add("hidden");
}

// add an event listener to the form
registerform.addEventListener("submit", (e) => {

    // check if the username exists
    request.open("GET", "./usernamexist.php?username=" + username.value);
    request.send();

    let errors = 0; // variable to keep track of errors

    // check for errors
    if (Name.value === "" || Name.value == null) {
        errors++;
        showErrorMessage(Name)
    } else hideErrorMessage(Name)

    if (username.value === "" || username.value == null) {
        errors++;
        showErrorMessage(username)
    } else hideErrorMessage(username)

    if (email.value === "" || email.value == null) {
        errors++;
        showErrorMessage(email)
    } else hideErrorMessage(email)

    if (password.value === "" || password.value == null) {
        errors++;
        showErrorMessage(password)
    } else hideErrorMessage(password)

    if (confirmpassword.value === "" || password.value !== confirmpassword.value) {
        errors++;
        showErrorMessage(confirmpassword)
      } else hideErrorMessage(confirmpassword) 

    // if there are errors, prevent the form from submitting
    if (errors > 0) {
        e.preventDefault();
    }
})
})