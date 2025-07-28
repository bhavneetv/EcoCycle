// JavaScript for interactive elements
// Add functionality as needed
// import {Html5QrcodeScanner, Html5QrcodeSupportedFormats} from "html5-qrcode";
const hamburger = document.querySelector(".hamburger")
const nav_menu = document.querySelector(".nav-menu")

let lastScrollTop = 0;
const navbar = document.querySelector('#navbar');

window.addEventListener('scroll', function () {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  if (scrollTop < 10) {

    navbar.style.display = 'block';
  }
  if (scrollTop > lastScrollTop) {

    // Downscroll - hide navbar
    // alert(scrollTop )
    navbar.style.display = 'none';

  } else {
    navbar.style.display = 'block';

  }

  if (scrollTop < 40) {
    navbar.style.display = 'block';
  }
  lastScrollTop = scrollTop;
});

// to add scaning feature 

let qr = document.querySelector('#qr')
let scan = document.querySelector('#scan')
qr.onclick = () => {
  scan.style.display = 'block';
  qr.style.display = 'none';
}

// for counting 
let valueDisplays = document.querySelectorAll(".num");
let interval = 4000;

valueDisplays.forEach((valueDisplay) => {
  let startValue = 0;
  let endValue = parseInt(valueDisplay.getAttribute("data-val"));
  let duration = Math.floor(interval / endValue);
  let counter = setInterval(function () {
    startValue += 1;
    valueDisplay.textContent = startValue;
    if (startValue == endValue) {
      clearInterval(counter);
    }
  }, duration);
});

// for scanning feature 

function onScanSuccess(decodedText, decodedResult) {
  console.log(`Barcode Scanned: ${decodedText}`);
  console.log('Scan result:', decodedResult);
  checkUPC(decodedText);
}

function onScanError(error) {
  console.warn(`Scan Error: ${error}`);
}

// Initialize scanner
let html5QrcodeScanner = new Html5QrcodeScanner(
  "scan",
  {
    fps: 10,
    qrbox: 250,
    supportedFormats: [
      Html5QrcodeSupportedFormats.EAN_13,
      Html5QrcodeSupportedFormats.EAN_8,
      Html5QrcodeSupportedFormats.UPC_A,
      Html5QrcodeSupportedFormats.UPC_E,
      Html5QrcodeSupportedFormats.CODE_39,
      Html5QrcodeSupportedFormats.CODE_128
    ]
  }
);
html5QrcodeScanner.render(onScanSuccess, onScanError);



// to get product detail 

// Function to check UPC code using Open Food Facts API
async function checkUPC(openUPC) {
  const url = `https://world.openfoodfacts.org/api/v0/product/${openUPC}.json`;

  try {
    const response = await fetch(url);
    const data = await response.json();

    // Check if product data was returned
    if (data.status === 1 && data.product) {
      const product = data.product;
      console.log('Product Details:', product);


      // to push product to array
      product_name = product.product_name;
      brands = product.brands;
      categories = product.categories;
      product_quantity = product.product_quantity;
      packaging_tags = product.packaging_tags;
      countries = product.countries;
      packaging = product.packaging;
      _keywords = product._keywords;
      c = product.food_groups

      // array arr that push the value of keywords to onwe array
      var arr = []

      arr.push(product_name, brands, categories, product_quantity, packaging_tags, countries, packaging, c, _keywords)



      // to filter array to remove empty values
      let arr_main = arr.filter(function (e) {
        return e;
      });
      console.log(arr_main)


      // to make a array if there there contain an array on it
      function flattenArray(arr) {
        for (let i = 0; i < arr.length; i++) {
          if (Array.isArray(arr[i])) {
            arr.splice(i, 1, ...arr[i]); // Replace the array with its elements
            i--; // Adjust index after splicing
          }
        }
      }

      flattenArray(arr_main);
      // console.log(arr_main)





      // to print the material
      let mat = 5



      for (var b = 0; b < arr_main.length; b++) {

        if (arr_main[b].match(/beverages/)) {


          if (arr_main[b].match(/plastic/)) {

            mat = 1
            break;
          }

          else if (arr_main[b].match(/can/)) {
            mat = 2
            break;
          }


          else {
            mat = 0

          }

        }
        else {
          console.log('This is not Valid products')
        }
      }


      console.log(mat)
      if (mat == 0) {
        alert('This is a plastic ')
      }

      else if (mat == 1) {
        alert('This is a plastic  ')
      }

      else if (mat == 2) {
        alert('This is a can')
      }





      // alert(arr)

    } else {
      console.log('No product found for this UPC.');
      alert('No product found for this UPC.');
    }
  } catch (error) {
    console.error('Error fetching data:', error);
    alert('Error fetching data. Please try again.');
  }
}

// Example usage



// by btn
document.getElementById('sub').addEventListener('click', function () {
  checkUPC(document.getElementById('inp').value);
});

// checkUPC('8901764061257')