var userDoc = new jspdf.jsPDF({orientation:'p', unit:'mm', format: 'a4',  userUnit: 144,});
var reportDoc = new jspdf.jsPDF({orientation:'p', unit:'mm', format: 'a4',  userUnit: 144,});
margin = 30; // narrow margin - 12.7 mm
let srcwidth = document.getElementById('users').scrollWidth;
let scale = (595.28 - margin * 2) / srcwidth; // a4 pageSize 595.28

function userPDF() {
    const userdata = document.getElementById('users');
    html2canvas(userdata,{scale: 2}).then((canvas) => {
        const imgWidth = 208;
        const pageHeight = 295;
        const margin = [12.7, 12.7];
        var innerPageWidth = imgWidth - margin[0] * 2;
        var innerPageHeight = pageHeight - margin[1] * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        heightLeft -= pageHeight;
        const docu = userDoc;
        //docu.addImage(canvas, 'PNG', margin[1], margin[0], innerPageWidth, innerPageHeight, '', 'FAST');
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            docu.addPage();
            docu.addImage(canvas, 'PNG', margin[1], margin[0], innerPageWidth, innerPageHeight, '', 'FAST');
            heightLeft -= pageHeight;
        }
        docu.deletePage(1);
        // Header
        var text = "HEKIMA Admin Reports";
        docu.setFontSize(20);
        docu.setTextColor(40);
        docu.text(text, 10, 10);
        docu.save('Current Users.pdf');
    });
}

function reportPDF() {
    const data = document.getElementById('reports');
    html2canvas(data, {scale: 2}).then((canvas) => {
        const imgWidth = 208;
        const pageHeight = 295;
        const margin = [12.7, 12.7];
        var innerPageWidth = imgWidth - margin[0] * 2;
        var innerPageHeight = pageHeight - margin[1] * 2;
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;
        heightLeft -= pageHeight;
        const doc = reportDoc;
        //doc.addImage(canvas, 'PNG', 0, position, imgWidth, innerPageHeight, '', 'FAST');
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(canvas, 'PNG', margin[1], margin[0], innerPageWidth, innerPageHeight, '', 'FAST');
            heightLeft -= pageHeight;
        }
        doc.deletePage(1);
        // Header
        var text = "HEKIMA Admin Reports";
        doc.setFontSize(20);
        doc.setTextColor(40);
        doc.text(text, 10, 5);
        doc.save('User Reports.pdf');
    });
}

document.querySelector('#userDwnld').addEventListener('click', userPDF);
document.querySelector('#reportDwnld').addEventListener('click', reportPDF);