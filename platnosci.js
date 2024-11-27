document.getElementById("paymentButton").addEventListener("click", function() {
    document.getElementById("paymentModal").style.display = "block";
});

document.getElementById("closeModal").addEventListener("click", function() {
    document.getElementById("paymentModal").style.display = "none";
});

function selectPaymentMethod(method) {
    document.getElementById("selectedPaymentMethod").value = method;

    let allMethods = document.querySelectorAll(".payment-method");
    allMethods.forEach(methodElement => {
        methodElement.classList.remove("selected");
    });

    document.getElementById("paymentMethod" + method).classList.add("selected");
}
