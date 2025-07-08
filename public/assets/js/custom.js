$(document).on("shown.bs.modal", ".modal", function () {
    $(".js-example-basic-single").select2({
        dropdownParent: this,
       
    });
});

$(document).ready(function () {
    $(".data-select2").select2({
        width: "resolve", // need to override the changed default
        theme: "classic",
    });
    $(".js-example-basic-single").select2();
});

document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("backButton")
        .addEventListener("click", function () {
            var button = this;
            button.disabled = true; // Disable the button
            button.classList.remove("waves-effect", "btn-label", "waves-light"); // Remove classes
            button.innerHTML =
                '<i class="mdi mdi-loading mdi-spin"></i> Please wait...'; // Change text to "Please wait" with loading icon

            setTimeout(function () {
                // Simulate a delay for 2 seconds
                button.innerHTML =
                    '<i class="mdi mdi-arrow-left-circle label-icon"></i> Back'; // Change text back to "Back"
                button.classList.add(
                    "waves-effect",
                    "btn-label",
                    "waves-light"
                ); // Add classes back
                button.disabled = false; // Enable the button
            }, 2000);
        });
});

document.addEventListener("DOMContentLoaded", function () {
    var loadButtonElements = document.getElementsByClassName("loadButton");

    for (var i = 0; i < loadButtonElements.length; i++) {
        loadButtonElements[i].addEventListener("click", function () {
            var button = this;
            button.disabled = true;
            button.classList.remove("waves-effect", "btn-label", "waves-light");
            button.innerHTML =
                '<i class="mdi mdi-loading mdi-spin"></i> Please wait...';

            var name = button.getAttribute("name");
            var value = button.getAttribute("value");

            // Create a hidden input element to store the button's name and value
            var hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = name;
            hiddenInput.value = value;
            button.closest("form").appendChild(hiddenInput);

            button.closest("form").submit();
        });
    }
});
