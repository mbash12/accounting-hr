#!/usr/bin/env python3
import json
import csv
import sys

def load_compiled_accounts():
    """Load the compiled account data"""
    try:
        with open('.data/compiled_accounts.json', 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        print(f"Error loading compiled accounts: {e}")
        return []

def find_parent_code(code, all_accounts):
    """Find parent code using simplified logic with compiled data"""
    code_str = str(code)
    
    # Find the current account
    current_account = None
    for account in all_accounts:
        if account['code'] == code_str:
            current_account = account
            break
    
    if not current_account:
        return None, ""
    
    # PRIORITY 1: Use explicit parent_code if available (from hierarchy data)
    if current_account['parent_code']:
        return current_account['parent_code'], current_account['parent_name']
    
    # PRIORITY 2: Use subclassification if available
    if current_account['subclassification_code']:
        # Find parent account info
        for account in all_accounts:
            if account['code'] == current_account['subclassification_code']:
                return account['code'], account['name']
    
    # PRIORITY 3: Use classification if available
    if current_account['classification_code']:
        # Find parent account info
        for account in all_accounts:
            if account['code'] == current_account['classification_code']:
                return account['code'], account['name']
    
    # PRIORITY 4: Use algorithm for pattern matching
    # For codes like 111300000 -> parent should be 111000000
    if len(code_str) == 9 and code_str[3:6] != '000':
        parent_candidate = code_str[:3] + '000000'
        for account in all_accounts:
            if account['code'] == parent_candidate:
                return account['code'], account['name']
    
    # For codes like 100000000 -> parent should be 1
    if len(code_str) == 9 and code_str[1:] == '00000000':
        parent_candidate = code_str[0]
        for account in all_accounts:
            if account['code'] == parent_candidate:
                return account['code'], account['name']
    
    # For codes like 120000000 -> parent should be 100000000
    if len(code_str) == 9 and code_str[1:3] != '00':
        parent_candidate = code_str[:1] + '00' + '000000'
        for account in all_accounts:
            if account['code'] == parent_candidate:
                return account['code'], account['name']
    
    # General pattern: try progressively shorter prefixes with trailing zeros
    for i in range(len(code_str) - 1, 0, -1):
        parent_candidate = code_str[:i] + '0' * (len(code_str) - i)
        for account in all_accounts:
            if account['code'] == parent_candidate:
                return account['code'], account['name']
    
    return None, ""

def main():
    input_file = '.data/compiled_accounts.json'
    output_file = 'accounts.csv'
    
    try:
        # Load compiled account data
        all_accounts = load_compiled_accounts()
        
        if not all_accounts:
            print("No account data found")
            sys.exit(1)
        
        # Prepare CSV data
        csv_data = []
        
        # Add header
        csv_data.append([
            'Code',
            'Name',
            'Alias Name',
            'Is Cash',
            'Is Active',
            'Parent Code',
            'Parent Name',
            'Classification Code',
            'Classification Name',
            'Header'
        ])
        
        # Process each account
        for account in all_accounts:
            code = account['code']
            name = account['name']
            alias_name = account['alias_name']
            is_cash = account['is_cash']
            is_active = account['is_active']
            
            # Find parent code and name
            parent_code, parent_name = find_parent_code(code, all_accounts)
            
            # Get classification info
            class_code = account['classification_code']
            class_name = account['classification_name']
            
            csv_data.append([
                code,
                name,
                alias_name,
                str(is_cash).lower(),
                str(is_active).lower(),
                parent_code if parent_code else '',
                parent_name,
                class_code,
                class_name,
                str(account['header']).lower()
            ])
        
        # Write to CSV
        with open(output_file, 'w', newline='', encoding='utf-8') as csvfile:
            writer = csv.writer(csvfile)
            writer.writerows(csv_data)
        
        print(f"CSV generated successfully: {output_file}")
        print(f"Total rows: {len(csv_data) - 1}")  # Subtract header
        
        # Show some statistics
        hierarchy_count = sum(1 for acc in all_accounts if acc['source'] == 'hierarchy')
        account_count = sum(1 for acc in all_accounts if acc['source'] == 'account')
        
        print(f"Accounts from hierarchy: {hierarchy_count}")
        print(f"Accounts from account.txt: {account_count}")
        
    except FileNotFoundError:
        print(f"Error: Input file '{input_file}' not found")
        sys.exit(1)
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()