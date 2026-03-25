# Installation Guide

## System Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.2 or higher
- **MySQL**: 5.5 or higher

## Installation Methods

### Method 1: WordPress Admin Dashboard (Recommended)

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins** → **Add New**
3. Click **Upload Plugin**
4. Choose the plugin zip file
5. Click **Install Now**
6. Click **Activate Plugin**

### Method 2: Manual Installation via FTP

1. Download the plugin zip file
2. Extract the zip file: `unzip network-search.zip`
3. Upload the extracted folder to `/wp-content/plugins/` via FTP
4. Log in to WordPress admin
5. Navigate to **Plugins**
6. Find "Network Search" and click **Activate**

### Method 3: SSH/Terminal

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/jaisonbthomas/network-search.git
```

Then activate from WordPress admin panel.

## First Time Setup

### Step 1: Import Sample Data

1. Go to **WordPress Admin** → **Network Search**
2. Click **Choose File**
3. Select `sample-data.csv` from the plugin folder
4. Click **Import CSV**
5. Verify "Rows in database" shows 40 records

### Step 2: Create a Search Page

1. Go to **Pages** → **Add New**
2. Title: "Delivery Checker" (or your preferred name)
3. Add the shortcode: `[pincode_checker]`
4. Click **Publish**
5. View the page to test the search

### Step 3: Test the Search

1. Visit your new page
2. Search for a sample pincode: **380001**, **400001**, or **560001**
3. Verify results appear with delivery zone information
4. Test city search: **Ahmedabad**, **Mumbai**, **Bangalore**

### Step 4: Import Your Data

1. Prepare your CSV with columns: Pincode, City, State, Zone, Vendor, OFD TAT, Location
2. Save as CSV format
3. Go to **WordPress Admin** → **Network Search**
4. Select your CSV file
5. Choose import mode:
   - **Replace all data**: Clears sample data and imports new data
   - **Append**: Adds to existing data
6. Click **Import CSV**

## Troubleshooting

### Plugin not appearing in menu
- Ensure you're logged in as an administrator
- Deactivate and reactivate the plugin
- Check that WordPress version is 5.0+

### Import fails
- Verify CSV file is properly formatted
- Check file size (should be under 10MB)
- Ensure columns include Pincode
- Try converting Excel to CSV UTF-8 format

### Search returns no results
- Make sure you've imported data via admin panel
- Check database has records: Admin → Network Search (shows row count)
- Try the sample data first to test functionality

### Shortcode not working
- Add `[pincode_checker]` to a page, not a post
- Ensure you use exactly: `[pincode_checker]` (lowercase, no spaces)
- Check for plugin conflicts by testing in a development environment

## Uninstallation

1. Go to **Plugins** in WordPress admin
2. Find "Network Search" and click **Deactivate**
3. Click **Delete**
4. Confirm deletion
5. Database table will be retained (can be manually deleted if needed)

## Support

For help:
- Check the [README.md](README.md) for FAQs
- Visit [GitHub Issues](https://github.com/jaisonbthomas/network-search/issues)
- Review [CONTRIBUTING.md](CONTRIBUTING.md) for development help

---

**Need help?** Open an issue on GitHub with details about your setup and the problem you're facing.
