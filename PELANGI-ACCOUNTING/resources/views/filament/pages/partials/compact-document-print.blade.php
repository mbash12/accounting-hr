@php
    $printPaper = request()->query('paper', 'a4');
    $printPageSize = $printPaper === 'a5' ? '210mm 148mm' : '210mm 297mm';
@endphp

<style>
    /* A4 portrait paper with a compact landscape A5 document at the top. */
    @page {
        size: {{ $printPageSize }};
        margin: 0;
    }

    .document-wrapper {
        width: 210mm !important;
        max-width: 210mm !important;
    }

    .document-page {
        width: 210mm !important;
        min-height: 148mm !important;
        height: auto !important;
        padding: 5mm 7mm 4mm !important;
        box-sizing: border-box !important;
    }

    #print-area {
        --compact-border-color: #64748b;
    }

    /* Table-form layout, following the supplied reference. */
    #print-area .header-container {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(62mm, 0.85fr) !important;
        align-items: start !important;
        column-gap: 6mm !important;
        width: 100% !important;
        margin-bottom: 6px !important;
        padding: 4px 5px 5px !important;
        border: 1px solid var(--compact-border-color) !important;
    }

    #print-area .company-branding {
        width: auto !important;
        max-width: none !important;
    }

    #print-area .document-info {
        width: auto !important;
        max-width: none !important;
        flex: none !important;
        padding: 0 !important;
        border: 0 !important;
    }

    #print-area .doc-title {
        padding-bottom: 3px !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
        text-align: center !important;
    }

    #print-area .doc-meta-table,
    #print-area .doc-meta-table td {
        border: 1px solid var(--compact-border-color) !important;
    }

    #print-area .doc-meta-table {
        border-collapse: collapse !important;
    }

    #print-area .doc-meta-label {
        padding-left: 3px !important;
        background: #f8fafc !important;
    }

    #print-area .address-section {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) !important;
        gap: 0 !important;
    }

    #print-area .address-box {
        min-width: 0 !important;
        padding: 4px 5px !important;
        border: 1px solid var(--compact-border-color) !important;
    }

    #print-area .address-box + .address-box {
        border-left: 0 !important;
    }

    #print-area .address-title {
        margin: -4px -5px 3px !important;
        padding: 2px 5px !important;
        background: #f8fafc !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
    }

    #print-area .items-table,
    #print-area .items-table th,
    #print-area .items-table td {
        border: 1px solid var(--compact-border-color) !important;
    }

    #print-area .items-table {
        border-collapse: collapse !important;
    }

    #print-area .items-table th {
        background: #f8fafc !important;
    }

    #print-area .summary-table,
    #print-area .summary-table td {
        border: 1px solid var(--compact-border-color) !important;
    }

    #print-area .summary-table {
        border-collapse: collapse !important;
    }

    #print-area .footer-section {
        border: 1px solid var(--compact-border-color) !important;
        padding: 6px 7px !important;
    }

    #print-area .signature-area {
        padding-left: 10px !important;
        border-left: 1px solid var(--compact-border-color) !important;
    }

    /* Keep the form light: only the item table and total need boxed borders. */
    #print-area .header-container {
        display: flex !important;
        column-gap: 0 !important;
        margin-bottom: 8px !important;
        padding: 0 0 6px !important;
        border: 0 !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
    }

    #print-area .company-branding {
        flex: 1 !important;
    }

    #print-area .document-info {
        flex: 0 0 230px !important;
        border: 0 !important;
    }

    #print-area .doc-title {
        padding-bottom: 0 !important;
        border-bottom: 0 !important;
    }

    #print-area .doc-meta-table,
    #print-area .doc-meta-table td {
        border: 0 !important;
        background: transparent !important;
    }

    #print-area .address-section {
        display: flex !important;
        gap: 15px !important;
    }

    #print-area .address-box,
    #print-area .address-box + .address-box {
        border: 0 !important;
        padding: 0 !important;
    }

    #print-area .address-title {
        margin: 0 0 3px !important;
        padding: 0 0 2px !important;
        background: transparent !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
    }

    #print-area .footer-section {
        border: 0 !important;
        border-top: 1px solid var(--compact-border-color) !important;
        padding: 8px 0 0 !important;
    }

    #print-area .signature-area {
        padding-left: 0 !important;
        border-left: 0 !important;
    }

    /* Tighten the repeated document sections without hiding document data. */
    #print-area .header-container {
        margin-bottom: 8px !important;
        padding-bottom: 6px !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
    }

    #print-area .company-logo {
        max-width: 160px !important;
        max-height: 40px !important;
        margin-bottom: 4px !important;
    }

    #print-area .company-details p {
        margin-bottom: 2px !important;
        font-size: 7pt !important;
        line-height: 1.1 !important;
    }

    #print-area .company-branding h1 {
        margin-bottom: 4px !important;
        font-size: 12pt !important;
    }

    #print-area .company-details .text-bold {
        font-size: 7.5pt !important;
    }

    #print-area .document-info {
        flex-basis: 230px !important;
    }

    #print-area .doc-title {
        margin-bottom: 3px !important;
        font-size: 12.5pt !important;
    }

    #print-area .doc-meta-table td {
        padding: 1px 0 !important;
        font-size: 7.5pt !important;
    }

    #print-area .address-section {
        gap: 0 !important;
        margin-bottom: 8px !important;
    }

    #print-area .address-title {
        margin-bottom: 3px !important;
        padding-bottom: 2px !important;
        font-size: 7pt !important;
    }

    #print-area .recipient-name {
        margin-bottom: 2px !important;
        font-size: 8.5pt !important;
    }

    #print-area p {
        margin-bottom: 2px !important;
        font-size: 7.5pt !important;
    }

    #print-area .text-sm {
        font-size: 7pt !important;
    }

    #print-area .text-xs {
        font-size: 6.5pt !important;
    }

    #print-area .items-table-container {
        margin-bottom: 8px !important;
    }

    #print-area .items-table {
        font-size: 7pt !important;
    }

    #print-area .items-table th {
        padding: 4px 5px !important;
        font-size: 6.5pt !important;
        line-height: 1.25 !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
    }

    #print-area .items-table td {
        padding: 4px 5px !important;
        font-size: 7pt !important;
        line-height: 1.25 !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
    }

    #print-area .summary-section {
        margin-bottom: 0 !important;
    }

    #print-area .summary-table {
        width: 205px !important;
    }

    #print-area .summary-table td {
        padding: 3px 5px !important;
        font-size: 7pt !important;
        line-height: 1.25 !important;
    }

    #print-area .grand-total-row td {
        padding: 4px 5px !important;
        border-top: 1px solid var(--compact-border-color) !important;
        border-bottom: 1px solid var(--compact-border-color) !important;
        font-size: 8pt !important;
    }

    #print-area .footer-section {
        margin-top: 10px !important;
        padding-top: 8px !important;
    }

    #print-area .notes-area {
        padding-right: 15px !important;
    }

    #print-area .signature-line {
        height: 18mm !important;
        margin-top: 2mm !important;
        margin-bottom: 2mm !important;
    }

    #print-area .signature-area > div:first-child {
        margin-bottom: 0 !important;
        font-size: 8.5pt !important;
    }

    #print-area .notes-area h4 {
        margin-bottom: 3px !important;
        font-size: 8pt !important;
    }

    #print-area .status-stamp {
        margin-top: 3px !important;
        padding: 2px 6px !important;
        font-size: 7pt !important;
    }

    @media screen {
        .document-wrapper {
            margin: 20px auto !important;
        }

        .document-page {
            overflow: visible !important;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    }

    @media print {
        html,
        body {
            width: 210mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        #print-area {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 210mm !important;
            max-width: 210mm !important;
            min-height: 148mm !important;
            margin: 0 !important;
            padding: 5mm 7mm 4mm !important;
            overflow: visible !important;
            box-shadow: none !important;
        }

        /* Use one-sided PDF hairlines so adjacent cells never overprint. */
        #print-area .header-container {
            border: 0 !important;
            border-bottom: 0.25pt solid var(--compact-border-color) !important;
        }

        #print-area .doc-title {
            border-bottom: 0 !important;
        }

        #print-area .doc-meta-table,
        #print-area .doc-meta-table td,
        #print-area .address-box,
        #print-area .footer-section,
        #print-area .signature-area {
            border-width: 0 !important;
        }

        #print-area .address-title {
            border-bottom: 0.25pt solid var(--compact-border-color) !important;
        }

        #print-area .items-table,
        #print-area .summary-table {
            border: 0 !important;
            border-top: 0.25pt solid var(--compact-border-color) !important;
            border-left: 0.25pt solid var(--compact-border-color) !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border-radius: 0 !important;
        }

        #print-area .items-table th,
        #print-area .items-table td {
            border: 0 !important;
            border-right: 0.25pt solid var(--compact-border-color) !important;
            border-bottom: 0.25pt solid var(--compact-border-color) !important;
            border-radius: 0 !important;
        }

        #print-area .summary-table td {
            border: 0 !important;
            border-right: 0.25pt solid var(--compact-border-color) !important;
            border-bottom: 0.25pt solid var(--compact-border-color) !important;
            border-radius: 0 !important;
        }

        #print-area .signature-line {
            border: 0 !important;
            border-bottom: 0.25pt solid var(--compact-border-color) !important;
        }

        #print-area .address-box + .address-box {
            border-left: 0 !important;
        }
    }
</style>
