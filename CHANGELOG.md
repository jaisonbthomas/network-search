# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Planned
- Advanced filtering options
- Export search results to CSV
- Admin analytics dashboard
- Bulk import with validation preview

## [1.0.0] - 2026-03-25

### Added
- Initial plugin release
- CSV import with flexible header mapping
- AJAX-powered live search functionality
- Delivery zone detection with Yes/No badges
- Admin interface for CSV management
- Sample data (sample-data.csv) for testing
- WordPress shortcode `[pincode_checker]` for frontend integration
- Responsive UI with modern styling
- Database schema with proper indexing
- Support for pincode, city, state, zone, vendor, OFD TAT, and location fields
- Getting started guide in admin panel
- Comprehensive documentation in README

### Security
- Proper nonce validation for AJAX requests
- SQL prepared statements for database safety
- Capability checks for admin operations
- Proper data escaping and sanitization

### Technical
- Singleton pattern for plugin initialization
- Modular class structure (Admin, Import, Frontend)
- SQL indexes for fast search performance
- Batch import for better performance (500 rows per batch)
- Support for UTF-8 and BOM handling in CSV files

## Legend

- **Added** for new features.
- **Changed** for changes in existing functionality.
- **Deprecated** for soon-to-be removed features.
- **Removed** for now removed features.
- **Fixed** for any bug fixes.
- **Security** in case of vulnerabilities.
