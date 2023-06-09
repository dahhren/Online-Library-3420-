//function to confirm deleting a book, to prevent from accidental deletion of a book
//this is done by displaying a window in the browser to confirm deletion.
//after deletion, it will delete the book from the database, and redirect the user to the index page.
const confirmDeleteBook = (bookid) => {
    if (confirm("This action will delete the book, and is not recoverable! Are you sure you want to delete this book?")) {
        window.location = "deletebook.php?bookid=" + bookid
    }
}