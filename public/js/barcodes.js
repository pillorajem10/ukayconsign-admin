// barcodes.js

function printBarcodes() {
    var printContents = document.getElementById("printableArea").innerHTML;
    var originalContents = document.body.innerHTML;

    // Set the content to print
    document.body.innerHTML = printContents;
    
    // Trigger the print dialog
    window.print();
    
    // Restore the original page content after printing
    document.body.innerHTML = originalContents;
}
