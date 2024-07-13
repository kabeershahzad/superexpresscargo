<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Print Receipt</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>
    <script src="qrious.min.js"></script>
  </head>
  <body>
    <script>
      // Get the document ID from the URL
      const params = new URLSearchParams(window.location.search);
      const documentId = params.get("id");

      // Fetch the shipment data using PHP
      fetch(`fetch_shipment.php?id=${documentId}`)
        .then((response) => response.json())
        .then((shipmentData) => {
          if (shipmentData) {
            const formattedDate = moment(shipmentData["date"]).format(
              "DD-MM-YYYY"
            );

            // Create the QR code
            const qr = new QRious({
              element: document.getElementById("qrcode"),
              value: shipmentData["receipt_no"], // Use the receipt number from shipmentData
            });

            const logoDataUrl = "1234d";

            // Create the PDF document definition
            const docDefinition = {
              content: [
                {
                  columns: [
                    // Left column: Shipment Receipt and Date
                    {
                      // width: "auto",
                      // image: logoDataUrl,
                      // fit: [100, 100],
                      // text: "Super Express",
                      // alignment: "left",
                      // bold: true,
                    },
                    // Center column: Shipment Receipt and Date
                    {
                      width: "*",
                      stack: [
                        {
                          text: "Shipment Receipt",
                          style: "header",
                          alignment: "center",
                          margin: [0, 0, 0, 0],
                        },
                        {
                          text: formattedDate,
                          alignment: "center",
                          margin: [0, 0, 0, 0],
                          bold: true,
                        },
                      ],
                    },
                    // Right column: Customer Copy
                    {
                      width: "auto",
                      text: "Customer Copy",
                      alignment: "right",
                      margin: [0, 0, 0, 0],
                    },
                  ],
                },
                // Table with three columns (First copy)
                {
                  table: {
                    widths: ["*", "*", "*"],
                    body: [
                      [
                        {
                          text: "Shipper / Consignee Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Receipt Number",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Shipment Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                      ],
                      [
                        {
                          stack: [
                            [
                              {
                                text: "Consignee Information",
                                alignment: "center",
                                decoration: "underline",
                                margin: [0, 5, 0, 0],
                              },
                              {
                                text: `\nName:     ${shipmentData["consignee_name"]}`,
                                alignment: "left",
                              },
                              {
                                text: `\nContact:  ${shipmentData["consignee_contact"]}\n\n`,
                                alignment: "left",
                              },
                            ],
                            {
                              text: `Shipper Information`,
                              alignment: "center",
                              decoration: "underline",
                              margin: [0, 10, 0, 0],
                            },
                            [
                              {
                                text: `\nName:     ${shipmentData["shipper_name"]}`,
                                alignment: "left",
                              },
                              {
                                text: `\nContact:  ${shipmentData["shipper_contact"]}\n\n`,
                                alignment: "left",
                              },
                            ],
                          ],
                          alignment: "left",
                        },
                        {
                          stack: [
                            {
                              text: `\n\n${shipmentData["receipt_no"]}`,
                              alignment: "center",
                              margin: [0, 5, 0, 30],
                              bold: true,
                              fontSize: "11",
                            },
                            {
                              // Display the QR code here
                              image: qr.toDataURL(),
                              fit: [70, 70], // Adjust size as needed
                              alignment: "center",
                            },
                            {
                              text: `\n\nDestinantion:`,
                              alignment: "center",
                              bold: true,
                              fontSize: "11",
                            },
                            {
                              text: `${shipmentData["destination"]}`,
                              alignment: "center",
                              fontSize: "16",
                            },
                          ],
                        },
                        {
                          table: {
                            widths: ["*", "*"],
                            heights: [20, 20, 20, 20, 20, 20, 20, 20], // Set the desired height for each row
                            body: [
                              [
                                "Origin:",
                                {
                                  text: shipmentData["origin"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Weight:",
                                {
                                  text: shipmentData["weight"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Pcs:",
                                {
                                  text: shipmentData["pieces"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Mode of Payment:",
                                {
                                  text: shipmentData["mode_of_payment"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Rate:",
                                {
                                  text: shipmentData["rate"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Packing:",
                                {
                                  text: shipmentData["packing"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Local Charges:",
                                {
                                  text: shipmentData["local_charges"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Total:",
                                {
                                  text: shipmentData["total_amount"],
                                  alignment: "left",
                                  bold: true,
                                },
                              ],
                            ],
                          },
                          layout: {
                            defaultBorder: false,
                            hLineWidth: function () {
                              return 0;
                            },
                            vLineWidth: function () {
                              return 0;
                            },
                          },
                        },
                      ],
                      [
                        {
                          text: `Main Karachi Office: G-56, Deans Market, Main Tariq Road, Karachi\n\nContact: 021-34382313, 0321-9285851, 0321-8756687`,
                          alignment: "center",
                          colSpan: 3, // Span the entire width of the table
                        },
                      ],
                    ],
                  },
                  layout: {
                    hLineWidth: function (i, node) {
                      return i === 0 || i === node.table.body.length ? 1 : 1;
                    },
                    vLineWidth: function (i, node) {
                      return i === 0 || i === node.table.widths.length ? 1 : 1;
                    },
                    paddingTop: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                    paddingBottom: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                  },

                  margin: [0, 20, 0, 15],
                },
                {
                  text: "-----------------------------------------------------------------------------------------------------------------------------------------------------------",
                  margin: [0, 20, 0, 15],
                },
                {
                  columns: [
                    // Left column: Shipment Receipt and Date
                    {
                      // width: "auto",
                      // image: logoDataUrl,
                      // fit: [100, 100],
                      // text: "Super Express",
                      // alignment: "left",
                      // bold: true,
                    },
                    // Center column: Shipment Receipt and Date
                    {
                      width: "*",
                      stack: [
                        {
                          text: "Shipment Receipt",
                          style: "header",
                          alignment: "center",
                          margin: [0, 0, 0, 0],
                        },
                        {
                          text: formattedDate,
                          alignment: "center",
                          margin: [0, 0, 0, 0],
                          bold: true,
                        },
                      ],
                    },
                    // Right column: Customer Copy
                    {
                      width: "auto",
                      text: "Office Copy",
                      alignment: "right",
                      margin: [0, 0, 0, 0],
                    },
                  ],
                },
                {
                  table: {
                    widths: ["*", "*", "*"],
                    body: [
                      [
                        {
                          text: "Shipper / Consignee Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Receipt Number",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                        {
                          text: "Shipment Info",
                          style: "tableHeader",
                          alignment: "center",
                          margin: [0, 5, 0, 5],
                        },
                      ],
                      [
                        {
                          stack: [
                            [
                              {
                                text: "Consignee Information",
                                alignment: "center",
                                decoration: "underline",
                                margin: [0, 5, 0, 0],
                              },
                              {
                                text: `\nName:     ${shipmentData["consignee_name"]}`,
                                alignment: "left",
                              },
                              {
                                text: `\nContact:  ${shipmentData["consignee_contact"]}\n\n`,
                                alignment: "left",
                              },
                            ],
                            {
                              text: `Shipper Information`,
                              alignment: "center",
                              decoration: "underline",
                              margin: [0, 10, 0, 0],
                            },
                            [
                              {
                                text: `\nName:     ${shipmentData["shipper_name"]}`,
                                alignment: "left",
                              },
                              {
                                text: `\nContact:  ${shipmentData["shipper_contact"]}\n\n`,
                                alignment: "left",
                              },
                            ],
                          ],
                          alignment: "left",
                        },
                        {
                          stack: [
                            {
                              text: `\n\n${shipmentData["receipt_no"]}`,
                              alignment: "center",
                              margin: [0, 5, 0, 30],
                              bold: true,
                              fontSize: "11",
                            },
                            {
                              // Display the QR code here
                              image: qr.toDataURL(),
                              fit: [70, 70], // Adjust size as needed
                              alignment: "center",
                            },
                            {
                              text: `\n\nDestinantion:`,
                              alignment: "center",
                              bold: true,
                              fontSize: "11",
                            },
                            {
                              text: `${shipmentData["destination"]}`,
                              alignment: "center",
                              fontSize: "16",
                            },
                          ],
                        },
                        {
                          table: {
                            widths: ["*", "*"],
                            heights: [20, 20, 20, 20, 20, 20, 20, 20], // Set the desired height for each row

                            body: [
                              [
                                "Origin:",
                                {
                                  text: shipmentData["origin"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Weight:",
                                {
                                  text: shipmentData["weight"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Pcs:",
                                {
                                  text: shipmentData["pieces"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Mode of Payment:",
                                {
                                  text: shipmentData["mode_of_payment"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Rate:",
                                {
                                  text: shipmentData["rate"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Packing:",
                                {
                                  text: shipmentData["packing"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Local Charges:",
                                {
                                  text: shipmentData["local_charges"],
                                  alignment: "left",
                                },
                              ],
                              [
                                "Total:",
                                {
                                  text: shipmentData["total_amount"],
                                  alignment: "left",
                                  bold: true,
                                },
                              ],
                            ],
                          },
                          layout: {
                            defaultBorder: false,
                            hLineWidth: function () {
                              return 0;
                            },
                            vLineWidth: function () {
                              return 0;
                            },
                          },
                        },
                      ],
                      [
                        {
                          text: `Main Karachi Office: G-56, Deans Market, Main Tariq Road, Karachi\n\nContact: 021-34382313, 0321-9285851, 0321-8756687`,
                          alignment: "center",
                          colSpan: 3, // Span the entire width of the table
                        },
                      ],
                    ],
                  },
                  layout: {
                    hLineWidth: function (i, node) {
                      return i === 0 || i === node.table.body.length ? 1 : 1;
                    },
                    vLineWidth: function (i, node) {
                      return i === 0 || i === node.table.widths.length ? 1 : 1;
                    },
                    paddingTop: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                    paddingBottom: function (i) {
                      return i === 2 ? 5 : 0;
                    },
                  },
                  margin: [0, 20, 0, 10],
                },
              ],
              styles: {
                header: {
                  fontSize: 18,
                  bold: true,
                },
                tableHeader: {
                  bold: true,
                  fontSize: 12,
                  color: "black",
                },
              },
              defaultStyle: {},
            };

            // Generate the PDF document
            const pdfDocGenerator = pdfMake.createPdf(docDefinition);

            // Open the PDF document in a new tab as a print prompt
            pdfDocGenerator.getBlob((blob) => {
              const url = URL.createObjectURL(blob);
              const printWindow = window.open(url, "_self");
              printWindow.onload = () => {
                printWindow.print();
                printWindow.onafterprint = () => {
                  URL.revokeObjectURL(url);
                  printWindow.close();
                };
              };
            });
          } else {
            console.log("Document does not exist");
          }
        })
        .catch((error) => {
          console.error("Error fetching data: ", error);
        });
    </script>
  </body>
</html>
