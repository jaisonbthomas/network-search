# Security Policy

## Reporting Security Vulnerabilities

If you discover a security vulnerability in Network Search, please do **not** open a public issue.

Instead, please email security concerns to the maintainers. We take security seriously and will respond promptly to all reports.

### How to Report

1. **Do NOT** create a public GitHub issue
2. **Do NOT** post the vulnerability details publicly
3. **DO** contact the maintainers privately
4. **DO** provide:
   - Description of the vulnerability
   - Steps to reproduce
   - Potential impact
   - Suggested fix (if applicable)

### What to Expect

- Acknowledgment of your report within 48 hours
- Regular updates on our progress
- Credit for the discovery (if desired)
- A reasonable timeframe to fix the issue before public disclosure

## Security Best Practices

When using Network Search, please follow these security best practices:

### For Administrators

- Keep WordPress updated
- Keep PHP updated (7.2+)
- Use strong admin passwords
- Enable two-factor authentication
- Regular database backups
- Use HTTPS on your website
- Keep plugins and themes updated

### For Developers

- Use `wp_kses_post()` for output
- Use `prepare()` for database queries
- Validate and sanitize all user inputs
- Use proper nonce verification
- Check user capabilities with `current_user_can()`
- Don't expose sensitive data in URLs

## Plugin Security Features

Network Search includes:

SQL Prepared Statements - Protects against SQL injection
Nonce Verification - Protects against CSRF attacks
Input Sanitization - Cleans all user inputs
Capability Checks - Only allows authorized users
Escape Output - Prevents XSS vulnerabilities
Safe File Upload Handling - Validates file types and sizes

## Known Issues

Currently, no known security vulnerabilities exist in Network Search v1.0.0.

## Versions & Patches

| Version | Status | Security Updates |
|---------|--------|------------------|
| 1.0.0   | Current | Active support |

## Security Team

- **Lead**: Radweber | Jaison B Thomas
- **Repository**: https://github.com/jaisonbthomas/network-search

## Additional Resources

- [WordPress Plugin Security](https://developer.wordpress.org/plugins/security/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [WordPress Security Handbook](https://developer.wordpress.org/plugins/security/)

---

Thank you for helping keep Network Search secure! 
