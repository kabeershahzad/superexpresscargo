document.addEventListener("DOMContentLoaded", function () {
  const weightInput = document.getElementById("weight");
  const rateInput = document.getElementById("rate");
  const localChargesInput = document.getElementById("local-charges");
  const packingInput = document.getElementById("packing");
  const totalAmountInput = document.getElementById("total-amount");
  
       // Focus on the "Shipper Name" input field
    document.getElementById("shipper-name").focus();


  // Add event listeners to the necessary fields
  weightInput.addEventListener("input", calculateTotalAmount);
  rateInput.addEventListener("input", calculateTotalAmount);
  localChargesInput.addEventListener("input", calculateTotalAmount);
  packingInput.addEventListener("input", calculateTotalAmount);

 var city_select = "";
  var date_select = "";

  if (sessionStorage.getItem("city_select")) {
    city_select = sessionStorage.getItem("city_select");
  }

  if (city_select != "") {
    document.getElementById("destination").value = city_select;
  }

  if (sessionStorage.getItem("date_select")) {
    date_select = sessionStorage.getItem("date_select");
  }

  if (date_select != "") {
    document.getElementById("date").value = date_select;
  }

  function calculateTotalAmount() {
    const weight = parseFloat(weightInput.value) || 0;
    const rate = parseFloat(rateInput.value) || 0;
    const localCharges = parseFloat(localChargesInput.value) || 0;
    const packing = parseFloat(packingInput.value) || 0;

    const totalAmount = weight * rate + localCharges + packing;
    totalAmountInput.value = totalAmount.toFixed(0);
  }

  const destinationSelect = document.getElementById("destination");
  const receiptInput = document.getElementById("receipt");

  destinationSelect.addEventListener("change", updateReceiptNumber);

  function updateReceiptNumber() {
    const destinationValue = destinationSelect.value;
    const randomNumber = generateRandomNumber(7);

    // Format the receipt number as "SUPER-random-Destination"
    sessionStorage.setItem("city_select", destinationValue);
    const receiptNumber = `SUPER-${randomNumber}-${destinationValue}`;

    receiptInput.value = receiptNumber;
  }

  function generateRandomNumber(digits) {
    const min = Math.pow(10, digits - 1);
    const max = Math.pow(10, digits) - 1;
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }
  updateReceiptNumber();

  const shipperContactInput = document.getElementById("shipper-contact");
  const noNumberCheckbox = document.getElementById("no-number-checkbox");

  const consigneeContactInput = document.getElementById("consignee-contact");
  const noNumberConsigneeCheckbox = document.getElementById(
    "no-number-consignee-checkbox"
  );

  noNumberCheckbox.addEventListener("change", () => {
    if (noNumberCheckbox.checked) {
      shipperContactInput.value = "-";
      shipperContactInput.disabled = true;
    } else {
      shipperContactInput.value = "";
      shipperContactInput.disabled = false;
    }
  });

  noNumberConsigneeCheckbox.addEventListener("change", () => {
    if (noNumberConsigneeCheckbox.checked) {
      consigneeContactInput.value = "-";
      consigneeContactInput.disabled = true;
    } else {
      consigneeContactInput.value = "";
      consigneeContactInput.disabled = false;
    }
  });

  const date_element = document.getElementById("date");

  date_element.addEventListener("change", () => {
    console.log("Hello");
    sessionStorage.setItem("date_select", date_element.value);
  });
});
