#!/usr/bin/env python3
import json
import sys

def load_json_file(filepath):
    """Load and parse JSON file, removing line numbers if present"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
            # Remove line numbers and clean up
            lines = content.split('\n')
            json_lines = []
            for line in lines:
                if '|' in line:
                    # Remove line number prefix (everything before and including '| ')
                    json_line = line.split('| ', 1)[1]
                    json_lines.append(json_line)
                else:
                    json_lines.append(line)
            
            json_str = '\n'.join(json_lines)
            return json.loads(json_str)
    except Exception as e:
        print(f"Error loading {filepath}: {e}")
        return []

def compile_account_data():
    """Compile account data from both files into a unified structure"""
    
    # Load data from both files
    account_data = load_json_file('.data/account.txt')
    hierarchy_data = load_json_file('.data/data.txt')
    
    # Create a unified dictionary keyed by code
    compiled_accounts = {}
    
    # First, add all accounts from hierarchy data (data.txt)
    for account in hierarchy_data:
        code = str(account.get('code', ''))
        if code:
            compiled_accounts[code] = {
                'code': code,
                'name': account.get('name', ''),
                'alias_name': account.get('alias_name', ''),
                'is_cash': False,  # Default for hierarchy accounts
                'is_active': True,  # Default for hierarchy accounts
                'parent_code': str(account.get('parent', {}).get('code', '')) if 'parent' in account and account['parent'] else '',
                'parent_name': account.get('parent', {}).get('name', '') if 'parent' in account and account['parent'] else '',
                'classification_code': str(account.get('classification', {}).get('code', '')) if 'classification' in account and account['classification'] else '',
                'classification_name': account.get('classification', {}).get('name', '') if 'classification' in account and account['classification'] else '',
                'subclassification_code': '',
                'subclassification_name': '',
                'source': 'hierarchy',  # Track data source
                'header': True  # header: true for classification, false for account
            }
    
    # Then, add/overwrite with detailed accounts from account.txt
    for account in account_data:
        code = str(account.get('code', ''))
        if code:
            # Get classification info
            class_code = ''
            class_name = ''
            if 'classification' in account and account['classification']:
                class_code = str(account['classification'].get('code', ''))
                class_name = account['classification'].get('name', '')
            
            # Get subclassification info
            sub_class_code = ''
            sub_class_name = ''
            if 'subclassification' in account and account['subclassification']:
                sub_class_code = str(account['subclassification'].get('code', ''))
                sub_class_name = account['subclassification'].get('name', '')
            
            compiled_accounts[code] = {
                'code': code,
                'name': account.get('name', ''),
                'alias_name': account.get('alias_name', ''),
                'is_cash': account.get('is_cash', False),
                'is_active': account.get('is_active', False),
                'parent_code': '',  # Will be determined by hierarchy logic
                'parent_name': '',
                'classification_code': class_code,
                'classification_name': class_name,
                'subclassification_code': sub_class_code,
                'subclassification_name': sub_class_name,
                'source': 'account',  # Track data source
                'header': False  # header: true for classification, false for account
            }
    
    # Convert to list and sort by code
    compiled_list = list(compiled_accounts.values())
    compiled_list.sort(key=lambda x: x['code'])
    
    return compiled_list

def main():
    try:
        compiled_data = compile_account_data()
        
        # Save compiled data
        output_file = '.data/compiled_accounts.json'
        with open(output_file, 'w', encoding='utf-8') as f:
            json.dump(compiled_data, f, indent=2, ensure_ascii=False)
        
        print(f"Compiled account data saved to: {output_file}")
        print(f"Total accounts: {len(compiled_data)}")
        
        # Show some statistics
        hierarchy_count = sum(1 for acc in compiled_data if acc['source'] == 'hierarchy')
        account_count = sum(1 for acc in compiled_data if acc['source'] == 'account')
        
        print(f"Accounts from hierarchy (data.txt): {hierarchy_count}")
        print(f"Accounts from account.txt: {account_count}")
        
        # Show some examples
        print("\nSample accounts:")
        for i, acc in enumerate(compiled_data[:5]):
            print(f"  {acc['code']}: {acc['name']} (source: {acc['source']})")
        
    except Exception as e:
        print(f"Error: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()