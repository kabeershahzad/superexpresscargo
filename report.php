
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Report</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    <style>
      body {
        padding-top: 60px;
      }
      tr {
        cursor: pointer;
        font-family:Verdana, Geneva, Tahoma, sans-serif;
        font-size: 15px;
        
      }
     
    </style>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.html"
          >Super Express Cargo Service</a
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="./createreceipt.php">Dispatch</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./createreceipt.php">Delivery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./report.php">Report</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
            <!-- Add more menu items for the admin panel -->
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="input-group mt-3">
        <input
          type="text"
          class="form-control"
          id="search-input"
          placeholder="Search"
        />
      </div>
      <div class="dropdown mt-5">
        <label for="entries-per-page">Entries per page:</label>
        <select id="entries-per-page">
          <option value="10">10</option>
          <option value="25">25</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
      <div>
        <button class="btn btn-success mt-5" id="export-btn">
          Export In Excel
        </button>
      </div>
      <table class="table" id="data-table">
        <thead>
          <tr>
            <th>Shipment Id</th>
            <th>Date</th>
            <th>Origin</th>
            <th>Destination</th>
            <th>Receipt#</th>
            <th>Shipper</th>
            <th>Shipper Contact</th>
            <th>Consignee</th>
            <th>Consignee Contact</th>
            <th>Weight</th>
            <th>Pcs</th>
            <th>Rate</th>
            <th>Local</th>
            <th>Packing</th>
            <th>Total Amount</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <?php
          include('config.php');

          $query = "SELECT * FROM shipments ORDER BY shipment_id DESC";
          $result = mysqli_query($con, $query);

          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['shipment_id'] . "</td>";
            echo "<td>" . date("d-m-Y", strtotime($row['date'])) . "</td>";
            echo "<td>" . $row['origin'] . "</td>";
            echo "<td>" . $row['destination'] . "</td>";
            echo "<td>" . $row['receipt_no'] . "</td>";
            echo "<td>" . $row['shipper_name'] . "</td>";
            echo "<td>" . $row['shipper_contact'] . "</td>";
            echo "<td>" . $row['consignee_name'] . "</td>";
            echo "<td>" . $row['consignee_contact'] . "</td>";
            echo "<td>" . $row['weight'] . "</td>";
            echo "<td>" . $row['pieces'] . "</td>";
            echo "<td>" . $row['rate'] . "</td>";
            echo "<td>" . $row['local_charges'] . "</td>";
            echo "<td>" . $row['packing'] . "</td>";
            echo "<td>" . $row['total_amount'] . "</td>";
            echo "</tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class="container mt-3">
      <div class="text-center" id="loading-icon" style="display: none;">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>

   
    <script>

      var searchInput = document.getElementById("search-input");
      var tableBody = document.getElementById("table-body");
      var entriesPerPageSelect = document.getElementById("entries-per-page");
      var currentPage = 1;
      var entriesPerPage = 10;
      var totalPages = 0;

      function updateTablePagination(entriesPerPage) {
    var tableRows = Array.from(tableBody.getElementsByTagName("tr"));
    totalPages = Math.ceil(tableRows.length / entriesPerPage);
    tableRows.forEach(function (row) {
      row.style.display = "none";
    });
    var startIndex = (currentPage - 1) * entriesPerPage;
    var endIndex = startIndex + entriesPerPage;
    var visibleRows = tableRows.slice(startIndex, endIndex);
    visibleRows.forEach(function (row) {
      row.style.display = "";
    });

    // Add click event listener to visible rows
    visibleRows.forEach(function (row) {
          row.addEventListener("click", function () {
            var shipmentId = row.cells[4].textContent;
            var url = "print_invoice.html?id=" + shipmentId;
        window.open(url, "_blank");
      });
    });
  }
      
      

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
    </script>
  </body>
</html>
