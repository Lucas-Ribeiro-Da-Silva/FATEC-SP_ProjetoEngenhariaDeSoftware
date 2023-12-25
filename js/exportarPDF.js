function downloadPDF() {
  const item = document.querySelector(".divTable");

  
  item.querySelectorAll(".acao").forEach((coluna) => {
    coluna.style.display = "none";
  });

  var opt = {
    margin: 1,
    filename: "estoque.pdf",
    html2canvas: { scale: 2 },
    jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
  };

  html2pdf().set(opt).from(item).save();

  
  setTimeout(() => {
    item.querySelectorAll(".acao").forEach((coluna) => {
      coluna.style.display = "";
    });
  }, 150); 
}
