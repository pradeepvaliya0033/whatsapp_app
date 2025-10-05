# WhatsApp Business Provider with Facebook Integration

A comprehensive Laravel application for managing WhatsApp Business API and Facebook Graph API integrations. This application provides a simplified interface for sending WhatsApp messages, managing templates, and connecting Facebook pages.

## 🚀 Features

### WhatsApp Integration
- ✅ Send template messages
- ✅ Send text messages
- ✅ Create and manage WhatsApp templates
- ✅ Direct WhatsApp Business API integration
- ✅ Simplified interface without complex entity/provider management

### Facebook Integration
- ✅ Connect Facebook accounts via OAuth
- ✅ Manage Facebook pages
- ✅ Select default page for posting
- ✅ Post content to Facebook pages
- ✅ View page insights and analytics
- ✅ Automatic token refresh
- ✅ Secure token storage

### User Management
- ✅ User authentication and registration
- ✅ Dashboard with quick actions
- ✅ Settings management
- ✅ Connection status monitoring

## 🛠️ Technology Stack

- **Backend:** Laravel 10
- **Frontend:** Bootstrap 5, Blade Templates
- **Database:** MySQL
- **APIs:** WhatsApp Business API, Facebook Graph API
- **Authentication:** Laravel Sanctum

## 📋 Requirements

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js & NPM (for frontend assets)
- WhatsApp Business Account
- Facebook Developer Account

## 🔧 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/pradeepvaliya0033/whatsapp_app.git
cd whatsapp_app
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment Variables
Edit `.env` file with your configuration:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=whatsapp_app
DB_USERNAME=your_username
DB_PASSWORD=your_password

# WhatsApp Configuration
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_ACCESS_TOKEN=your_access_token
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id
WHATSAPP_APP_SECRET=your_app_secret

# Facebook Configuration
FACEBOOK_APP_ID=your_facebook_app_id
FACEBOOK_APP_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://your-domain.com/facebook/callback
FACEBOOK_API_VERSION=v18.0
```

### 5. Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Frontend Assets
```bash
npm run build
```

### 7. Start the Application
```bash
php artisan serve
```

## 🔑 API Setup

### WhatsApp Business API Setup
1. Create a WhatsApp Business Account
2. Set up a Meta Business account
3. Create a WhatsApp Business App
4. Get your Phone Number ID and Access Token
5. Configure webhook endpoints

### Facebook Graph API Setup
1. Go to [Facebook Developers](https://developers.facebook.com/apps/)
2. Create a new app
3. Add Facebook Login product
4. Set redirect URI: `http://your-domain.com/facebook/callback`
5. Get App ID and App Secret
6. Add required permissions:
   - `pages_manage_metadata`
   - `pages_read_engagement`
   - `pages_show_list`
   - `pages_manage_posts`
   - `pages_read_user_content`

## 📱 Usage

### 1. User Registration/Login
- Register a new account or login with existing credentials
- Access the dashboard after authentication

### 2. WhatsApp Integration
- Navigate to "Messages" to send WhatsApp messages
- Use "Templates" to create and manage message templates
- Send template messages or text messages directly

### 3. Facebook Integration
- Go to "Facebook" settings
- Click "Connect Facebook Account" to start OAuth flow
- Select your default page from connected pages
- Manage your Facebook presence

### 4. Dashboard
- View system status and quick actions
- Access all features from the main dashboard
- Monitor connection status

## 🏗️ Project Structure

```
whatsapp_app/
├── app/
│   ├── Http/Controllers/
│   │   ├── WhatsAppController.php
│   │   ├── FacebookController.php
│   │   ├── AuthController.php
│   │   └── DashboardController.php
│   ├── Services/
│   │   ├── WhatsAppService.php
│   │   └── FacebookService.php
│   ├── Models/
│   │   └── User.php
│   └── ...
├── config/
│   ├── whatsapp.php
│   └── facebook.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── whatsapp/
│   │   ├── facebook/
│   │   ├── templates/
│   │   └── layouts/
│   └── ...
├── routes/
│   ├── web.php
│   └── api.php
└── ...
```

## 🔒 Security Features

- **Token Encryption:** All API tokens are encrypted before storage
- **CSRF Protection:** All forms protected with CSRF tokens
- **Authentication:** Secure user authentication system
- **Input Validation:** Comprehensive input validation
- **Error Handling:** Secure error handling without sensitive data exposure

## 📊 API Endpoints

### WhatsApp Endpoints
- `POST /whatsapp/send-message` - Send template message
- `POST /whatsapp/send-text` - Send text message
- `POST /templates` - Create template
- `GET /templates` - List templates
- `DELETE /templates/{id}` - Delete template

### Facebook Endpoints
- `GET /facebook/redirect` - Start OAuth flow
- `GET /facebook/callback` - Handle OAuth callback
- `POST /facebook/disconnect` - Disconnect account
- `POST /facebook/refresh` - Refresh token
- `POST /facebook/test` - Test connection

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 📈 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Support

If you have any questions or need help with the setup, please:
- Open an issue on GitHub
- Check the documentation
- Contact the maintainer

## 🔄 Changelog

### Version 1.0.0
- Initial release
- WhatsApp Business API integration
- Facebook Graph API integration
- User authentication system
- Dashboard and settings management
- Template management
- Secure token storage

## 📞 Contact

**Developer:** Pradeep Valiya
**GitHub:** [@pradeepvaliya0033](https://github.com/pradeepvaliya0033)
**Repository:** [whatsapp_app](https://github.com/pradeepvaliya0033/whatsapp_app)

---

⭐ **Star this repository if you find it helpful!**