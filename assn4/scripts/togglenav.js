// defines the toggleNav function to toggle the display of the nav
const nav = document.getElementById("nav");
const navButton = document.getElementById("togglenav");

let hidden = true;

nav.classList.add("hidden"); // add the hidden class to the nav

// add an event listener to the button
navButton.addEventListener("click", () => { 
    if (hidden) {
        // if the nav is not displayed, display it
        nav.classList.remove("hidden");
        hidden = false;
    } else {
        // if the nav is displayed, hide it
        nav.classList.add("hidden");
        hidden = true;
    }
});