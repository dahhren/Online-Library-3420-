//this is a javascript file that will display the characters remaining in the description field of the add book form
// defining the variables
let description = document.getElementById("description");
let count = document.getElementById("count");
const max = 2500; // maximum number of characters

const countCharacters = () => {
    let numberofchars = description.value.length; // number of characters in the description field
    let counter = max - numberofchars; // number of characters remaining
    count.textContent = counter + "/2500"; // display the number of characters remaining
};

description.addEventListener("input", countCharacters); // add an event listener to the description field


