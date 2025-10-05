# WhatsApp Business API Provider

A comprehensive WhatsApp Business API provider built with Laravel, similar to WATI, that allows you to send messages, manage templates, and track message status.

## Features

-   **Message Sending**: Send template-based messages and text messages
-   **Template Management**: Create, read, update, and delete WhatsApp templates
-   **Message Tracking**: Track message status and delivery
-   **Multi-Entity Support**: Support for multiple entities with different provider configurations
-   **Provider Management**: Flexible provider configuration system
-   **Request Logging**: Complete request/response logging for debugging

## Installation

1. Install the required dependencies:

```bash
composer install
```

2. Run the database migrations:

```bash
php artisan migrate
```

3. Configure your environment variables in `.env`:

```env
# WhatsApp Business API Configuration
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_ACCESS_TOKEN=your_access_token
WHATSAPP_BUSINESS_ACCOUNT_ID=your_business_account_id
WHATSAPP_APP_SECRET=your_app_secret
WHATSAPP_DEFAULT_LANGUAGE=en_US
WHATSAPP_TIMEOUT=30
WHATSAPP_WEBHOOK_VERIFY_TOKEN=your_webhook_verify_token
WHATSAPP_WEBHOOK_URL=https://your-domain.com/api/whatsapp/webhook
```

## Database Setup

The system uses the following main tables:

-   `entity_masters`: Stores entity information
-   `provider_masters`: Stores WhatsApp provider configurations
-   `entity_provider_mappings`: Maps entities to providers
-   `message_requests`: Tracks all message requests and responses

## API Endpoints

### Message Sending

#### Send Template Message

```http
POST /api/whatsapp/send-message
Content-Type: application/json

{
    "entity_id": "entity-uuid",
    "provider_id": "provider-uuid", // optional
    "to": ["+1234567890", "+0987654321"],
    "template_name": "welcome_template",
    "lang_code": "en_US",
    "parameters": ["John", "12345"] // optional
}
```

#### Send Text Message

```http
POST /api/whatsapp/send-text
Content-Type: application/json

{
    "entity_id": "entity-uuid",
    "provider_id": "provider-uuid", // optional
    "to": "+1234567890",
    "message": "Hello, this is a test message!"
}
```

### Template Management

#### Create Template

```http
POST /api/whatsapp/templates
Content-Type: application/json

{
    "name": "welcome_template",
    "category": "TRANSACTIONAL",
    "lang_code": "en_US",
    "header": {
        "type": "text",
        "text": "Welcome!"
    },
    "body": "Hello {{1}}, your verification code is {{2}}",
    "footer": "Thank you for using our service",
    "buttons": [
        {
            "type": "quick_reply",
            "text": "Get Started"
        }
    ]
}
```

#### Get All Templates

```http
GET /api/whatsapp/templates
```

#### Get Specific Template

```http
GET /api/whatsapp/templates/{template_id}
```

#### Delete Template

```http
DELETE /api/whatsapp/templates/{template_id}
```

### Message Status & History

#### Get Message Status

```http
GET /api/whatsapp/messages/{message_id}/status
```

#### Get Message Requests

```http
GET /api/whatsapp/messages?entity_id=entity-uuid&status=sent&limit=20&page=1
```

## Usage Examples

### Setting Up Entities and Providers

1. Create an entity:

```php
$entity = EntityMaster::create([
    'name' => 'My Company',
    'description' => 'Main company entity',
    'status' => true
]);
```

2. Create a provider:

```php
$provider = ProviderMaster::create([
    'name' => 'WhatsApp Business',
    'description' => 'Main WhatsApp provider',
    'provider_type' => 'WhatsApp',
    'api_config' => [
        'api_url' => 'https://graph.facebook.com/v18.0',
        'phone_number_id' => 'your_phone_number_id',
        'access_token' => 'your_access_token',
        'business_account_id' => 'your_business_account_id',
        'app_secret' => 'your_app_secret'
    ],
    'status' => true
]);
```

3. Map entity to provider:

```php
EntityProviderMapping::create([
    'entity_id' => $entity->id,
    'provider_id' => $provider->id,
    'usage_type' => 'WhatsApp',
    'is_default' => true,
    'status' => true
]);
```

### Sending Messages

```php
// Using the controller
$controller = new WhatsAppController(new WhatsAppService());

$request = new Request([
    'entity_id' => $entity->uuid,
    'to' => ['+1234567890'],
    'template_name' => 'welcome_template',
    'lang_code' => 'en_US',
    'parameters' => ['John', '12345']
]);

$response = $controller->sendMessage($request);
```

## Error Handling

The system includes comprehensive error handling and logging:

-   All API calls are logged with request/response data
-   Errors are caught and returned in a consistent format
-   Database transactions ensure data consistency
-   Validation errors are returned with detailed messages

## Security Features

-   App secret proof generation for API authentication
-   Request validation and sanitization
-   Secure token handling
-   Database foreign key constraints
-   UUID-based entity identification

## Monitoring and Logging

All WhatsApp API interactions are logged to Laravel's log system with:

-   Request payloads
-   Response data
-   Error messages and stack traces
-   Performance metrics

## Customization

The system is designed to be easily extensible:

-   Add new provider types by extending the `ProviderMaster` model
-   Customize message templates through the template management API
-   Add webhook handlers for real-time message status updates
-   Implement additional message types (media, interactive messages, etc.)

## Support

For issues and questions, please check the Laravel logs and ensure all environment variables are properly configured.
