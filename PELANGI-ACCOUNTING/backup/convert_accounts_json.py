import json
import csv
import os
import re

def clean_code(code):
    # Remove all non-digit characters
    return re.sub(r'\D', '', code)

def get_root_info(digit):
    # Definitions for Level 1 Roots based on standard accounting and sample CSV
    roots = {
        '1': {'name': 'Harta', 'type': 'asset'},
        '2': {'name': 'Kewajiban', 'type': 'liability'},
        '3': {'name': 'Modal', 'type': 'equity'},
        '4': {'name': 'Pendapatan Usaha', 'type': 'revenue'},
        '5': {'name': 'Beban Atas Pendapatan', 'type': 'expense'},
        '6': {'name': 'Beban Operasional', 'type': 'expense'},
        '7': {'name': 'Pendapatan & Beban Non Operasional', 'type': 'expense'}, # Mixed, defaulting to expense/other
        '8': {'name': 'Pendapatan Lain', 'type': 'revenue'},
        '9': {'name': 'Beban Lain', 'type': 'expense'}
    }
    return roots.get(digit, {'name': 'Lainnya', 'type': 'asset'})

def map_type(json_type):
    # Map JSON types to CSV classification_types
    type_map = {
        "Kas/Bank": "current_asset",
        "Aktiva Lancar lainnya": "current_asset",
        "Akun Piutang": "current_asset",
        "Persediaan": "current_asset",
        "Aktiva Tetap": "fixed_asset",
        "Akumulasi Penyusutan": "fixed_asset",
        "Aktiva Lainnya": "asset",
        "Akun Hutang": "liability",
        "Hutang lancar lainnya": "liability",
        "Hutang Jangka Panjang": "liability",
        "Ekuitas": "equity",
        "Pendapatan": "revenue",
        "Pendapatan lain": "revenue",
        "Harga Pokok Penjualan": "expense",
        "Beban": "expense",
        "Beban lain-lain": "expense"
    }
    return type_map.get(json_type, "asset")

def process_node(node, parent_code, level, rows):
    code = clean_code(node['no_akun'])
    name = node['nama_akun']
    description = name # Using name as description
    
    classification = map_type(node.get('tipe_akun', ''))
    
    # Determine flags
    has_children = 'children' in node and len(node['children']) > 0
    is_header = 'true' if has_children else 'false'
    
    # Check if Cash/Bank
    # In JSON "tipe_akun": "Kas/Bank"
    is_cash_bank = 'true' if node.get('tipe_akun') == 'Kas/Bank' else 'false'
    
    is_active = 'true'
    
    row = {
        'code': code,
        'name': name,
        'description': description,
        'classification_type': classification,
        'is_header': is_header,
        'is_cash_bank': is_cash_bank,
        'is_active': is_active,
        'level': level,
        'parent_code': parent_code
    }
    rows.append(row)
    
    if has_children:
        for child in node['children']:
            process_node(child, code, level + 1, rows)

def main():
    input_path = 'accounts.json'
    output_path = 'database/seeders/data/accounts.csv'
    
    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    
    with open(input_path, 'r') as f:
        data = json.load(f)
        
    rows = []
    seen_roots = set()
    accounts = data
    
    # We need to sort accounts or process them such that we can identify roots
    # The JSON structure has top-level accounts. We assume these are children of the Level 1 roots.
    
    for account in accounts:
        # Get the cleaned code to find the first digit
        raw_code = account['no_akun']
        cleaned = clean_code(raw_code)
        
        if not cleaned:
            continue
            
        root_digit = cleaned[0]
        
        # If we haven't created the Level 1 root for this digit, do it now
        if root_digit not in seen_roots:
            root_info = get_root_info(root_digit)
            root_row = {
                'code': root_digit,
                'name': root_info['name'],
                'description': root_info['name'],
                'classification_type': root_info['type'], # Using generic type for root
                'is_header': 'true',
                'is_cash_bank': 'false',
                'is_active': 'true',
                'level': 1,
                'parent_code': ''
            }
            rows.append(root_row)
            seen_roots.add(root_digit)
        
        # Now process the account as a child of the root digit
        # Top level in JSON is Level 2 in our CSV structure (Child of Root)
        process_node(account, root_digit, 2, rows)
        
    fieldnames = ['code', 'name', 'description', 'classification_type', 'is_header', 'is_cash_bank', 'is_active', 'level', 'parent_code']
    
    with open(output_path, 'w', newline='') as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)
        
    print(f"Successfully converted accounts to {output_path}")

if __name__ == '__main__':
    main()