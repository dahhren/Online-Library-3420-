// function to handle the controls for the modal
displayBook = (bookid) => {
    // get modal element using id
    modal = document.getElementById('modal' + bookid);
    // display modal
    modal.style.display = "block";

    // define the close button
    const close = document.getElementsByClassName("close")[0];

    // when the user clicks on the close button, it will close the modal
    close.onclick = () => {
        modal.style.display = "none";
    }

    // when the user clicks anywhere outside of the modal, it will close the modal
    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}