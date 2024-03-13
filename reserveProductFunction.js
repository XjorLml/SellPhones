document.addEventListener("DOMContentLoaded", function () {
    // Get elements
    var quantityInput = document.getElementById("quantityInput");
    var plusBtn = document.getElementById("plusBtn");
    var minusBtn = document.getElementById("minusBtn");
    var reserveNowBtn = document.getElementById("confirmReservationBtn");
    var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    
    // Get the maximum quantity allowed from the server-side (accessing the JavaScript variable)
    maxQuantityAllowed;
    
    // Maximum phones user can reserve
    var maxPhonesToReserve = 3;
    
    // Event listener for the plus button
    plusBtn.addEventListener("click", function () {
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < maxQuantityAllowed && currentQuantity < maxPhonesToReserve) {
            quantityInput.value = currentQuantity + 1;
        }
    });
    
    // Event listener for the minus button
    minusBtn.addEventListener("click", function () {
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    });
    
    // Event listener for the reserve button
    reserveNowBtn.addEventListener("click", function () {
        var selectedQuantity = parseInt(quantityInput.value);
        if (selectedQuantity > maxQuantityAllowed) {
            alert("The selected quantity exceeds the available stock.");
            return; // Prevent further execution
        }
        if (selectedQuantity > maxPhonesToReserve) {
            alert("You can reserve a maximum of " + maxPhonesToReserve + " phones.");
            return; // Prevent further execution
        }
        confirmationModal.show();
    });
    
    // Event listener for the confirmation modal
    document.getElementById("confirmReservation").addEventListener("click", function () {
        confirmationModal.hide();
        document.getElementById("reservationForm").submit();
    });
});
