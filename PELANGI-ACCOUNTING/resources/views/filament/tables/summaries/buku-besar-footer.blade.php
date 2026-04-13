<div style="background:#334155;border-radius:12px;padding:20px;margin-top:16px;color:white;">
    <table width="100%" style="border-collapse:collapse;">
        <tr>
            <td style="width:20%">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;">Saldo Awal</div>
                <div style="font-size:18px;font-weight:600;">
                    IDR {{ number_format($saldoAwal,0,',','.') }}
                </div>
            </td>

            <td style="width:20%">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;">Mutasi</div>
                <div style="font-size:18px;font-weight:600;">
                    IDR {{ number_format($mutasiBalance,0,',','.') }}
                </div>
            </td>

            <td style="width:20%">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;">Saldo Akhir</div>
                <div style="font-size:18px;font-weight:600;">
                    IDR {{ number_format($saldoAkhir,0,',','.') }}
                </div>
            </td>

            <td style="width:20%;text-align:right">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;">Total Debit</div>
                <div style="font-size:14px;font-weight:600;">
                    IDR {{ number_format($totalDebit,0,',','.') }}
                </div>
            </td>

            <td style="width:20%;text-align:right">
                <div style="font-size:11px;color:#94a3b8;text-transform:uppercase;">Total Kredit</div>
                <div style="font-size:14px;font-weight:600;">
                    IDR {{ number_format($totalKredit,0,',','.') }}
                </div>
            </td>
        </tr>
    </table>
</div>
