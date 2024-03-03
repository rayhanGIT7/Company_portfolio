
  function validateForm() {

    var title = document.getElementById('title').value;
    var service_name = document.getElementById('server_name').value; // Corrected ID

    var image = document.getElementById('image').value;

    // Clear previous error messages
    document.getElementById('titleError').innerHTML = '';
    document.getElementById('service_nameError').innerHTML = '';
    document.getElementById('imageError').innerHTML = '';

    // Perform validation
    if (title.trim() === "") {
        document.getElementById('titleError').innerHTML = 'Please enter Title.';
        return false;
    }
    if (service_name.trim() === "") {
        document.getElementById('service_nameError').innerHTML = 'Please enter some details.';
        return false;
    }
    if (image.trim() === "") {
        document.getElementById('imageError').innerHTML = 'Please select an image.';
        return false;
    }

    document.getElementById('ServiceForm').submit();
}



