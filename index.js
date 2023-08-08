var budgetDoc = new jspdf.jsPDF({orientation:'p', unit:'mm', format: 'a4'});
var loanDoc = new jspdf.jsPDF({orientation:'p', unit:'mm', format: 'a4'});

function budgetPDF() {
    const budgetdata = document.getElementById('budgets');
    html2canvas(budgetdata,{scale: 2}).then((canvas) => {
        const imgWidth = 208;
        const pageHeight = 295;
        const margin = [12.7, 12.7];
        var innerPageWidth = imgWidth - margin[0] * 2;
        var innerPageHeight = pageHeight - margin[1] * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        heightLeft -= pageHeight;
        const doc = budgetDoc;
        doc.addImage(canvas, 'PNG', margin[0], margin[1], innerPageWidth, imgHeight, '', 'FAST');
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(canvas, 'PNG', margin[1], margin[0], innerPageWidth, innerPageHeight, '', 'FAST');
            heightLeft -= pageHeight;
        }
        // Header
        var text = "HEKIMA User Reports";
        doc.setFontSize(20);
        doc.setTextColor(40);
        doc.text(text, 10, 10);
        doc.save('Budget reports.pdf');
    });
}

function loanPDF() {
    const loansdata = document.getElementById('loans');
    html2canvas(loansdata,{scale: 2}).then((canvas) => {
        const imgWidth = 208;
        const pageHeight = 295;
        const margin = [12.7, 12.7];
        var innerPageWidth = imgWidth - margin[0] * 2;
        var innerPageHeight = pageHeight - margin[1] * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        heightLeft -= pageHeight;
        const doc = loanDoc;
        doc.addImage(canvas, 'PNG', margin[0], margin[1], innerPageWidth, imgHeight, '', 'FAST');
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(canvas, 'PNG', margin[1], margin[0], innerPageWidth, innerPageHeight, '', 'FAST');
            heightLeft -= pageHeight;
        }
        // Header
        var text = "HEKIMA User Reports";
        doc.setFontSize(20);
        doc.setTextColor(40);
        doc.text(text, 10, 10);
        doc.save('Loan reports.pdf');
    });
}

document.querySelector('#budgetDwnld').addEventListener('click', budgetPDF);
document.querySelector('#loanDwnld').addEventListener('click', loanPDF);