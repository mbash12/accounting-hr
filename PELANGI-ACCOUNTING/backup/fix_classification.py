import csv

# Mapping from Tipe Akun to classification_type
mapping = {
    'Kas/Bank': 'cash_bank',
    'Akun Piutang': 'account_receivable',
    'Persediaan': 'inventory',
    'Aktiva Lancar lainnya': 'current_asset',
    'Aktiva Tetap': 'fixed_asset',
    'Akumulasi Penyusutan': 'accumulated_depreciation',
    'Aktiva Lainnya': 'other_asset',
    'Akun Hutang': 'account_payable',
    'Hutang lancar lainnya': 'current_liability',
    'Ekuitas': 'equity',
    'Pendapatan': 'revenue',
    'Harga Pokok Penjualan': 'cogs',
    'Beban': 'expense',
    'Pendapatan lain': 'other_revenue',
    'Beban lain-lain': 'other_expense'
}

# Read source mapping
source = {}
with open('accounts.csv', 'r', encoding='utf-8-sig') as f:
    reader = csv.DictReader(f)
    for row in reader:
        source[row['Akun']] = mapping.get(row['Tipe Akun'], row['Tipe Akun'])

# Update seeder file
rows = []
with open('database/seeders/data/accounts.csv', 'r') as f:
    reader = csv.DictReader(f)
    for row in reader:
        if row['code'] in source:
            row['classification_type'] = source[row['code']]
        rows.append(row)

# Write updated file
with open('database/seeders/data/accounts.csv', 'w', newline='') as f:
    writer = csv.DictWriter(f, fieldnames=rows[0].keys())
    writer.writeheader()
    writer.writerows(rows)

print("Fixed classification_type based on Tipe Akun")
