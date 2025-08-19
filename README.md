# Asset Management System

A modern, responsive web application for managing company assets built with HTML, CSS, and JavaScript. This application allows users to track assets, manage check-ins and check-outs, generate reports, and manage user accounts.

## Features

### 🔐 User Management
- Pre-provisioned authentication (no self-registration)
- Role-based access control (Admin)
- User profile management
- Password change (self-service)

### 📦 Asset Management
- Add, view, and manage company assets
- Asset categorization (Laptop, Desktop, Monitor, Printer, Phone, Tablet, Car, etc.)
- Asset status tracking (Available, Checked Out, Issued, Loaned)
- Asset assignment to users (with AD search UI)

### 📊 Scanning System
- Check-in and check-out functionality
- Real-time asset status updates
- Location tracking
- Transaction history
- Scanner-friendly input (Enter submits)

### 📈 Reporting & Analytics
- Asset summary reports
- Transaction reports with date filtering
- User activity reports
- Asset status reports
- Individual user and individual asset reports
- System logs
- Export functionality (CSV)

### 🎨 Modern UI/UX
- Responsive design for all devices
- Modern gradient design
- Interactive elements and animations
- Clean, professional interface

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Storage**: Browser LocalStorage
- **No Backend Required**: Pure client-side application
- **No Dependencies**: No external libraries or frameworks

## Getting Started

### Prerequisites
- A modern web browser (Chrome, Firefox, Safari, Edge)
- No server setup required

### Installation
1. Download or clone the project files
2. Open `index.html` in your web browser
3. Start using the application!

### First Time Setup
1. Login with one of the provisioned accounts:
   - louwS@namwater.com.na / ICT12345678
   - tjivikuaM@namwater.com.na / ICT87654321
   - AssetmanagementAD / NamwaterassetM@654321
2. Add some assets to the system
3. Start tracking asset check-ins and check-outs

## File Structure

```
asset-management-system/
├── index.html              # Home page
├── login.html              # Login page
├── dashboard.html          # Main dashboard
├── scan.html               # Asset scanning page
├── add-asset.html          # Add new assets
├── issue-asset.html        # Issue asset to user
├── loan-return.html        # Loan and return assets
├── reports.html            # Reports and analytics
├── users.html              # User management (legacy)
├── styles.css              # Main stylesheet
├── app.js                  # Main JavaScript application
└── README.md               # This file
```

## Usage Guide

### For Regular Users
1. **Login**: Use your username and password to access the system
2. **Dashboard**: View your assigned assets and recent activity
3. **Scan Assets**: Check in or check out assets using the scan functionality
4. **View Reports**: Access transaction history and asset status

### For Administrators
1. **User Management**: Add, edit, and manage user accounts
2. **Asset Management**: Add new assets and assign them to users
3. **Reports**: Generate comprehensive reports on system usage
4. **System Monitoring**: Track all transactions and user activity

## Data Storage

All data is stored locally in the browser's localStorage. This means:
- ✅ No server required
- ✅ Works offline
- ✅ Fast performance
- ⚠️ Data is browser-specific
- ⚠️ Data is lost if browser data is cleared

## Security Features

- Password hashing (Base64 encoding for demo purposes)
- Session management
- Input validation
- XSS protection through proper HTML escaping

## Browser Compatibility

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Customization

### Adding New Asset Types
Edit the asset type options in `add-asset.html` and `reports.html`:

```html
<option value="new-type">New Asset Type</option>
```

### Modifying Styles
All styling is in `styles.css`.

### Adding New Features
The main application logic is in `app.js`. The `AssetManagementSystem` class contains all the core functionality.

## Troubleshooting

### Common Issues

1. **Data not persisting**: Ensure localStorage is enabled in your browser
2. **Forms not working**: Check that JavaScript is enabled
3. **Styling issues**: Clear browser cache and reload the page

### Browser Console Errors
If you encounter JavaScript errors, check the browser console (F12) for detailed error messages.

## Future Enhancements

- [ ] Real backend with database and AD (LDAP) integration
- [ ] Real-time notifications
- [ ] Barcode/QR code scanning via WebUSB / native bridge
- [ ] Mobile app version
- [ ] Advanced reporting with charts
- [ ] Email notifications
- [ ] Backup and restore functionality
- [ ] Multi-language support

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is open source and available under the [MIT License](LICENSE).

## Support

For support or questions, please open an issue in the repository or contact the development team.

---

**Note**: This is a demonstration application. For production use, consider implementing proper security measures, database storage, and server-side validation.