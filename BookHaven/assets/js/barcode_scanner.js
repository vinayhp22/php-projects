// assets/js/barcode_scanner.js
// Example integration using QuaggaJS for barcode scanning.
// Make sure to include the QuaggaJS library from a CDN or local copy before this script runs.

document.addEventListener('DOMContentLoaded', function(){
    var scannerElem = document.getElementById('barcodeScanner');
    if(scannerElem){
       // Initialize QuaggaJS to start video stream and scanning
       Quagga.init({
          inputStream: {
             name: "Live",
             type: "LiveStream",
             target: scannerElem
          },
          decoder: {
             readers: ["ean_reader"]  // Change readers based on your barcode types
          }
       }, function(err) {
          if (err) {
             console.error("QuaggaJS initialization error: ", err);
             return;
          }
          Quagga.start();
       });
       
       // When a barcode is detected, fill the barcode input field and stop scanning
       Quagga.onDetected(function(result){
          document.getElementById('barcodeInput').value = result.codeResult.code;
          Quagga.stop();
       });
    }
 });
 