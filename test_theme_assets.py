import unittest
import os
import re

class TestThemeAssets(unittest.TestCase):
    def test_amidas_assets_exist(self):
        assets = [
            'amidas/index-DsZ0Tq_K.css',
            'amidas/css2.css',
            'amidas/favicon.ico',
            'amidas/katex.min.css',
            'amidas/logo.png',
            'amidas/sso.png'
        ]
        for asset in assets:
            with self.subTest(asset=asset):
                self.assertTrue(os.path.exists(asset), f"Asset {asset} missing")

    def test_index_php_references(self):
        # This test ensures index.php contains the expected FIDO integration points
        # before we start the migration.
        with open('index.php', 'r') as f:
            content = f.read()
            
        required_ids = ['userName', 'userDisplayName', 'rpId', 'remember', 'loading-overlay', 'status-container']
        for element_id in required_ids:
            with self.subTest(element_id=element_id):
                self.assertIn(f'id="{element_id}"', content)
                
        required_functions = ['checkRegistration', 'createRegistration', 'showLoading', 'setStatus']
        for func in required_functions:
            with self.subTest(func=func):
                self.assertIn(f'function {func}', content)

    def test_amidas_index_html_structure(self):
        # Verify amidas/index.html has the expected structure for migration
        with open('amidas/index.html', 'r') as f:
            content = f.read()
            
        self.assertIn('<div id="root">', content)
        self.assertIn('Sign in to your account', content)
        self.assertIn('logo.png', content)

if __name__ == '__main__':
    unittest.main()
