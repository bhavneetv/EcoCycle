document.getElementById("manageRoutesBtn").addEventListener("click", () => {
    
    window.location.href = "../dashboard/index.php?page=recycleRequest";
    
 })
 document.getElementById("processPendingBtn").addEventListener("click", () => {
    
    window.location.href = "../dashboard/index.php?page=recyclerScan";
    
 })

 function processBottle() {
   // Add your processing logic here
  window.location.href = "../dashboard/index.php?page=recyclerScan";
}