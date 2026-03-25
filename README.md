# Network Search

> A lightweight, professional WordPress plugin for searching pincodes, cities, and delivery zones via AJAX-powered live search.

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-GPL--2.0%2B-green)
![WordPress](https://img.shields.io/badge/wordpress-%3E%3D5.0-blue)
![PHP](https://img.shields.io/badge/php-%3E%3D7.2-blue)

## ✨ Features

- **⚡ Live Search**: AJAX-powered search for pincodes or areas with instant results
- **📁 CSV Import**: Upload CSV files with flexible column headers for bulk data import
- **🎯 Delivery Zone Check**: Automatically determines if an area is in the delivery zone
- **📱 Responsive UI**: Clean, modern interface that works on all devices
- **🔧 Easy Integration**: Simple `[pincode_checker]` shortcode for any page
- **⚙️ Admin Panel**: Simple interface for importing data and viewing statistics
- **📊 Sample Data**: Includes test data from major Indian cities for quick testing
- **🔐 Secure**: Proper nonces, sanitization, and database prepared statements

## Features

- **Live Search**: AJAX-powered search for pincodes or areas with instant results.
- **CSV Import**: Upload CSV files with flexible column headers for bulk data import.
- **Delivery Zone Check**: Automatically determines if an area is in the delivery zone based on zone data.
- **Responsive UI**: Clean, modern interface that works on all devices.
- **Shortcode Support**: Easy integration with `[pincode_checker]` shortcode.
- **Admin Interface**: Simple admin panel for importing data and viewing statistics.

## 🚀 Quick Start

### Installation

1. Download the plugin
2. Upload to `/wp-content/plugins/`
3. Activate from WordPress admin
4. Done! 🎉

**Detailed instructions?** See [INSTALL.md](INSTALL.md)

### First-Time Setup (2 Minutes)

```
1. Admin → Network Search
2. Upload: sample-data.csv
3. Click Import CSV
4. Create page with: [pincode_checker]
5. Test search! (Try: 380001, 400001, 560001)
```

**Full walkthrough?** Check [INSTALL.md](INSTALL.md)

## Usage

### Importing Data

1. Prepare a CSV file with the following columns (headers are flexible):
   - Pincode
   - City
   - State
   - Zone
   - Vendor
   - OFD TAT (Out for Delivery Turnaround Time)
   - Location

2. Go to WordPress Admin > Pincode Finder.
3. Upload your CSV file.
4. Choose import mode: Replace all data or Append.
5. Click "Import CSV".

### Displaying the Search Form

Add the shortcode `[pincode_checker]` to any page or post to display the search form.

### Search Behavior

- Enter a pincode (e.g., 380001) or area name (e.g., Andheri).
- The search is live and updates as you type (with 250ms debounce).
- Results show delivery availability and detailed information in a table.

## CSV Format

Your CSV should have headers that can be automatically mapped. Common variations are supported:

- Pincode: pincode, pin, pin_code, postal, postalcode, zipcode
- City: city, district, town
- State: state, stateut, province
- Zone: zone, deliveryzone, servicezone, region
- Vendor: vendor, partner, carrier, courier
- OFD TAT: ofdtat, tat, ofd, ofdtatoutfordeliveryturnaroundtime, turnaround, turnaroundtime
- Location: location, area, locality, place, neighborhood, neighbourhood

Example CSV:

```
Pincode,City,State,Zone,Vendor,OFD TAT,Location
380001,Ahmedabad,Gujarat,Local,ABC Courier,1-2 days,CG Road
400001,Mumbai,Maharashtra,Metro,XYZ Logistics,2-3 days,Fort
```

## 📋 Documentation

- **[INSTALL.md](INSTALL.md)** - Detailed installation and setup guide
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - How to contribute to the project
- **[CHANGELOG.md](CHANGELOG.md)** - Version history and updates
- **[GITHUB_SETUP.md](GITHUB_SETUP.md)** - Git setup and push to GitHub

## 🔧 Technical Details

### Architecture

```
Network Search
├── Plugin File (network-search.php)
│   └── Initializes classes and hooks
├── Admin Class (class-admin.php)
│   └── WordPress admin menu and UI
├── Import Class (class-import.php)
│   └── CSV parsing and database insertion
├── Frontend Class (class-frontend.php)
│   └── Shortcode and AJAX handlers
├── CSS Assets
│   └── Responsive styling
└── JavaScript Assets
    └── Live search functionality
```

### Database Schema

```sql
wp_network_search {
  id (INT, Primary Key)
  pincode (VARCHAR 20) [indexed]
  city (VARCHAR 191) [indexed]
  state (VARCHAR 191)
  zone (VARCHAR 191) [indexed]
  vendor (VARCHAR 191)
  ofd_tat (VARCHAR 191)
  location (VARCHAR 191) [indexed]
  updated_at (DATETIME)
}
```

### Performance

- **Batch Import**: 500 rows per batch for large files
- **Database Indexes**: 4 indexes for fast searching
- **AJAX**: Debounced at 250ms to reduce server load
- **Result Limit**: 800 records max per search

## 📦 File Structure

```
network-search/
├── network-search.php      # Main plugin
├── includes/
│   ├── class-network-search.php
│   ├── class-admin.php
│   ├── class-import.php
│   └── class-frontend.php
├── assets/
│   ├── css/network-search.css
│   └── js/network-search.js
├── sample-data.csv              # Test data
├── README.md                     # This file
├── INSTALL.md                    # Installation guide
├── CONTRIBUTING.md               # Contributing guidelines
├── CHANGELOG.md                  # Version history
├── LICENSE                       # GPL-2.0+
├── .gitignore
├── .gitattributes
└── GITHUB_SETUP.md              # GitHub setup guide
```

## Hooks and Filters

### Actions

- `pfl_before_import`: Fires before CSV import begins
- `pfl_after_import`: Fires after CSV import completes

### Filters

- `pfl_csv_mimes`: Modify allowed CSV mime types
- `pfl_search_limit`: Change the maximum number of search results (default: 800)
- `pfl_batch_size`: Change the import batch size (default: 500)

## Frequently Asked Questions

**Q: The search returns no results. What should I do?**

A: Make sure you've imported CSV data via the admin panel. Use the included `sample-data.csv` file to test immediately. An empty database will show no results.

**Q: How do I get started quickly?**

A: Use the sample data! The plugin includes `sample-data.csv` with test data from major Indian cities. Upload it via Admin > Network Search and test the search.

**Q: How do I customize the appearance?**

A: The plugin uses CSS classes prefixed with `pfl-`. You can override styles in your theme's CSS.

**Q: Can I import data programmatically?**

A: Yes, use the `PFL_Import` class methods or directly insert into the database table.

## Changelog

### 1.0.0
- Initial release
- CSV import with flexible headers
- AJAX live search
- Shortcode integration
- Admin interface with getting started guide
- Included sample data (sample-data.csv) for quick testing

## License

This plugin is licensed under the GPL-2.0+.

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines on:

- Reporting bugs
- Suggesting features
- Code contributions
- Pull request process

## 📄 License

This plugin is licensed under the **GPL-2.0+** license. See [LICENSE](LICENSE) for full details.

## 👥 Author

**Radweber | Jaison B Thomas**
- GitHub: [@jaisonbthomas](https://github.com/jaisonbthomas)
- Repository: [network-search](https://github.com/jaisonbthomas/network-search)

## 🆘 Support & Issues

- **Bug Reports**: [GitHub Issues](https://github.com/jaisonbthomas/network-search/issues)
- **Feature Requests**: [GitHub Discussions](https://github.com/jaisonbthomas/network-search/discussions)
- **Documentation**: [INSTALL.md](INSTALL.md) and [README.md](README.md)

## ⭐ Show Your Support

If you find this plugin useful, please give it a ⭐ on GitHub!

---

**Made with ❤️ by Radweber for the WordPress community.**