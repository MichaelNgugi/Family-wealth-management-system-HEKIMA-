var assetDoc = new jspdf.jsPDF({orientation:'p', unit:'mm', format: 'a4'});
var investDoc = new jspdf.jsPDF({orientation:'p', unit:'mm', format: 'a4'});
margin = 30; // narrow margin - 12.7 mm
let srcwidth = document.getElementById('assets').scrollWidth;
let scale = (595.28 - margin * 2) / srcwidth; // a4 pageSize 595.28

function assetPDF() {
    const assetdata = document.getElementById('assets');
    html2canvas(assetdata,{scale: 2}).then((canvas) => {
        const imgWidth = 208;
        const pageHeight = 295;
        const margin = [12.7, 12.7];
        var innerPageWidth = imgWidth - margin[0] * 2;
        var innerPageHeight = pageHeight - margin[1] * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        heightLeft -= pageHeight;
        const doc = assetDoc;
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
        doc.save('Asset reports.pdf');
    });
}

function investPDF (){
    const investdata = document.getElementById('investment');
    html2canvas(investdata,{scale: 2}).then((canvas) => {
        const imgWidth = 208;
        const pageHeight = 295;
        const margin = [12.7, 12.7];
        var innerPageWidth = imgWidth - margin[0] * 2;
        var innerPageHeight = pageHeight - margin[1] * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        heightLeft -= pageHeight;
        const doc = investDoc;
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
        doc.save('Investment reports.pdf');
    });
}


document.querySelector('#assetDwnld').addEventListener('click', assetPDF);
document.querySelector('#investDwnld').addEventListener('click', investPDF);