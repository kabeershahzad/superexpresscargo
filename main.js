var searchInput = document.getElementById("search-input");
var tableBody = document.getElementById("table-body");
var entriesPerPageSelect = document.getElementById("entries-per-page");
var currentPage = 1;
var entriesPerPage = 10;
var totalPages = 0;

// function updateTablePagination(entriesPerPage) {
//   var tableRows = Array.from(tableBody.getElementsByTagName("tr"));
//   totalPages = Math.ceil(tableRows.length / entriesPerPage);
//   tableRows.forEach(function (row) {
//     row.style.display = "none";
//   });
//   var startIndex = (currentPage - 1) * entriesPerPage;
//   var endIndex = startIndex + entriesPerPage;
//   var visibleRows = tableRows.slice(startIndex, endIndex);
//   visibleRows.forEach(function (row) {
//     row.style.display = "";
//   });

//   // Add click event listener to visible rows
//   visibleRows.forEach(function (row) {
//     row.addEventListener("click", function () {
//       var shipmentId = row.cells[4].textContent;
//       var url = "print_invoice.html?id=" + shipmentId;
//       window.open(url, "_blank");
//     });
//   });
// }

function goToPage(page) {
  if (page < 1 || page > totalPages) {
    return;
  }
  currentPage = page;
  updateTablePagination(entriesPerPage);
  updatePaginationButtons();
}

entriesPerPageSelect.addEventListener("change", function () {
  entriesPerPage = parseInt(this.value);
  currentPage = 1;
  updateTablePagination(entriesPerPage);
  updatePaginationButtons();
});

function createPaginationButton(text, page) {
  var button = document.createElement("button");
  button.classList.add("btn", "btn-secondary", "me-2");
  button.textContent = text;
  button.addEventListener("click", function () {
    goToPage(page);
  });
  return button;
}

function updatePaginationButtons() {
  paginationContainer.innerHTML = "";
  var prevButton = createPaginationButton("Previous", currentPage - 1);
  prevButton.disabled = currentPage === 1;
  var nextButton = createPaginationButton("Next", currentPage + 1);
  nextButton.disabled = currentPage === totalPages;
  paginationContainer.appendChild(prevButton);
  paginationContainer.appendChild(nextButton);
}

var paginationContainer = document.createElement("div");
paginationContainer.classList.add("mt-3");
document.querySelector(".container").appendChild(paginationContainer);

searchInput.addEventListener("input", function () {
  var searchValue = searchInput.value.trim().toLowerCase();
  Array.from(tableBody.getElementsByTagName("tr")).forEach(function (row) {
    var shipperName = row.cells[4].textContent.toLowerCase();
    var shipperContact = row.cells[5].textContent.toLowerCase();
    var consigneeName = row.cells[6].textContent.toLowerCase();
    var consigneeContact = row.cells[7].textContent.toLowerCase();
    if (
      shipperName.includes(searchValue) ||
      shipperContact.includes(searchValue) ||
      consigneeName.includes(searchValue) ||
      consigneeContact.includes(searchValue)
    ) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
  currentPage = 1;
  updateTablePagination(entriesPerPage);
  updatePaginationButtons();
});

updateTablePagination(entriesPerPage);
updatePaginationButtons();

document.getElementById("export-btn").addEventListener("click", function () {
  var visibleRows = Array.from(
    document.querySelectorAll("#data-table tbody tr")
  ).filter(function (row) {
    return row.style.display !== "none";
  });
  var filteredTable = document.createElement("table");
  filteredTable.innerHTML = document.getElementById("data-table").innerHTML;
  var existingTBody = filteredTable.querySelector("tbody");
  if (existingTBody) {
    filteredTable.removeChild(existingTBody);
  }
  var newTBody = document.createElement("tbody");
  visibleRows.forEach(function (row) {
    newTBody.appendChild(row.cloneNode(true));
  });
  filteredTable.appendChild(newTBody);
  var wb = XLSX.utils.table_to_book(filteredTable);
  var wbout = XLSX.write(wb, { bookType: "xlsx", type: "array" });
  var blob = new Blob([wbout], { type: "application/octet-stream" });
  saveAs(blob, "table_data.xlsx");
});
