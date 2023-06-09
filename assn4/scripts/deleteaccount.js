//function to confirm deleting an account, to prevent from accidental deletion of an account.
//this is done by displaying a window in the browser to confirm deletion.
//after deletion, it will delete the book from the database, and redirect the user to the index page.
function confirmDeleteAcc(){
    if (confirm("Are you sure you want to delete your account?")) {
        window.location = "deleteaccount.php" 
    }
}